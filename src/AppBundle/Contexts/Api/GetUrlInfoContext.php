<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 5/25/17
 * Time: 12:36 PM
 */

namespace AppBundle\Contexts\Api;


use AppBundle\Model\LinkModel;
use AppBundle\Services\ApiPrepared;
use AppBundle\Services\UrlExtractor;
use AppBundle\Services\UrlValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;

class GetUrlInfoContext extends LinkModel
{

    private $jsonResponse;
    private $em;
    private $urlValidator;
    private $urlExtractor;
    private $data = array(
        'url' => null
    );

    public function __construct(
        EntityManager $entityManager,
        ApiPrepared $apiPrepared,
        UrlValidator $urlValidator,
        UrlExtractor $urlExtractor){
        $this->em           = $entityManager;
        $this->jsonResponse = $apiPrepared;
        $this->urlValidator = $urlValidator;
        $this->urlExtractor = $urlExtractor;
    }

    public function extractInfoFromUrl(){
        $extractedDataFromUrl = $this->urlExtractor->getDataFromUrl($this->data['url']);
        if($extractedDataFromUrl) return array(
            'title' => $extractedDataFromUrl['title'],
            'description' => $extractedDataFromUrl['description']
        );
        return array();
    }

    public function extractInfoFromUrlResponse(){
        $urlInfo = $this->extractInfoFromUrl();
        if(count($urlInfo)) $message = 'Url info';
        else $message = 'Cant Extract info';
        return $this->jsonResponse->success($urlInfo, $message, Response::HTTP_OK);
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
            return $this->jsonResponse->error($errors, Response::HTTP_BAD_REQUEST);
        }

        if($this->urlValidator->isUrlValid($this->data['url'])){
            return true;
        }
        return $this->jsonResponse->error(array('Url is not valid'), Response::HTTP_UNPROCESSABLE_ENTITY);


    }

}