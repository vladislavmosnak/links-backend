<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 5/30/17
 * Time: 12:45 PM
 */

namespace AppBundle\Model;


use AppBundle\Entity\LinkCategory;
use AppBundle\Exceptions\EntityDeletedException;
use Doctrine\ORM\EntityManager;

class CategoryModel
{

    private $em;
    private $repository;

    public function __construct(EntityManager $entityManager){
        $this->em           = $entityManager;
        $this->repository   = $this->em->getRepository('AppBundle:LinkCategory');
    }

    public function getRepository(){
        return $this->repository;
    }

    public function toArray(LinkCategory $linkCategory){
        return array(
            'id'            => $linkCategory->getId(),
            'categoryName'  => $linkCategory->getCategoryName()
        );
    }

    public function getAllCategories(){
        return $this->repository->findAll();
    }

    public function getSingleCategory($id){
        $category = $this->repository->find($id);
        if($category->getDeletedAt()){
            throw new EntityDeletedException();
        }
        return $category;
    }

}