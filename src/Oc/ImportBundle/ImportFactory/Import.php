<?php

namespace Oc\ImportBundle\ImportFactory;

use Oc\CoreBundle\Entity\GeoCache;
use \Oc\CoreBundle\Entity\User;
use \Oc\CoreBundle\Entity\GeoCacheDescription;
use \Oc\CoreBundle\Entity\GeoCacheAttribute;
use \Oc\CoreBundle\Entity\Image;
use Symfony\Component\HttpFoundation\Response;

class Import
{
    protected $ocNodeIdentifier = null;

    protected $doctrine;

    /**
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     */
    public function setDoctrine($doctrine){
        $this->doctrine = $doctrine;
    }

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

        $geoCache = $this->doctrine->getRepository('Oc\CoreBundle\Entity\GeoCache')->findOneByCode($import->code);
        if(!$geoCache) { /*create new geoCache*/
            $entityManager = $this->doctrine->getManager();
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
                ;
            $this->addDescriptionsFromOkapi($geoCache, $import->descriptions, $import->hints, $entityManager);
            $this->addImagesFromOkapi($geoCache, $import->images, $entityManager);
            $this->addAttributesFromOkapi($geoCache, $import->attr_acodes, $entityManager);

            if(!empty($import->alt_wpts)){
                dd($import->alt_wpts);
            }
//            dd($import, $geoCache);

            $entityManager->persist($geoCache);
            $entityManager->flush();
            print 'Dodano'.$import->code.', ';
        } else {
            print 'ten kesz już jest w bazie:'.$import->code;
        }
	}

	/**
	 * convert Okapi geocache type to our format
	 * @param type $okapiType
	 */
	public function parseGeocacheType($okapiType) {
		switch ($okapiType) {
			case 'Event':
				return GeoCache::TYPE_EVENT;
            case 'Traditional':
                return GeoCache::TYPE_TRADITIONAL;
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
	public function parseGeocacheSize($import) {
		switch ($import->size2) {
			case 'none':
				return GeoCache::SIZE_NOTSPECIFIED;
            case 'regular':
                return GeoCache::SIZE_REGULAR;
            case 'micro':
                return GeoCache::SIZE_MICRO;
            case 'small':
                return GeoCache::SIZE_SMALL;
			default:
				dd('dodać rozmiar', $import->size2, $import->size);
		}
	}

    private function buildUser($owner, $entityManager){
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

    private function parseOkapiUserIdentifier($url){
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
}