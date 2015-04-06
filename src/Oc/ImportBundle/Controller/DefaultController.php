<?php

namespace Oc\ImportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Oc\ImportBundle\ImportFactory\Romania;
use Oc\ImportBundle\ImportFactory\Polska;

class DefaultController extends Controller
{

    static $originIdentifier = array(
//        1 => 'Germany', # http://www.opencaching.de OC
        2 => array(
            'name' => 'polska',
            'url' => 'http://www.opencaching.pl',
            'prefix' => 'OP',
            'okapiUrl' => 'http://opencaching.pl/okapi/services/',
        ),
//        3 => 'Czech', # http://www.opencaching.cz OZ
//        6 Opencaching Great Britain http://www.opencaching.org.uk OK
//        7 Opencaching Sweden http://www.opencaching.se OS =>OC Scandinavia
//        10 Opencaching United States http://www.opencaching.us OU
//        12 Opencaching Russia http://www.opencaching.org.ru  (I don't know current status???)
//        14 => 'Benelux', # http://www.opencaching.nl OB => OC Benelux
        16 => array(
            'name' => 'romania',
            'url' => 'http://www.opencaching.ro',
            'prefix' => 'OR',
            'okapiUrl' => 'http://opencaching.ro/okapi/services/',
        ),
    );

    public function importAction($ocToImport)
    {
        $id = $this->getRemoteSystemIdByName($ocToImport);
        $importHandler = $this->selectImportHandler($id);
        $result = $importHandler->importDump();

        return $this->render('OcImportBundle:Default:import.html.twig', array('name' => $result));
    }

    public function updateAction(){
        foreach ($this::$originIdentifier as $id => $remoteSystem) {
            $updateHandler = $this->selectImportHandler($id);
            $updateHandler->update();
        }

    }

    private function selectImportHandler($remoteSystemId){
        switch ($remoteSystemId){
            case 16:
                $handler = new Romania();
                break;
            case 2:
                $handler = new Polska();
                break;
            default:
                dd('problem', $remoteSystemId);
        }
        $remoteSystemCredentials = $this->container->getParameter('remote_systems_credentials');
        $handler->setDoctrine($this->getDoctrine());
        $handler->setRemoteSystemCredentials($remoteSystemCredentials[$remoteSystemId]);
        return $handler;
    }

    public function getRemoteSystemIdByName($name){
        foreach (self::$originIdentifier as $id => $originDetails) {
            if($originDetails['name'] === $name){
                return $id;
            }
        }
        return false;
    }

}
