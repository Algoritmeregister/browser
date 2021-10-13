<?php

namespace AlgorithmRegister;

class AlgorithmRegister
{
    private $_organizations;

    public function __construct($organizations)
    {
        $this->_organizations = $organizations;
    }

    public function getClient()
    {
        $organization = reset($this->_organizations); //FIXME this is a hack for single org instance
        return new $organization["client"]["class"]($organization["client"]["params"]["url"]);
    }

    public function getOrganizationName()
    {
        $organization = reset($this->_organizations); //FIXME this is a hack for single org instance
        return $organization["name"];
    }
}