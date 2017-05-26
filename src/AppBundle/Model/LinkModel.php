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
        $image,
        $author
    ){

        if(!$title)         $title                  = 'Default title';
        if(!$description)   $description            = 'Default description';
        if(!$image)         $image                  = 'Default image';
        if(!$author)        $author                 = 'Unknown author';

        $newLink = new Link();
        $newLink->setDescription($description);
        $newLink->setTitle($title);
        $newLink->setUrl($url);
        $newLink->setCategory($category);
        $newLink->setImage($image);
        $newLink->setAuthor($author);
        return $newLink;
    }

    protected function toArray(Link $link){
        return array(
            'id'            => $link->getId(),
            'url'           => $link->getUrl(),
            'title'         => $link->getTitle(),
            'description'   => $link->getDescription(),
            'image'         => $link->getImage(),
            'author'        => $link->getAuthor(),
            'category'      => array(
                'id'    => $link->getCategory()->getId(),
                'name'  => $link->getCategory()->getCategoryName()
            )
        );
    }
}