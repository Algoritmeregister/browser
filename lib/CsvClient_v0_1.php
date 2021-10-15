<?php

namespace AlgorithmRegister;

class CsvClient_v0_1
{
    private $_apiUrl;

    public function __construct($apiUrl)
    {
        $this->_apiUrl = $apiUrl;
    }

    public function readApplications()
    {
        $csvFile = file($this->_apiUrl);
        $tmp = explode(",",array_shift($csvFile));
        $headers = ["name", "status", "type", "contact", "organization", "department", "revision_date", "id", "uri", "category"];
        $applications = array_map('str_getcsv', $csvFile);
        $applications = array_map(function ($application) use ($headers) {
            return array_combine($headers, $application);
        }, $applications);
        return $applications;
    }

    public function createApplication($data)
    {
        throw new \Exception("operation not supported");
    }

    public function readApplication($id)
    {
        $applications = $this->readApplications();
        foreach ($applications as $application) {
            if ($application["id"] === $id) return $application;
        }
    }

    public function updateApplication($id, $data, $token)
    {
        throw new \Exception("operation not supported");
    }

    public function deleteApplication($id, $token)
    {
        throw new \Exception("operation not supported");
    }

    public function readEvents($id)
    {
        throw new \Exception("operation not supported");
    }
}