<?php
namespace AppBundle\Contexts\Api;


use AppBundle\Exceptions\EntityDeletedException;
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
        return $this->linkModel->getSingleLink($id);
    }

    public function getSingleLinkResponse($id){
        try{
            $link = $this->getSingleLink($id);
        }catch (EntityDeletedException $e){
            return $this->jsonRepsonse->error(array('Link was deleted'), Response::HTTP_GONE);
        }

        if(!$link){
            return $this->jsonRepsonse->error(array('Link not found'), Response::HTTP_NOT_FOUND);
        }
        return $this->jsonRepsonse->success($this->linkModel->toArray($link));
    }
}