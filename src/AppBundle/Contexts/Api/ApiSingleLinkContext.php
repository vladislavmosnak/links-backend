<?php
namespace AppBundle\Contexts\Api;


use AppBundle\Model\LinkModel;
use AppBundle\Services\ApiPrepared as jresponse;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;

class ApiSingleLinkContext
{
    private $em;
    private $linkModel;
    private $jsonRepsonse;

    public function __construct(EntityManager $entityManager, jresponse $jsonResponse, LinkModel $linkModel){
        $this->em           = $entityManager;
        $this->linkModel    = $linkModel;
        $this->jsonRepsonse = $jsonResponse;
    }

    public function getSingleLink($id){
        return $this->em->getRepository('AppBundle:Link')->find($id);
    }

    public function getSingleLinkResponse($id){
        $link = $this->getSingleLink($id);
        if(!$link){
            return $this->jsonRepsonse->error(array('Link not found'), Response::HTTP_NOT_FOUND);
        }
        return $this->jsonRepsonse->success($this->linkModel->toArray($link));
    }
}