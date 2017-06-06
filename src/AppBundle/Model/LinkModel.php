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
use Doctrine\ORM\EntityManager;

class LinkModel
{

    private $em;
    private $linkTagsModel;

    public function __construct(EntityManager $entityManager, LinkTagsModel $linkTagsModel){
        $this->em               = $entityManager;
        $this->linkTagsModel    = $linkTagsModel;
    }

    public function saveLink(
        $title,
        $description,
        $url,
        LinkCategory $category,
        $image,
        $author,
        $linkTags = array()
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
        $newLink->setCreatedAt(new \DateTime('now', new \DateTimeZone('UTC')));

        $this->em->persist($newLink);

        if(count($linkTags) > 0){
            foreach ($linkTags as $linkTag){
                $newLinkTag = $this->linkTagsModel->saveLinkTag($linkTag, $newLink);
                $newLink->setLinkTag($newLinkTag);
            }
        }

        $this->em->flush();

        return $newLink;
    }

    public function toArray(Link $link){
        $tags = $link->getLinkTag();
        $tagsArray = array();
        if($tags){
            foreach ($tags as $tag){
                $tagsArray[] = $this->linkTagsModel->toArray($tag);
            }
        }
        return array(
            'id'            => $link->getId(),
            'url'           => $link->getUrl(),
            'title'         => $link->getTitle(),
            'description'   => $link->getDescription(),
            'image'         => $link->getImage(),
            'created_at'    => $link->getCreatedAt(),
            'author'        => $link->getAuthor(),
            'category'      => array(
                'id'    => $link->getCategory()->getId(),
                'name'  => $link->getCategory()->getCategoryName()
            ),
            'tags'      => $tagsArray
        );
    }
}