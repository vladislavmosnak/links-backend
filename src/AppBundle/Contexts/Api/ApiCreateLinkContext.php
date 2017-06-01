<?php
namespace AppBundle\Contexts\Api;

use AppBundle\Model\LinkModel;
use AppBundle\Services\ApiPrepared;
use AppBundle\Services\ImageFromUrl;
use AppBundle\Services\UrlExtractor;
use AppBundle\Services\UrlValidator;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;

class ApiCreateLinkContext
{

    private $em;
    private $linkModel;
    private $jsonRepsonse;
    private $urlExtractor;
    private $urlValidator;
    private $data = array(
        'url'           => null,
        'title'         => null,
        'description'   => null,
        'category'      => null,
        'image'         => null,
        'author'        => null,
        'linktags'      => null,
        'autopopulate'  => false
    );

    public function __construct(
        EntityManager $entityManager,
        LinkModel $linkModel,
        ApiPrepared $jsonResponse,
        UrlExtractor $urlExtractor,
        UrlValidator $urlValidator
    ){
        $this->em           = $entityManager;
        $this->linkModel    = $linkModel;
        $this->jsonRepsonse = $jsonResponse;
        $this->urlExtractor = $urlExtractor;
        $this->urlValidator = $urlValidator;
    }

    public function createLink(){
        $newLink = $this->linkModel->saveLink(
            $this->data['title'],
            $this->data['description'],
            $this->data['url'],
            $this->data['category'],
            $this->data['image'],
            $this->data['author'],
            $this->data['linktags']
        );

        return $newLink;
    }

    public function createLinkResponse(){
        $newLink = $this->createLink();
        return $this->jsonRepsonse->success($this->linkModel->toArray($newLink), 'Link created', Response::HTTP_CREATED);
    }

    public function populateAndValidate(array $data){
        $errors = array();

        $notRequiredFromRequest = array('image', 'autopopulate', 'author');

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
            return $this->jsonRepsonse->error(array('Url is not valid'), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $extractedDataFromUrl = $this->urlExtractor->getDataFromUrl($this->data['url']);
        if($extractedDataFromUrl) {
            if(isset($extractedDataFromUrl['image']))       $this->data['image']            = $extractedDataFromUrl['image'];
        }else{
            return $this->jsonRepsonse->error($errors, Response::HTTP_EXPECTATION_FAILED, 'Cant procces url data');
        }

        return true;
    }
}