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

class ApiAllCategoriesContext
{
    private $em;
    private $categoryModel;
    private $jsonRepsonse;

    public function __construct(EntityManager $entityManager, ApiPrepared $jsonResponse, CategoryModel $categoryModel){
        $this->em               = $entityManager;
        $this->categoryModel    = $categoryModel;
        $this->jsonRepsonse     = $jsonResponse;
    }

    public function getAllCategories(){
        return $this->categoryModel->getAllCategories();
    }

    public function getAllLinksResponse(){
        $allLinks = $this->getAllCategories();
        $allLinksArray = array();
        foreach ($allLinks as $link){
            $allLinksArray[] = $this->categoryModel->toArray($link);
        }
        return $this->jsonRepsonse->success($allLinksArray, 'All Categories', Response::HTTP_OK);
    }
}