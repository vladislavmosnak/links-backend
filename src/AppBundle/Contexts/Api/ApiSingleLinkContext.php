<?php
/**
 * Created by PhpStorm.
 * User: vladi
 * Date: 21.5.2017.
 * Time: 19.22
 */

namespace AppBundle\Contexts\Api;


use AppBundle\Model\LinkModel;
use AppBundle\Services\ApiPrepared as jresponse;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;

class ApiSingleLinkContext extends LinkModel
{
    private $em;
    private $jsonRepsonse;

    public function __construct(EntityManager $entityManager, jresponse $jsonResponse){
        $this->em           = $entityManager;
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
        return $this->jsonRepsonse->success(parent::toArray($link));
    }
}