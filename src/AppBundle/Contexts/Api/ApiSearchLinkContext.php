<?php
/**
 * Created by PhpStorm.
 * User: vladi
 * Date: 24.5.2017.
 * Time: 19.54
 */

namespace AppBundle\Contexts\Api;


use AppBundle\Model\LinkModel;
use AppBundle\Services\ApiPrepared;
use Doctrine\ORM\EntityManager;

class ApiSearchLinkContext extends LinkModel
{

    private $jsonResponse;
    private $em;

    public function __construct(
        EntityManager $entityManager,
        ApiPrepared $apiPrepared){
        $this->em           = $entityManager;
        $this->jsonResponse = $apiPrepared;
    }

    public function getBy(array $data){
        $criteria = array();
        if(isset($data['category'])) $criteria['category'] = $data['category'];
        $links =  $this->em->getRepository('AppBundle:Link')->findBy($criteria);
        $allLinksArray = array();
        foreach ($links as $link){
            $allLinksArray[] = parent::toArray($link);
        }
        return $this->jsonResponse->success($allLinksArray, 'Searched Links');
    }
}