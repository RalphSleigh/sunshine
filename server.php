#!/php -q
<?php  
namespace Ralphie\Sunshine;
use \stdClass;
 
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;

require 'vendor/autoload.php';
require "config.php";

//THIS BIT RUNS.

$a = new \Auryn\Injector;
//this is kind of ugly, should be a better way to configure the logger.
$logger = new \Monolog\Logger('log');
$handle = new \Monolog\Handler\StreamHandler('php://stdout');
$handle->setFormatter(new MonologCliFormatter());
$logger->pushHandler($handle);
\Monolog\ErrorHandler::register($logger,array(),\Psr\Log\LogLevel::ERROR);
error_reporting(0);

$a->share($logger);

$logger->info('Logging is go');

//make twitter
$twitter = new \Twitter(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, TWITTER_ACCESS_TOKEN, TWITTER_ACCESS_TOKEN_SECRET);
$a->share($twitter);


$a->share('Ralphie\Sunshine\MessageServer');
$ping = $a->make('Ralphie\Sunshine\PingHandler');


$loop = Factory::create();
$loop->addPeriodicTimer(SUNSHINE_PING_INTERVAL, array($ping, 'ping'));

$socket = new \React\Socket\Server($loop);
$socket->listen(SUNSHINE_WEBSOCKET_PORT, SUNSHINE_WEBSOCKET_HOST);

$server = new IoServer(
    new HttpServer(new WsServer($a->make('\Ralphie\Sunshine\MessageServer'))),
    $socket,
    $loop
);


$logger->Info('Looping!');
$loop->run();


/*

$server = IoServer::factory(
		new HttpServer(
            new WsServer($a->make('\Ralphie\Sunshine\MessageServer'))
					),
        WEBSOCKET_PORT
    );

	
	

	
//Loop the loop
$server->run();

*/
