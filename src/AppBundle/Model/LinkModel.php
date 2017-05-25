<?php
/**
 * Created by PhpStorm.
 * User: vladi
 * Date: 21.5.2017.
 * Time: 18.37
 */

namespace AppBundle\Model;


use AppBundle\Entity\Link;
use AppBundle\Entity\LinkCategory;

class LinkModel
{

    public function populateLink(
        $title,
        $description,
        $url,
        LinkCategory $category,
        $image
    ){
        $newLink = new Link();
        $newLink->setDescription($description);
        $newLink->setTitle($title);
        $newLink->setUrl($url);
        $newLink->setCategory($category);
        $newLink->setImage($image);
        return $newLink;
    }

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