<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 5/30/17
 * Time: 1:03 PM
 */

namespace AppBundle\Model;


class InitModel
{

    private $linkModel;
    private $categoryModel;

    public function __construct(LinkModel $linkModel, CategoryModel $categoryModel){
        $this->linkModel        = $linkModel;
        $this->categoryModel    = $categoryModel;
    }

    public function toArray($links, $categories){
        $linksArray         = array();
        $categoriesArray    = array();
        foreach ($links as $link){
            $linksArray[] = $this->linkModel->toArray($link);
        }
        foreach ($categories as $category){
            $categoriesArray[] = $this->categoryModel->toArray($category);
        }
        return array(
            'links'         => $linksArray,
            'categories'    => $categoriesArray
        );
    }

}