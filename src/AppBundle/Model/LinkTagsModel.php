<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 5/31/17
 * Time: 2:56 PM
 */

namespace AppBundle\Model;


use AppBundle\Entity\Link;
use AppBundle\Entity\LinkTags;
use Doctrine\ORM\EntityManager;

class LinkTagsModel
{

    private $em;
    private $repository;

    public function __construct(EntityManager $entityManager){
        $this->em = $entityManager;
        $this->repository = $this->em->getRepository('AppBundle:LinkTags');
    }

    public function getRepository(){
        return $this->repository;
    }

    public function saveLinkTag(
        $tagName,
        Link $link
    ){

        $newLinkTag = new LinkTags();
        $newLinkTag->setLink($link);
        $newLinkTag->setTagName($tagName);

        $this->em->persist($newLinkTag);
        $this->em->flush();

        return $newLinkTag;
    }

    public function toArray(LinkTags $linkTag){
        return array(
            'id'            => $linkTag->getId(),
            'tagName'       => $linkTag->getTagName()
        );
    }

}