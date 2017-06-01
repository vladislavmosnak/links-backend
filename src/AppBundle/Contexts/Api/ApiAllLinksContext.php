<?php
namespace AppBundle\Contexts\Api;


use AppBundle\Model\LinkModel;
use AppBundle\Services\ApiPrepared;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;

class ApiAllLinksContext
{
    private $em;
    private $linkModel;
    private $jsonRepsonse;

    public function __construct(EntityManager $entityManager, ApiPrepared $jsonResponse, LinkModel $linkModel){
        $this->em           = $entityManager;
        $this->linkModel    = $linkModel;
        $this->jsonRepsonse = $jsonResponse;
    }

    public function getAllLinks(){
        return $this->em->getRepository('AppBundle:Link')->findAll();
    }

    public function getAllLinksResponse(){
        $allLinks = $this->getAllLinks();
        $allLinksArray = array();
        foreach ($allLinks as $link){
            $allLinksArray[] = $this->linkModel->toArray($link);
        }
        return $this->jsonRepsonse->success($allLinksArray, 'All Links', Response::HTTP_OK);
    }
}