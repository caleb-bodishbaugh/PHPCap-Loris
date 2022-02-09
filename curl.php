<?php

declare(strict_types=1);

require_once("vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required('LORIS_USERNAME', 'LORIS_PASSWORD', 'LORIS_API_URL')->notEmpty();

$loris = new \LorisAPI\LorisAPI($_ENV['LORIS_USERNAME'], $_ENV['LORIS_API_URL']);
$loris->set_password($_ENV['LORIS_PASSWORD']);
$loris->set_token();
echo($loris->get_candidates());
// get_loris_token();

/* if (getenv('LORIS_JWT_TOKEN') === false) {
        echo("Token not found. Retrieving with given credentials.\n");
        get_loris_token();
} else {
        echo(getenv('LORIS_JWT_TOKEN') . "\n");
        echo("Token already exists.\n");
        echo("Here is your token: " . getenv('LORIS_JWT_TOKEN'));
} */

// get_project('belg');
// get_candidates();
// get_candidate('862114');
/* post_candidate('loris', 
               '2022-02-06', 
               '2000-01-23', 
               'Female', 
               'Data Coordinating Center'); */
