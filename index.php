<?php
$loader = require_once __DIR__ . "/./vendor/autoload.php";

date_default_timezone_set( 'Europe/Berlin' );

use Symfony\Component\Console\Application;
use William\SevencooksTestTask\ListStarshipDataCommand;

$console = new Application();
$console->add(new ListStarshipDataCommand());
try {
    $console->run();
} catch (Exception $exception) {
    echo $exception->getMessage();
}