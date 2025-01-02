<?php 

// Start the timer
$_ENV['startTime'] = microtime(true); 

// Start a Session
if (!session_id()) {
  session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/Los_Angeles');

$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

use App\Utils\Template;

Template::setfolder($_ENV['BASE_DIR'] . '/app/Views/');

require_once 'helpers.php';
require_once 'routes.php';