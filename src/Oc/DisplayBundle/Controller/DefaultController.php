<?php

namespace Oc\DisplayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Oc\CoreBundle\Entity\GeoCache;

class DefaultController extends Controller
{
    public function userAction($uuid)
    {
        $templateVariables = array(
            'name' => $uuid,
        );
        return $this->render('DisplayBundle:Default:user.html.twig', $templateVariables);
    }
    public function geocacheAction($code)
    {
        /* @var $geoCache \Oc\CoreBundle\Entity\GeoCache */
        $geoCache = $this->getDoctrine()->getRepository('Oc\CoreBundle\Entity\GeoCache')->findOneByCode($code);
        if($geoCache){

            d($geoCache);
            $waypoints = $this->extractWaypoints($geoCache->getWaypoints());
            $templateVariables = array(
                'trexample' => $translated = $this->get('translator')->trans('Hello tra la la'),
                'code' => $code,
                'name' => $geoCache->getName(),
                'status' => $geoCache->getStatus(),
                'latitude' => $geoCache->getCoordinates()->getLatitudeString(),
                'longitude' => $geoCache->getCoordinates()->getLongitudeString(),
                'owner' => $this->extractShortUserInfo($geoCache->getOwner()),
                'descriptions' => $this->extractDescriptions($geoCache->getDescriptions()),
                'waypoints' => $waypoints['templateArray'],
                'javascriptVars' => array(
                    'mapCenter' => array(
                        'latitude' => $geoCache->getCoordinates()->getLatitude(),
                        'longitude' => $geoCache->getCoordinates()->getLongitude(),
                    ),
                    'waypoints' => $waypoints['jsArray'],
                )
            );
            d($templateVariables);
        }
        return $this->render('DisplayBundle:Default:geocache.html.twig', $templateVariables);
    }

    private function extractWaypoints(\Doctrine\ORM\PersistentCollection $waypoints){
        /* @var $waypoint \Oc\CoreBundle\Entity\GeoCacheWaypoint */
        $result = array(
            'templateArray' => array(), 'jsArray' => array()
        );
        foreach ($waypoints as $waypoint) {
            $result['jsArray'][] = array(
                'name' => $waypoint->getName(),
                'latitude' => $waypoint->getCoordinates()->getLatitude(),
                'longitude' => $waypoint->getCoordinates()->getLongitude(),
            );
            $result['templateArray'][] = array (
                'latitude' => $waypoint->getCoordinates()->getLatitudeString(),
                'longitude' => $waypoint->getCoordinates()->getLongitudeString(),
                'name' => $waypoint->getName(),
                'description' => $waypoint->getDescription(),
            );
        }
        return $result;
    }

    private function extractDescriptions(\Doctrine\ORM\PersistentCollection $descriptions)
    {
        /* @var $description \Oc\CoreBundle\Entity\GeoCacheDescription */
        foreach ($descriptions as $description) {
            $result[$description->getLanguage()] = array(
                'language' => $description->getLanguage(),
                'geocache' => $description->getTextGeocache(),
                'place' => $description->getText(),
                'hint' => $description->getHint(),
            );
        }
        return $result;
    }

    private function extractShortUserInfo(\Oc\CoreBundle\Entity\User $user){
        $user->__load();
        d($user);
        return array(
            'name' => $user->getUsername(),
            'uuid' => $user->getUuid(),
        );
    }
}
