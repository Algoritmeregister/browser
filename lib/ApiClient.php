<?php

namespace AlgorithmRegister;

class ApiClient
{
    private $_apiUrl;

    public function __construct($apiUrl)
    {
        $this->_apiUrl = $apiUrl;
    }

    public function readApplications()
    {
        $rs = json_decode(file_get_contents($this->_apiUrl . "/applications"), true);
        $applications = $rs["_embedded"]["applications"];
        return $applications;
    }

    public function createApplication($data)
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($data)
            ]
        ]);
        return json_decode(file_get_contents($this->_apiUrl . "/applications", false, $context), true);
    }

    public function readApplication($id)
    {
        return json_decode(file_get_contents($this->_apiUrl . "/applications/{$id}"), true);
    }

    public function updateApplication($id, $data, $token)
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'PUT',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($data)
            ]
        ]);
        return json_decode(file_get_contents($this->_apiUrl . "/applications/{$id}?token={$token}", false, $context), true);
    }

    public function deleteApplication($id, $token)
    {
        $context = stream_context_create([
            'http' => [
                'method' => 'DELETE'
            ]
        ]);
        return json_decode(file_get_contents($this->_apiUrl . "/applications/{$id}?token={$token}", false, $context), true);
    }

    public function readEvents($id)
    {
        $rs = json_decode(file_get_contents($this->_apiUrl . "/events/{$id}"), true);
        $events = $rs["_embedded"]["events"];
        return $events;
    }
}