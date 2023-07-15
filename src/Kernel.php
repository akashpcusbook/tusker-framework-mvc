<?php

namespace Tusker\App;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Tusker\App\Services\TwigServices;
use Tusker\Framework\Auth\AuthInterface;
use Tusker\Framework\Manager\Object\ObjectManager;
use Tusker\Framework\Router\RouteResolver;
use Tusker\Framework\Support\Session;
use Twig\Loader\FilesystemLoader;

class Kernel
{
    private ObjectManager $objects;

    public function __construct()
    {
        $this->objects = getObjectManager();
    }
    public function load(): void
    {
        if (PHP_SAPI !== 'cli') {
            Session::start();
        }
        framework()->load();
        $this->loadORM();
        $this->loadDependencyFromConfig();
        $this->loadTemplate();
        
        $this->loadServices();
        
        $this->loadAuth();
        $this->loadRoutes();
        $this->resolveRoutes();
        $this->loadCommands();

        $this->removeSession();
        
    }

    private function loadDependencyFromConfig(): void
    {
        $dependency = config('dependency');

        foreach ($dependency['variables'] as $key => $value) {
            $this->objects->addVariables($key, $value);
        }

        foreach ($dependency['class'] as $class) {
            $this->objects->add($class);
        }

        foreach ($dependency['interface'] as $key => $value) {
            $this->objects->add($key, $value);
        }
    }

    private function loadRoutes(): void
    {
        config('routes');
    }

    private function resolveRoutes(): void
    {
        if (PHP_SAPI !== 'cli') {
            /**
             * @var RouteResolver|null $routeResolver
             */
            $routeResolver = $this->objects->get(RouteResolver::class);
            if (null !== $routeResolver) {
                $routeResolver->resolve();
            }
        }
    }

    private function loadTemplate(): void
    {
        $loader = new FilesystemLoader(app_path('templates/'));
        $twig = new \Twig\Environment($loader);

        $this->objects->addVariables('template', $twig);
    }

    private function loadServices(): void
    {
        $this->objects->add(TwigServices::class);

        /**
         * @var TwigServices|null $twigService
         */
        $twigService = $this->objects->get(TwigServices::class);

        if (null !== $twigService) {
            $twigService->addFunctions();
        }
    }

    private function loadCommands(): void
    {
        $commands = config('commands');
        foreach ($commands as $command) {
            $this->objects->add($command);
        }
    }

    private function loadORM(): void
    {
        $paths = [
            app_path('src/Entities')
        ];
        $isDevMode = 'dev' === env('APP_MODE', 'dev') ? true : false;

        $dbParams = [
            'driver'   => env('DB_DRIVER', 'pdo_mysql'),
            'user'     => env('DB_USER', 'root'),
            'password' => env('DB_PASS', ''),
            'dbname'   => env('DB_SCHEMA', 'tusker'),
            'host'     => env('DB_HOST', 'localhost'),
            'port'     => (int)env('DB_PORT', '3306'),
        ];

        $config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
        $connection = DriverManager::getConnection($dbParams, $config);
        $entityManager = new EntityManager($connection, $config);

        $this->objects->addVariables('entityManager', $entityManager);
    }

    public function removeSession(): void
    {
        Session::remove('temp');
    }

    public function loadAuth(): void
    {
        $auth = config('auth');

        $this->objects->add(AuthInterface::class, $auth['provider_class']);
    }
}
