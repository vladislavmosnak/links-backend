<?php
/**
 * Created by PhpStorm.
 * User: vladi
 * Date: 21.5.2017.
 * Time: 18.40
 */

namespace AppBundle\Contexts\Api;


use AppBundle\Model\LinkModel;
use AppBundle\Services\ApiPrepared;
use Doctrine\ORM\EntityManager;

class ApiAllLinksContext extends LinkModel
{
    private $em;
    private $jsonRepsonse;

    public function __construct(EntityManager $entityManager, ApiPrepared $jsonResponse){
        $this->em           = $entityManager;
        $this->jsonRepsonse = $jsonResponse;
    }

    public function getAllLinks(){
        return $this->em->getRepository('AppBundle:Link')->findAll();
    }

    public function getAllLinksResponse(){
        $allLinks = $this->getAllLinks();
        $allLinksArray = array();
        foreach ($allLinks as $link){
            $allLinksArray[] = parent::toArray($link);
        }
        return $this->jsonRepsonse->success($allLinksArray, 'All Links');
    }
}