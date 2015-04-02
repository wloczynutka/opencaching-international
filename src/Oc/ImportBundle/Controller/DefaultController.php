<?php

namespace Oc\ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Oc\ImportBundle\ImportFactory\Romania;

class DefaultController extends Controller
{

    static $originIdentifier = array(
//        1 => 'Germany', # http://www.opencaching.de OC
        2 => 'Poland', # http://www.opencaching.pl OP
//        3 => 'Czech', # http://www.opencaching.cz OZ
//        6 Opencaching Great Britain http://www.opencaching.org.uk OK
//        7 Opencaching Sweden http://www.opencaching.se OS =>OC Scandinavia
//        10 Opencaching United States http://www.opencaching.us OU
//        12 Opencaching Russia http://www.opencaching.org.ru  (I don't know current status???)
//        14 => 'Benelux', # http://www.opencaching.nl OB => OC Benelux
        16 => 'Romania', # http://www.opencaching.ro OR
    );

    public function importAction($ocToImport)
    {
        switch ($ocToImport){
            case 'romania':
                $importHandler = new Romania();
                $importHandler->setDoctrine($this->getDoctrine());
                break;
        }

        $importHandler->importDump();

        return $this->render('OcImportBundle:Default:import.html.twig', array('name' => 'tra la la'));
    }


}
