<?php
/**
 * Created by PhpStorm.
 * User: vladi
 * Date: 21.5.2017.
 * Time: 18.37
 */

namespace AppBundle\Model;


use AppBundle\Entity\Link;

class LinkModel
{

    protected function toArray(Link $link){
        return array(
            'id'            => $link->getId(),
            'url'           => $link->getUrl(),
            'title'         => $link->getTitle(),
            'description'   => $link->getDescription(),
            'category'      => $link->getCategory()->getCategoryName()
        );
    }
}