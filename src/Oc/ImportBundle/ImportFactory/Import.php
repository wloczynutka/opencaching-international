<?php

namespace Oc\ImportBundle\ImportFactory;

use Oc\CoreBundle\Entity\GeoCache;
use Oc\CoreBundle\Entity\GeoCacheWaypointType;
use \Oc\CoreBundle\Entity\User;
use \Oc\CoreBundle\Entity\GeoCacheDescription;
use \Oc\CoreBundle\Entity\GeoCacheAttribute;
use \Oc\CoreBundle\Entity\Image;
use \Oc\CoreBundle\Entity\GeoCacheWaypoint;
use \Oc\CoreBundle\Entity\GeoCacheLog;
use Symfony\Component\HttpFoundation\Response;

class Import
{
    protected $ocNodeIdentifier = null;
    protected $ocNodeIdentifierInt;

    protected $doctrine;

    private $temp;
    private $result;



    /**
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     */
    public function setDoctrine($doctrine)
	{
        $this->doctrine = $doctrine;
    }

    public function importDump()
    {
        ini_set('max_execution_time', 600);
		$importFilesIndex = json_decode(file_get_contents(__DIR__.'/dumpsToImport/'.$this->ocNodeIdentifier.'/index.json'));
        $importedFilename = __DIR__.'/dumpsToImport/'.$this->ocNodeIdentifier.'/imported.json';
        if(file_exists($importedFilename)) {
            $importedStock = json_decode(file_get_contents($importedFilename));
        } else {
            $importedStock = array();
        }

        d($importFilesIndex, $importedStock);
        foreach ($importFilesIndex->data_files as $file) {
            if(!in_array($file, $importedStock)) {
                d($file);
                $partToImport = json_decode(file_get_contents(__DIR__ . '/dumpsToImport/' . $this->ocNodeIdentifier . '/' . $file));
                $this->importObjects($partToImport);
                $importedStock[] = $file;
                file_put_contents($importedFilename, json_encode($importedStock));
            }
        }
        d($this->result);
        return true;
    }

	private function importObjects($partToImport)
	{
		foreach ($partToImport as $object) {
			if($object->object_type == 'geocache'){
				$this->importGeocache($object->data);
			} elseif($object->object_type == 'log') {
				$this->importLog($object);
			} else {
				dd('TODO: nieznany typ obiektu??');
			}
		}
	}

	/**
	 * import or update geocache logs from okapi
	 * @param stdClass $data
	 */
	private function importLog($data)
	{
		$entityManager = $this->doctrine->getManager();
		$geoCache = $this->doctrine->getRepository('Oc\CoreBundle\Entity\GeoCache')->findOneByCode($data->data->cache_code);
		if(!$geoCache){
			dd('barak geoCache', $data);
		}
		$geoCacheLog = $this->doctrine->getRepository('Oc\CoreBundle\Entity\GeoCacheLog')->findOneByUuid($data->data->uuid);
		if(!$geoCacheLog){
			$geoCacheLog = new GeoCacheLog();
		}
		$geoCacheLog
				->setUuid($data->data->uuid)
				->setType($this->parseOkapiLogType($data->data->type))
				->setDateTime(new \DateTime($data->data->date))
				->setRecommendation($data->data->was_recommended)
				->setText($data->data->comment)
				->setGeoCache($geoCache)
				->setAuthor($this->buildUser($data->data->user, $entityManager))
		;
		$entityManager->persist($geoCacheLog);
		$entityManager->flush();
        $this->result['log']['updated'][] = $data->data->uuid;
	}

	private function parseOkapiLogType($okapiLogtype)
	{
		switch ($okapiLogtype) {
			case 'Found it':
				return GeoCacheLog::LOGTYPE_FOUNDIT;
			case 'Comment':
				return GeoCacheLog::LOGTYPE_COMMENT;
			case 'Archived':
				return GeoCacheLog::LOGTYPE_ARCHIVED;
			case 'Temporarily unavailable':
				return GeoCacheLog::LOGTYPE_DEACTIVATED;
			case 'Maintenance performed':
				return GeoCacheLog::LOGTYPE_SERVICED;
			case 'Attended':
				return GeoCacheLog::LOGTYPE_ATTENDED;
			case 'Ready to search':
				return GeoCacheLog::LOGTYPE_ACTIVATED;
			case 'Will attend':
				return GeoCacheLog::LOGTYPE_WILLATTEND;
			default:
				dd('TODO: dodac brakujacy typ logu', $okapiLogtype);
		}
	}

	private function importGeocache($import)
	{
		$nId = $this->ocNodeIdentifier;
		if(!isset($import->location)){
			dd($import);
		}
		$coordinates = explode('|', $import->location);

        $entityManager = $this->doctrine->getManager();
        $geoCache = $this->doctrine->getRepository('Oc\CoreBundle\Entity\GeoCache')->findOneByCode($import->code);
        if(!$geoCache) { /*create new geoCache*/
            $geoCache = new GeoCache();
            $geoCache->setCode($import->code)
                ->setName($import->names->$nId)
                ->setLatitude($coordinates[0])
                ->setLongitude($coordinates[1])
                ->setType($this->parseGeocacheType($import->type))
                ->setStatus($this->parseGeocacheStatus($import->status))
                ->setFoundCount($import->founds)
                ->setNotFoundCount($import->notfounds)
                ->setWillattendCount($import->willattends)
                ->setSize($this->parseGeocacheSize($import))
                ->setDifficulty($import->difficulty)
                ->setTerrain($import->terrain)
                ->setRating((float) $import->rating)
                ->setRatingVotesCount($import->rating_votes)
                ->setRecommendations($import->recommendations)
                ->setLastFound(new \DateTime($import->last_found))
                ->setDateModified(new \DateTime($import->last_modified))
                ->setDateCreated(new \DateTime($import->date_created))
                ->setDatePlaced(new \DateTime($import->date_hidden))
                ->setOwner($this->buildUser($import->owner, $entityManager))
                ->setUrl($import->url)
                ->setCountry($import->country)
                ->setState($import->state)
                ->setSource($this->ocNodeIdentifierInt)
                ;
            $this->addDescriptionsFromOkapi($geoCache, $import->descriptions, $import->hints, $entityManager);
            $this->addImagesFromOkapi($geoCache, $import->images, $entityManager);
            $this->addAttributesFromOkapi($geoCache, $import->attr_acodes, $entityManager);
            $this->addWaypointsFromOkapi($geoCache, $import->alt_wpts, $entityManager);

//            dd($import, $geoCache);

            $entityManager->persist($geoCache);
            $entityManager->flush();
            $this->result['geocache']['added'][] = $import->code;
        } else {
            $this->result['geocache']['inDB'] = $import->code;
        }
	}

	/**
	 * convert Okapi geocache type to our format
	 * @param type $okapiType
	 */
	public function parseGeocacheType($okapiType)
	{
		switch ($okapiType) {
			case 'Event':
				return GeoCache::TYPE_EVENT;
            case 'Traditional':
                return GeoCache::TYPE_TRADITIONAL;
			case 'Other':
				return GeoCache::TYPE_OTHERTYPE;
			case 'Quiz':
				return GeoCache::TYPE_QUIZ;
			case 'Multi':
				return GeoCache::TYPE_MULTICACHE;
            case 'Virtual':
				return GeoCache::TYPE_VIRTUAL;
            case 'Webcam':
				return GeoCache::TYPE_WEBCAM;
            case 'Own':
				return GeoCache::TYPE_OWNCACHE;
            case 'Moving':
				return GeoCache::TYPE_MOVING;
			default:
				dd('dodać typ', $okapiType);
		}
	}

	/**
	 * convert Okapi geocache type to our format
	 * @param type $okapiType
	 */
	public function parseGeocacheStatus($okapiStatus)
	{
		switch ($okapiStatus) {
			case 'Archived':
				return GeoCache::STATUS_ARCHIVED;
            case 'Available':
                return GeoCache::STATUS_READY;
            case 'Temporarily unavailable':
                return GeoCache::STATUS_UNAVAILABLE;
			default:
				dd('dodać status', $okapiStatus);
		}
	}

	/**
	 * convert Okapi geocache type to our format
	 * @param type $okapiType
	 */
	public function parseGeocacheSize($import)
	{
		switch ($import->size2) {
			case 'none':
				return GeoCache::SIZE_NOTSPECIFIED;
            case 'regular':
                return GeoCache::SIZE_REGULAR;
            case 'micro':
                return GeoCache::SIZE_MICRO;
            case 'small':
                return GeoCache::SIZE_SMALL;
            case 'large':
                return GeoCache::SIZE_LARGE;
            case 'xlarge':
                return GeoCache::SIZE_XXL;
			default:
				dd('dodać rozmiar', $import->size2, $import->size);
		}
	}

    private function buildUser($owner, $entityManager)
	{
		if($owner->uuid == "ZZZZZZZZ-ZZZZ-ZZZZ-ZZZZ-ZZZZZZZZZZZZ"){ /*opencaching system user uuid is invalid. Replace witch hardcoded valid one*/
			$owner->uuid = '7a2fdea1-2294-4c3d-9845-9c3aa1947842';
		}
		$user = $this->doctrine->getRepository('Oc\CoreBundle\Entity\User')->findOneByUuid($owner->uuid);
        if(!$user){
            $user = new User();
            $user->setUuid($owner->uuid)
                 ->setUsername($owner->username)
                 ->setUrl($owner->profile_url)
                 ->setOriginIdentifier($this->parseOkapiUserIdentifier($owner->profile_url))
                 ->setEmail('unknown@'.$owner->uuid)
                 ->setPassword('unknown')
            ;
            $entityManager->persist($user);
        }
        return $user;
    }

    private function parseOkapiUserIdentifier($url)
	{
        $explode = explode('userid=', $url);
        return (int) $explode[1];
    }

    private function addDescriptionsFromOkapi(GeoCache $geoCache, $descriptions, $hint, $entityManager)
    {
        foreach ($descriptions as $lang => $description) {
            $geoCacheDescription = new GeoCacheDescription();
            $geoCacheDescription
                ->setLanguage($lang)
                ->setText($description)
                ->setGeoCacheId($geoCache);
            if(isset($hint->$lang)){
                $geoCacheDescription->setHint($hint->$lang);
            }
            $geoCache->addDescription($geoCacheDescription);
            $entityManager->persist($geoCacheDescription);
        }
    }

    private function addImagesFromOkapi(GeoCache $geoCache, $images, $entityManager)
    {
        if(!empty($images)){
            foreach ($images as $image) {
                $imgObj = new Image();
                $imgObj
                    ->setUuid($image->uuid)
                    ->setUrl($image->url)
                    ->setCaption($image->caption)
                    ->setIsSpoiler($image->is_spoiler)
                    ->setThumbUrl($image->thumb_url)
                    ->setGeoCacheId($geoCache);
            }
            $geoCache->addImage($imgObj);
            $entityManager->persist($imgObj);
        }
    }

    private function addAttributesFromOkapi(GeoCache $geoCache, $attributes, $entityManager)
    {
        if(!empty($attributes)){
            foreach ($attributes as $attribute) {
                $geoCacheAttribute = new GeoCacheAttribute();
                $geoCacheAttribute->setIdentifier($attribute)->setGeoCacheId($geoCache);
                $geoCache->addAtttribute($geoCacheAttribute);
                $entityManager->persist($geoCacheAttribute);
            }
        }
    }

    private function addWaypointsFromOkapi(GeoCache $geoCache, $waypoints, $entityManager)
    {
        if(!empty($waypoints)){
            foreach ($waypoints as $waypoint) {
                $coordinates = explode('|', $waypoint->location);
                $geoCacheWaypoint = new GeoCacheWaypoint();
                $geoCacheWaypoint
                    ->setName($waypoint->name)
                    ->setLatitude($coordinates[0])
                    ->setLongitude($coordinates[1])
                    ->setType($this->buildGeocacheWaypointTypeFromOkapi($waypoint, $entityManager))
                    ->setDescription($waypoint->description)
                    ->setGeoCache($geoCache);
                $geoCache->addWaypoint($geoCacheWaypoint);
                $entityManager->persist($geoCacheWaypoint);
            }
        }
    }

    private function buildGeocacheWaypointTypeFromOkapi($waypoint, $entityManager){
        if(isset($this->temp['addedGeoCacheWaypointType'][$waypoint->type])){
            $geoCacheWaypointType = $this->temp['addedGeoCacheWaypointType'][$waypoint->type];
        } else {
            $geoCacheWaypointType = $this->doctrine->getRepository('Oc\CoreBundle\Entity\GeoCacheWaypointType')->findOneByTypeShort($waypoint->type);
        }
        if(!$geoCacheWaypointType){
            $geoCacheWaypointType = new GeoCacheWaypointType();
            $geoCacheWaypointType
                ->setName($waypoint->type_name)
                ->setSymbol($waypoint->sym)
                ->setTypeShort($waypoint->type);
            $entityManager->persist($geoCacheWaypointType);
            $this->temp['addedGeoCacheWaypointType'][$waypoint->type] = $geoCacheWaypointType;
        }
        return $geoCacheWaypointType;
    }
}