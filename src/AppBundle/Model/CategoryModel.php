<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 5/30/17
 * Time: 12:45 PM
 */

namespace AppBundle\Model;


use AppBundle\Entity\LinkCategory;

class CategoryModel
{

//    public function populateCategory(
//
//    }

    public function toArray(LinkCategory $linkCategory){
        return array(
            'id'            => $linkCategory->getId(),
            'categoryName'  => $linkCategory->getCategoryName()
        );
    }

}