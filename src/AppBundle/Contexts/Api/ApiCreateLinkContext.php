<?php
/**
 * Created by PhpStorm.
 * User: vladi
 * Date: 21.5.2017.
 * Time: 19.57
 */

namespace AppBundle\Contexts\Api;


use AppBundle\Entity\Link;
use AppBundle\Model\LinkModel;
use AppBundle\Services\ApiPrepared;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class ApiCreateLinkContext extends LinkModel
{

    private $em;
    private $jsonRepsonse;
    private $requestData = array(
        'url'           => '',
        'title'         => '',
        'description'   => '',
        'category'      => ''
    );

    public function __construct(EntityManager $entityManager, ApiPrepared $jsonResponse){
        $this->em           = $entityManager;
        $this->jsonRepsonse = $jsonResponse;
    }

    public function createLink(array $data){
        $url            = $data['url'];
        $title          = $data['title'];
        $description    = $data['description'];
        $categoryId     = $data['category'];

        $category = $this->em->getRepository('AppBundle:LinkCategory')->find($categoryId);

        $newLink = new Link();
        $newLink->setDescription($description);
        $newLink->setTitle($title);
        $newLink->setUrl($url);
        $newLink->setCategory($category);

        $this->em->persist($newLink);
        $this->em->flush();

        return $newLink;
    }

    public function createLinkResponse(array $data){
        $newLink = $this->createLink($data);
        return $this->jsonRepsonse->success(parent::toArray($newLink), 'Link created');
    }

    public function isRequestValidInContext(array $data){
        $errors = array();

        foreach ($this->requestData as $key => $val){
            if(!isset($data[$key])) $errors[] = 'Missing ' . $key;
            $this->requestData[$key] = $data['key'];
        }

        if(isset($data['category'])){
            $categoryId = $data['category'];
            $category = $this->em->getRepository('AppBundle:LinkCategory')->find($categoryId);
            if(!$category){
                $errors[] = 'No category for ID: ' . $categoryId;
            }
        }
        else $errors[] = 'Missing category';

        if(count($errors) == 0){
            return true;
        }
        $this->jsonRepsonse->error($errors, 400);
        return false;
    }

    public function getContextData(){
        return $this->jsonRepsonse->getData();
    }
}