<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 5/30/17
 * Time: 1:01 PM
 */

namespace AppBundle\Contexts\Api;


use AppBundle\Model\InitModel;
use AppBundle\Services\ApiPrepared;
use Doctrine\ORM\EntityManager;

class ApiInitContext
{

    private $em;
    private $initModel;
    private $jsonRepsonse;

    public function __construct(EntityManager $entityManager, ApiPrepared $jsonResponse, InitModel $initModel){
        $this->em           = $entityManager;
        $this->initModel    = $initModel;
        $this->jsonRepsonse = $jsonResponse;
    }

    public function getInitData(){
        $links      = $this->em->getRepository('AppBundle:Link')->findAll();
        $categories = $this->em->getRepository('AppBundle:LinkCategory')->findAll();
        return array($links, $categories);
    }

    public function getInitDataResponse(){
        list($links, $categories) = $this->getInitData();
        return $this->jsonRepsonse->success($this->initModel->toArray($links, $categories));
    }

}