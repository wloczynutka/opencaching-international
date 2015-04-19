<?php

namespace Oc\DisplayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Oc\CoreBundle\Entity\GeoCache;

class SearchController extends Controller
{

    public function findNearestGeocacheAction($latitude, $longitude, $radius)
    {

//        https://developers.google.com/maps/articles/phpsqlsearch_v3#findnearsql

        d($latitude, $longitude, $radius);


        $query = 'SELECT id, code, name, latitude, longitude, status, ( 3959 * acos( cos( radians(:latitude) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(:longitude) ) + sin( radians(:latitude) ) * sin( radians( latitude ) ) ) ) AS distance FROM geocaches WHERE status IN(:statusList) HAVING distance < :radius  ORDER BY distance  LIMIT 0 , 100';

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($query);
        $stmt->bindValue(':radius', $radius);
        $stmt->bindValue(':latitude', $latitude);
        $stmt->bindValue(':longitude', $longitude);
        $stmt->bindValue(':statusList', '1,2');
        $stmt->execute();
        $geoCaches = $stmt->fetchAll();
        $templateVariables = array(
            'latitude' => $latitude,
            'longitude' => $longitude,
            'radius' => $radius,
            'geoCaches' => $geoCaches,
        );


        return $this->render('DisplayBundle:Default:geocache_list.html.twig', $templateVariables);
    }


}
