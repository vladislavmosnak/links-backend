<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 5/30/17
 * Time: 12:43 PM
 */

namespace AppBundle\Contexts\Api;


use AppBundle\Model\CategoryModel;
use AppBundle\Services\ApiPrepared;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;

class ApiAllCategoriesContext extends CategoryModel
{
    private $em;
    private $jsonRepsonse;

    public function __construct(EntityManager $entityManager, ApiPrepared $jsonResponse){
        $this->em           = $entityManager;
        $this->jsonRepsonse = $jsonResponse;
    }

    public function getAllCategories(){
        return $this->em->getRepository('AppBundle:LinkCategory')->findAll();
    }

    public function getAllLinksResponse(){
        $allLinks = $this->getAllCategories();
        $allLinksArray = array();
        foreach ($allLinks as $link){
            $allLinksArray[] = parent::toArray($link);
        }
        return $this->jsonRepsonse->success($allLinksArray, 'All Links', Response::HTTP_OK);
    }
}