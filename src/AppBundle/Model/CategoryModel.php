<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 5/30/17
 * Time: 12:45 PM
 */

namespace AppBundle\Model;


use AppBundle\Entity\LinkCategory;
use Doctrine\ORM\EntityManager;

class CategoryModel
{

    private $em;

    public function __construct(EntityManager $entityManager){
        $this->em = $entityManager;
    }

    public function toArray(LinkCategory $linkCategory){
        return array(
            'id'            => $linkCategory->getId(),
            'categoryName'  => $linkCategory->getCategoryName()
        );
    }

}