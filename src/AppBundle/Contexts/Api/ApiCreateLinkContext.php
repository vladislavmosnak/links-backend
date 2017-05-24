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
use AppBundle\Services\ImageFromUrl;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;

class ApiCreateLinkContext extends LinkModel
{

    private $em;
    private $jsonRepsonse;
    private $imageFromUrl;
    private $data = array(
        'url'           => null,
        'title'         => null,
        'description'   => null,
        'category'      => null,
        'image'         => null
    );

    public function __construct(
        EntityManager $entityManager,
        ApiPrepared $jsonResponse,
        ImageFromUrl $imageFromUrl
    ){
        $this->em           = $entityManager;
        $this->jsonRepsonse = $jsonResponse;
        $this->imageFromUrl = $imageFromUrl;
    }

    public function createLink(){
        $newLink = new Link();
        $newLink->setDescription($this->data['description']);
        $newLink->setTitle($this->data['title']);
        $newLink->setUrl($this->data['url']);
        $newLink->setCategory($this->data['category']);
        $newLink->setImage($this->data['image']);

        $this->em->persist($newLink);
        $this->em->flush();

        return $newLink;
    }

    public function createLinkResponse(){
        $newLink = $this->createLink();
        return $this->jsonRepsonse->success(parent::toArray($newLink), 'Link created', Response::HTTP_CREATED);
    }

    public function populateAndValidate(array $data){
        $errors = array();

        $notRequiredFromRequest = array('image');

        foreach ($this->data as $key => $val){
            if(!isset($data[$key])){
                if(!in_array($key, $notRequiredFromRequest)) $errors[] = 'Missing ' . $key;
            }
            else $this->data[$key] = $data[$key];
        }

        if(count($errors)){
            return $this->jsonRepsonse->error($errors, Response::HTTP_BAD_REQUEST);
        }

        $this->data['category'] = $this->em->getRepository('AppBundle:LinkCategory')->find($this->data['category']);
        if(!$this->data['category']){
            $errors[] = 'No category for ID: ' . $data['category'];
            return $this->jsonRepsonse->error($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->data['image'] = $this->imageFromUrl->getImage($this->data['url']);

        return true;
    }
}