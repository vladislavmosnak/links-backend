<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 5/30/17
 * Time: 1:01 PM
 */

namespace AppBundle\Contexts\Api;


use AppBundle\Model\CategoryModel;
use AppBundle\Model\InitModel;
use AppBundle\Model\LinkModel;
use AppBundle\Services\ApiPrepared;
use Doctrine\ORM\EntityManager;

class ApiInitContext
{

    private $em;
    private $initModel;
    private $linkModel;
    private $categoryModel;
    private $jsonRepsonse;

    public function __construct(
        EntityManager $entityManager,
        ApiPrepared $jsonResponse,
        InitModel $initModel,
        LinkModel $linkModel,
        CategoryModel $categoryModel
        ){
        $this->em           = $entityManager;
        $this->initModel    = $initModel;
        $this->jsonRepsonse = $jsonResponse;
        $this->linkModel    = $linkModel;
        $this->categoryModel= $categoryModel;
    }

    public function getInitData(){
        $links      = $this->linkModel->getAllLinks();
        $categories = $this->categoryModel->getAllCategories();
        return array($links, $categories);
    }

    public function getInitDataResponse(){
        list($links, $categories) = $this->getInitData();
        return $this->jsonRepsonse->success($this->initModel->toArray($links, $categories));
    }

}