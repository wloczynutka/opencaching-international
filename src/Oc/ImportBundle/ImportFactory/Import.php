<?php

namespace Oc\ImportBundle\ImportFactory;

use Oc\CoreBundle\Entity\GeoCache;

class Import
{
    protected $ocNodeIdentifier = null;

    public function importDump()
    {
        $importFilesIndex = json_decode(file_get_contents(__DIR__.'/dumpsToImport/'.$this->ocNodeIdentifier.'/index.json'));
//        d($importFilesIndex);
        foreach ($importFilesIndex->data_files as $file) {
            $partToImport = json_decode(file_get_contents(__DIR__.'/dumpsToImport/'.$this->ocNodeIdentifier.'/'.$file));
			foreach ($partToImport as $importGeoCache) {
				$this->setGeocache($importGeoCache->data);
			}
            dd($partToImport);
        }

    }

	private function setGeocache($import) {
		$nId = $this->ocNodeIdentifier;
		$coordinates = explode('|', $import->location);


		$geoCache = new GeoCache();
		$geoCache->setCode($import->code)
				 ->setName($import->names->$nId)
				 ->setLatitude($coordinates[0])
				 ->setLongitude($coordinates[1])
				 ->setType($this->parseGeocacheType($import->type))
				 ->setStatus($this->parseGeocacheStatus($import->status))
				 ->setFoundCount($import->founds)
				 ->setNotFoundCount($import->notfounds);
		dd($import, $geoCache);
	}

	/**
	 * convert Okapi geocache type to our format
	 * @param type $okapiType
	 */
	public function parseGeocacheType($okapiType) {
		switch ($okapiType) {
			case 'Event':
				return GeoCache::TYPE_EVENT;

			default:
				dd('dodać typ', $okapiType);
		}
	}

	/**
	 * convert Okapi geocache type to our format
	 * @param type $okapiType
	 */
	public function parseGeocacheStatus($okapiStatus) {
		switch ($okapiStatus) {
			case 'Archived':
				return GeoCache::STATUS_ARCHIVED;

			default:
				dd('dodać status', $okapiStatus);
		}
	}

	/**
	 * convert Okapi geocache type to our format
	 * @param type $okapiType
	 */
	public function parseGeocacheSize($okapiSize) {
		switch ($okapiSize) {
			case 'Archived':
				return GeoCache::STATUS_ARCHIVED;

			default:
				dd('dodać status', $okapiStatus);
		}
	}
}