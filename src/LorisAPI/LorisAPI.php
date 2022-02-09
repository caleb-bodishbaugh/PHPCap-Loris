<?php

namespace LorisAPI;

use Exception;

class LorisAPI {
    public $username;
    public $api_url;
    private $password;
    private $token;

    public function __construct($username, $api_url) {
        $this->username = $username;
        $this->api_url = $api_url;
    }

    public function set_password($pwd) {
        $this->password = $pwd;
    }

    public function set_token() {
        $request_uri = $this->api_url . "login";
        $login = array('username' => $this->username,
                       'password' => $this->password);
        $login_json = json_encode($login);

        $result = post_request($request_uri, $login_json, "placeholder");
        $token = json_decode($result);
        
        $this->token = $token->token;
    }

    public function get_candidate($CandID) {
        $request_uri = $this->api_url . "/candidates/$CandID";
        return get_request($request_uri, $this->token);
    }

    public function get_candidates() {
        $request_uri = $this->api_url . "/candidates";
        return get_request($request_uri, $this->token);
    }

    public function get_candidate_visit($CandID, $VisitLabel) { 
        $request_uri = $this->api_url . "/candidates/$CandID/$VisitLabel";
        return get_request($request_uri, $this->token);
    }

    public function get_projects() {
        $request_uri = $this->api_url . "/projects";
        return get_request($request_uri, $this->token);
    }

    public function get_project($project_name) {
        $request_uri = $this->api_url . "/projects/$project_name";
        return get_request($request_uri, $this->token);
    }

    public function get_project_instrument($project_name, $instrument_name) {
        $request_uri = $this->api_url . "/projects/$project_name/instruments/$instrument_name";
        return get_request($request_uri, $this->token);
    }

    public function get_project_field($project_name, $field_name) {
        $request_uri = $this->api_url . "/projects/$project_name/$field_name";
        return get_request($request_uri, $this->token);
    }

    public function post_candidate($Project, $EDC, $DoB, $Sex, $Site) {
        $request_uri = $this->api_url . "/candidates";
        $candidate_info = array('Project' => $Project,
                                'EDC'     => $EDC,
                                'DoB'     => $DoB,
                                'Sex'     => $Sex,
                                'Site'    => $Site);
        $candidate = array('Candidate' => $candidate_info);
        $candidate_json = json_encode($candidate);

        return post_request($request_uri, $candidate_json, $this->token);
    }

    public function put_candidate_visitlabel($CandID, $VisitLabel, $Site, $Battery, $Project) {
        $request_uri = $this->api_url . "/candidates/$CandID/$VisitLabel";
        $meta_info = array('CandID'  => $CandID,
                           'Visit'   => $VisitLabel,
                           'Site'    => $Site,
                           'Battery' => $Battery,
                           'Project' => $Project);
        $meta = array('Meta' => $meta_info);
        $meta_json = json_encode($meta);

        return put_request($request_uri, $meta_json, $this->token);
    }

    public function patch_candidate_visitlabel($CandID, $VisitLabel, $Site, $Battery, $Project, $Date, $Status) {
        $request_uri = $this->api_url . "/candidates/$CandID/$VisitLabel";
        $visit_info = array('Date'   => $Date,
                            'Status' => $Status);
        $visit = array('Visit' => $visit_info);
        $patch_info = array('CandID' => $CandID,
                            'Visit'   => $VisitLabel,
                            'Site'    => $Site,
                            'Battery' => $Battery,
                            'Project' => $Project,
                            'Stages'  => $visit);
        $patch_json = json_encode($patch_info);

        return patch_request($request_uri, $patch_json, $this->token);
    }
}

function get_request($request_uri, $token) {
    try {
        $ch = curl_init();
        curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => $request_uri,
                CURLOPT_SSH_COMPRESSION => true,
                CURLOPT_HTTPHEADER => array('Accept: application/json',
                                            'Authorization: Bearer ' . $token,)
        ]);
        $result = curl_exec($ch);
        echo($result . "\n");
        return $result;
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}

function post_request($request_uri, $post_data, $token) {
    try {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $request_uri,
            CURLOPT_SSH_COMPRESSION => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json',
                                        'Authorization: Bearer ' . $token),
            CURLOPT_POSTFIELDS => $post_data,
        ]);
        $result = curl_exec($ch);
        echo($result . "\n");
        return $result;
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}

function put_request($request_uri, $put_data, $token) {
    try {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $request_uri,
            CURLOPT_SSH_COMPRESSION => true,
            CURLOPT_PUT => true,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json',
                                        'Authorization: Bearer ' . $token),
            CURLOPT_POSTFIELDS => $put_data,
        ]);
        $result = curl_exec($ch);
        echo($result . "\n");
        return $result;
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}

function patch_request($request_uri, $patch_data, $token) {
    try {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $request_uri,
            CURLOPT_SSH_COMPRESSION => true,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_HTTPHEADER => array('Content-Type: application/json',
                                        'Authorization: Bearer ' . $token),
            CURLOPT_POSTFIELDS => $patch_data,
        ]);
        $result = curl_exec($ch);
        echo($result . "\n");
        return $result;
    } catch (Exception $ex) {
        echo $ex->getMessage();
    }
}