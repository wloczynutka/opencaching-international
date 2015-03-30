<?php

namespace Oc\ImportBundle\ImportFactory;

class Import
{
    protected $ocNodeIdentifier = null;

    public function importDump()
    {
        $importFilesIndex = json_decode(file_get_contents(__DIR__.'/dumpsToImport/'.$this->ocNodeIdentifier.'/index.json'));
//        d($importFilesIndex);
        foreach ($importFilesIndex->data_files as $file) {
            $partToImport = json_decode(file_get_contents(__DIR__.'/dumpsToImport/'.$this->ocNodeIdentifier.'/'.$file));
            dd($partToImport);
        }

    }
}