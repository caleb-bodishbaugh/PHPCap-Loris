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

get_project('belg');
// get_candidates();
get_candidate('862114');
/* post_candidate('loris', 
               '2022-02-06', 
               '2000-01-23', 
               'Female', 
               'Data Coordinating Center'); */

function get_candidate_visit($CandID, $VisitLabel) {
        try {
                $requestUri = $_ENV['LORIS_API_URL'] . "candidates/" . $CandID . "/" . $VisitLabel;

                $ch = curl_init();
                curl_setopt_array($ch, [
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_URL => $requestUri,
                        CURLOPT_SSH_COMPRESSION => true,
                        CURLOPT_HTTPHEADER => array('Accept: application/json',
                                                    'Authorization: Bearer ' . getenv('LORIS_JWT_TOKEN'))
                ]);
                $result = curl_exec($ch);
                echo($result . "\n");
                return $result;
        } catch (Exception $ex) {
                echo $ex->getMessage();
        }   
}

function get_candidate($CandID) {
        try {
                $requestUri = $_ENV['LORIS_API_URL'] . "candidates/" . $CandID;

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
                return $result;
        } catch (Exception $ex) {
                echo $ex->getMessage();
        }
}

function get_candidates() {
        try {
                $requestUri = $_ENV['LORIS_API_URL'] . "candidates";

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
                return $result;
        } catch (Exception $ex) {
                echo $ex->getMessage();
        }
}

function get_project($project_name) {
        try {
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
                return $result;
        } catch (Exception $ex) {
                echo $ex->getMessage();
        }
}

function get_projects() {
        try {
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
                return $result;
        } catch (Exception $ex) {
                echo $ex->getMessage();
        }
}

function get_loris_token() {
        try {
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
                return $result;
        } catch (Exception $ex) {
                echo $ex->getMessage();
        }
}

function post_candidate($Project, $EDC, $DoB, $Sex, $Site) {
        try {
                $requestUri = $_ENV['LORIS_API_URL'] . "candidates";

                $candidate_info = array('Project' => $Project,
                                        'EDC'     => $EDC,
                                        'DoB'     => $DoB,
                                        'Sex'     => $Sex,
                                        'Site'    => $Site);
                $candidate = array('Candidate' => $candidate_info);

                $candidate_json = json_encode($candidate);

                $ch = curl_init();
                curl_setopt_array($ch, [
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_URL => $requestUri,
                        CURLOPT_SSH_COMPRESSION => true,
                        CURLOPT_POST => true,
                        CURLOPT_HTTPHEADER => array('Content-Type: application/json',
                                                'Authorization: Bearer ' . getenv('LORIS_JWT_TOKEN')),
                        CURLOPT_POSTFIELDS => $candidate_json,
                ]);
                $result = curl_exec($ch);
                echo($result . "\n");
                return $result;
        } catch (Exception $ex) {
                echo $ex->getMessage();
        }
}