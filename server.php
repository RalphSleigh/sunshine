#!/php -q
<?php  
namespace Ralphie\Sunshine;
use \stdClass;
 
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require 'vendor/autoload.php';
require "config.php";

//THIS BIT RUNS.

$a = new \Auryn\Provider;
//this is kind of ugly, should be a better way to configure the logger.
$logger = new \Monolog\Logger('log');
$handle = new \Monolog\Handler\StreamHandler('php://stdout');
$handle->setFormatter(new MonologCliFormatter());
$logger->pushHandler($handle);
\Monolog\ErrorHandler::register($logger,array(),\Psr\Log\LogLevel::ERROR);
error_reporting(0);

$a->share($logger);

$logger->info('Logging is go');

$server = IoServer::factory(
		new HttpServer(
            new WsServer($a->make('\Ralphie\Sunshine\MessageServer'))
					),
        WEBSOCKET_PORT
    );

$server->run();
