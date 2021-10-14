<?php

namespace AlgorithmRegister;

class AlgorithmRegister
{
    private $_organizations;
    private $_clients;

    public function __construct($organizations)
    {
        $this->_organizations = $organizations;
        $this->_clients = [];
        foreach ($organizations as $organization) {
            $this->_clients[] = new $organization["client"]["class"]($organization["client"]["params"]["url"]);
        }
    }

    public function readApplications()
    {
        $items = [];
        foreach ($this->_clients as $client) {
            $rs = $client->readApplications();
            if (is_array($rs)) {
                $items = array_merge($items, $rs);
            }
        }
        return $items;
    }

    public function readApplication($id)
    {
        foreach ($this->_clients as $client) {
            $rs = $client->readApplication($id);
            if (!empty($rs)) {
                return $rs;
            }
        }
        return null;
    }

    public function readEvents($id)
    {
        foreach ($this->_clients as $client) {
            $rs = $client->readEvents($id);
            if (!empty($rs)) {
                return $rs;
            }
        }
        return null;
    }

    public function getOrganizationName()
    {
        if (count($this->_organizations) > 1) return "";
        $organization = reset($this->_organizations);
        return $organization["name"];
    }

    public function getOrganizationStyle()
    {
        if (count($this->_organizations) > 1) return "";
        $organization = reset($this->_organizations);
        return $organization["style"];
    }
}