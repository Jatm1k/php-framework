<?php

use App\Services\UserService;
use Doctrine\DBAL\Connection;
use League\Container\Container;
use Jatmy\Framework\Http\Kernel;
use Jatmy\Framework\Routing\Router;
use Jatmy\Framework\Session\Session;
use Symfony\Component\Dotenv\Dotenv;
use Jatmy\Framework\Console\Application;
use Jatmy\Framework\Template\TwigFactory;
use League\Container\ReflectionContainer;
use Jatmy\Framework\Event\EventDispatcher;
use Jatmy\Framework\Dbal\ConnectionFactory;
use Jatmy\Framework\Routing\RouterInterface;
use Jatmy\Framework\Session\SessionInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Jatmy\Framework\Controller\AbstractController;
use Jatmy\Framework\Http\Middleware\RequestHandler;
use Jatmy\Framework\Http\Middleware\RouterDispatch;
use Jatmy\Framework\Console\Commands\MigrateCommand;
use Jatmy\Framework\Console\Kernel as ConsoleKernel;
use League\Container\Argument\Literal\ArrayArgument;
use Jatmy\Framework\Http\Middleware\ExtractRouteInfo;
use League\Container\Argument\Literal\StringArgument;
use Jatmy\Framework\Authenication\SessionAuthenication;
use Jatmy\Framework\Authenication\SessionAuthInterface;
use Jatmy\Framework\Authenication\UserServiceInterface;
use Jatmy\Framework\Console\Commands\MigrateRollbackCommand;
use Jatmy\Framework\Http\Middleware\RequestHandlerInterface;

$dotenv = new Dotenv();
$dotenv->load(BASE_PATH.'/.env');

// Application parameters

$routes = include BASE_PATH.'/routes/web.php';
$appEnv = $_ENV['APP_ENV'] ?? 'local';
$viewsPath = BASE_PATH.'/views';
$databaseUrl = 'pdo-mysql://lemp:lemp@database:3306/lemp?charset=utf8mb4';

// Application services

$container = new Container();

$container->delegate(new ReflectionContainer(true));

$container->add('framework-commands-namespace', new StringArgument('Jatmy\\Framework\\Console\\Commands\\'));

$container->add('APP_ENV', new StringArgument($appEnv));

$container->add(RouterInterface::class, Router::class);

$container->add(RequestHandlerInterface::class, RequestHandler::class)
    ->addArgument($container);

$container->addShared(EventDispatcherInterface::class, EventDispatcher::class);

$container->add(Kernel::class)
    ->addArguments([
        $container,
        RequestHandlerInterface::class,
        EventDispatcherInterface::class,
    ]);

$container->addShared(SessionInterface::class, Session::class);

$container->add('twig-factory', TwigFactory::class)
    ->addArguments([
        new StringArgument($viewsPath),
        SessionInterface::class,
        SessionAuthInterface::class,
    ]);

$container->addShared('twig', function () use ($container) {
    return $container->get('twig-factory')->create();
});
$container->inflector(AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

$container->add(ConnectionFactory::class)
    ->addArgument(new StringArgument($databaseUrl));

$container->addShared(Connection::class, function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});

$container->add(Application::class)
    ->addArgument($container);

$container->add(ConsoleKernel::class)
    ->addArgument($container)
    ->addArgument(Application::class);

$container->add('console:migrate', MigrateCommand::class)
    ->addArgument(Connection::class)
    ->addArgument(new StringArgument(BASE_PATH . '/database/migrations'));
$container->add('console:migrate:rollback', MigrateRollbackCommand::class)
    ->addArgument(Connection::class)
    ->addArgument(new StringArgument(BASE_PATH . '/database/migrations'));

$container->add(RouterDispatch::class)
    ->addArguments([
        RouterInterface::class,
        $container,
    ]);

$container->add(SessionAuthInterface::class, SessionAuthenication::class)
    ->addArgument(UserService::class)
    ->addArgument(SessionInterface::class);

$container->add(ExtractRouteInfo::class)
    ->addArgument(new ArrayArgument($routes));
return $container;
