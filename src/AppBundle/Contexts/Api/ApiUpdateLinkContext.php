<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 6/16/17
 * Time: 2:14 PM
 */

namespace AppBundle\Contexts\Api;


use AppBundle\Model\CategoryModel;
use AppBundle\Model\LinkModel;
use AppBundle\Services\ApiPrepared;
use Symfony\Component\HttpFoundation\Response;

class ApiUpdateLinkContext{

    private $linkModel;
    private $jsonResponse;
    private $categoryModel;
    private $data = array(
        'id'            => null,
        'title'         => null,
        'description'   => null,
        'category'      => null
    );

    public function __construct(
        LinkModel $linkModel,
        ApiPrepared $apiPrepared,
        CategoryModel $categoryModel
    ){
        $this->linkModel    = $linkModel;
        $this->jsonResponse = $apiPrepared;
        $this->categoryModel= $categoryModel;
    }

    public function updateLink(){
        $link = $this->linkModel->getSingleLink($this->data['id']);
        return $this->linkModel->updateLink($this->updateLink());
    }

    public function updateLinkResponse(){
        $updatedLink = $this->updateLink();
        return $this->jsonRepsonse->success($this->linksModel->toArray($updatedLink), 'Link created', Response::HTTP_OK);
    }

    public function populateAndValidate($data){
        $errors = array();

        $notRequiredFromRequest = array('title', 'description', 'category');

        foreach ($this->data as $key => $val){
            if(!isset($data[$key])){
                if(!in_array($key, $notRequiredFromRequest)) $errors[] = 'Missing ' . $key;
            }
            else $this->data[$key] = $data[$key];
        }

        if(count($errors)){
            return $this->jsonResponse->error($errors, Response::HTTP_BAD_REQUEST);
        }

        if(isset($this->data['category']) && $this->data['category'] != null){
            $newLinkCategory = $this->categoryModel->getSingleCategory($this->data['category']);
            if(!$newLinkCategory){
                $errors[] = 'Category was deleted: ' . $data['category'];
                return $this->jsonResponse->error($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
            }else{
                $this->data['category'] = $newLinkCategory;
            }
        }

        return true;
    }

}