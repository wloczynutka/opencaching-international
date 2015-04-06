<?php

namespace Oc\ImportBundle\ImportFactory;

class Romania extends Import
{
    public function __construct()
    {
        $this->ocNodeIdentifier = 'ro';
        $this->ocNodeIdentifierInt = 16;
        $this->updateUrl = 'http://opencaching.ro/okapi/services/replicate/changelog?since=';
        $this->consumerKey = '';

    }

}