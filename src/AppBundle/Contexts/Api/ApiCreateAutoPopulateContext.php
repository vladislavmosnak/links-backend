<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 5/25/17
 * Time: 1:37 PM
 */

namespace AppBundle\Contexts\Api;


use AppBundle\Model\LinkModel;
use AppBundle\Services\ApiPrepared;
use AppBundle\Services\ImageFromUrl;
use AppBundle\Services\UrlExtractor;
use AppBundle\Services\UrlValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;

class ApiCreateAutoPopulateContext extends LinkModel
{

    private $em;
    private $jsonRepsonse;
    private $imageFromUrl;
    private $urlExtractor;
    private $urlValidator;
    private $data = array(
        'title'         => null,
        'url'           => null,
        'description'   => null,
        'category'      => null,
        'image'         => null
    );

    public function __construct(
        EntityManager $entityManager,
        ApiPrepared $apiPrepared,
        ImageFromUrl $imageFromUrl,
        UrlExtractor $urlExtractor,
        UrlValidator $urlValidator){
        $this->em           = $entityManager;
        $this->jsonRepsonse = $apiPrepared;
        $this->imageFromUrl = $imageFromUrl;
        $this->urlExtractor = $urlExtractor;
        $this->urlValidator = $urlValidator;
    }

    public function createLink(){
        $newLink = parent::populateLink(
            $this->data['title'],
            $this->data['description'],
            $this->data['url'],
            $this->data['category'],
            $this->data['image']
        );

        $this->em->persist($newLink);
        $this->em->flush();

        return $newLink;
    }

    public function createLinkResponse(){
        $newLink = $this->createLink();
        return $this->jsonRepsonse->success(parent::toArray($newLink), 'Link created', Response::HTTP_CREATED);
    }

    public function populateAndValidate($data){
        $errors = array();

        $notRequiredFromRequest = array('title', 'description', 'image');

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


        if(!$this->urlValidator->isUrlValid($this->data['url'])){
            return $this->jsonResponse->error(array('Url is not valid'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //TODO move imageFromUrl logic to UrlExtractor
        $this->data['image'] = $this->imageFromUrl->getImage($this->data['url']);

        //TODO what if extractor fails in just one field?
        $extractedDataFromUrl = $this->urlExtractor->getDataFromUrl($this->data['url']);
        if($extractedDataFromUrl) {
            (isset($extractedDataFromUrl['title'])) ? $this->data['title'] = $extractedDataFromUrl['title'] : $this->data['title'] = '';
            (isset($extractedDataFromUrl['description'])) ? $this->data['description'] = $extractedDataFromUrl['title'] : $this->data['description'] = '';
        }else{
            return $this->jsonRepsonse->error($errors, Response::HTTP_EXPECTATION_FAILED, 'Cant procces url data');
        }

        return true;

    }

}