#!/php -q
<?php 
namespace Ralphie\Sunshine;
use \stdClass;

require 'vendor/autoload.php';
require "config.php";

//THIS BIT RUNS.

$p = new \Pimple();

$p['log'] = $p->share(function ($p) {
		$logger = new \Monolog\Logger('log');
		$handle = new \Monolog\Handler\StreamHandler('php://stdout');
		$handle->setFormatter(new MonologCliFormatter());
		$logger->pushHandler($handle);
		
		\Monolog\ErrorHandler::register($logger);
		error_reporting(0);
		
		return $logger;
	});

$p['log']->info('Logging is go');

$master = new WebSocket($p,WEBSOCKET_HOST,WEBSOCKET_PORT);
