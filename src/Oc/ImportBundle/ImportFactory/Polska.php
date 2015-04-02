<?php

namespace Oc\ImportBundle\ImportFactory;

class Polska extends Import
{
    public function __construct()
    {
        $this->ocNodeIdentifier = 'pl';
        $this->ocNodeIdentifierInt = 2;
    }

}