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

$p = new \Pimple();

$p['log'] = $p->share(function ($p) {
		$logger = new \Monolog\Logger('log');
		$handle = new \Monolog\Handler\StreamHandler('php://stdout');
		$handle->setFormatter(new MonologCliFormatter());
		$logger->pushHandler($handle);
		
		\Monolog\ErrorHandler::register($logger,array(),\Psr\Log\LogLevel::ERROR);
		error_reporting(0);
		
		return $logger;
	});

$p['log']->info('Logging is go');

$p['ms'] = $p->share(function ($p) {
	return new MessageServer($p);
	});


$server = IoServer::factory(
		new HttpServer(
            new WsServer($p['ms'])
					),
        WEBSOCKET_PORT
    );

$server->run();
