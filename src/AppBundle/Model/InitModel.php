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

    public function toArray($links, $categories){
        $linkModel      = new LinkModel(); //TODO dependency
        $categoryModel  = new CategoryModel();
        $linksArray         = array();
        $categoriesArray    = array();
        foreach ($links as $link){
            $linksArray[] = $linkModel->toArray($link);
        }
        foreach ($categories as $category){
            $categoriesArray[] = $categoryModel->toArray($category);
        }
        return array(
            'links'         => $linksArray,
            'categories'    => $categoriesArray
        );
    }

}