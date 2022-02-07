<?php

declare(strict_types=1);

require_once("vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required('LORIS_USERNAME', 'LORIS_PASSWORD', 'LORIS_API_URL')->notEmpty();

if (getenv('LORIS_JWT_TOKEN') === false) {
        echo("Token not found. Retrieving with given credentials.\n");
        get_loris_token();
} else {
        echo(getenv('LORIS_JWT_TOKEN') . "\n");
        echo("Token already exists.\n");
        echo("Here is your token: " . getenv('LORIS_JWT_TOKEN'));

}

get_project('loris');

function get_project($project_name) {
        $requestUri = $_ENV['LORIS_API_URL'] . "projects/" . $project_name;

        $ch = curl_init();
        curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $requestUri,
                CURLOPT_SSH_COMPRESSION => true,
                CURLOPT_HTTPHEADER => array('Accept: application/json',
                                            'Authorization: Bearer ' . getenv('LORIS_JWT_TOKEN')),
        ]);
        $result = curl_exec($ch);
        echo($result . "\n");
}

function get_projects() {
        $requestUri = $_ENV['LORIS_API_URL'] . "projects";

        $ch = curl_init();
        curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $requestUri,
                CURLOPT_SSH_COMPRESSION => true,
                CURLOPT_HTTPHEADER => array('Accept: application/json',
                                            'Authorization: Bearer ' . getenv('LORIS_JWT_TOKEN')),
        ]);
        $result = curl_exec($ch);
        echo($result . "\n");
}

function get_loris_token() {
        $requestUri = $_ENV['LORIS_API_URL'] . "login";
        $login = array('username' => $_ENV['LORIS_USERNAME'],
                'password' => $_ENV['LORIS_PASSWORD']);
        $login_json = json_encode($login);


        $ch = curl_init();
        curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $requestUri,
                CURLOPT_POST => true,
                CURLOPT_SSH_COMPRESSION => true,
                CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
                CURLOPT_POSTFIELDS => $login_json,
        ]);
        $result = curl_exec($ch);
        $token = json_decode($result);

        echo($token->token . "\n");

        putenv("LORIS_JWT_TOKEN=$token->token");

        curl_close($ch);
}