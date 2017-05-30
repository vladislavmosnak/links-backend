<?php
namespace AppBundle\Contexts\Api;


use AppBundle\Entity\LinkTags;
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
    private $urlExtractor;
    private $urlValidator;
    private $data = array(
        'title'         => null,
        'url'           => null,
        'description'   => null,
        'category'      => null,
        'image'         => null,
        'author'        => null,
        'linkTags'      => array(),
    );

    public function __construct(
        EntityManager $entityManager,
        ApiPrepared $apiPrepared,
        UrlExtractor $urlExtractor,
        UrlValidator $urlValidator){
        $this->em           = $entityManager;
        $this->jsonRepsonse = $apiPrepared;
        $this->urlExtractor = $urlExtractor;
        $this->urlValidator = $urlValidator;
    }

    public function createLink(){
        $newLink = parent::populateLink(
            $this->data['title'],
            $this->data['description'],
            $this->data['url'],
            $this->data['category'],
            $this->data['image'],
            $this->data['author']
        );

        $linkTags = explode(',', $this->data['linkTags']);

        foreach ($linkTags as $linkTag){
            $newLinkTag = new LinkTags();
            $newLinkTag->setLink($newLink);
            $newLinkTag->setTagName($linkTag);
            $this->em->persist($newLinkTag); //TODO where to handle this???
        }

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

        $notRequiredFromRequest = array('title', 'description', 'image', 'author', 'linkTags');

        foreach ($this->data as $key => $val){
            if(!isset($data[$key])){
                if(!in_array($key, $notRequiredFromRequest)) $errors[] = 'Missing ' . $key;
            }
            else $this->data[$key] = $data[$key];
        }

        if(count($errors)){
            return $this->jsonRepsonse->error($errors, Response::HTTP_BAD_REQUEST);
        }

        //TODO if not provided, detect category or set default one
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
            if(isset($extractedDataFromUrl['author']))      $this->data['author']           = $extractedDataFromUrl['author'];
            if(isset($extractedDataFromUrl['title']))       $this->data['title']            = $extractedDataFromUrl['title'];
            if(isset($extractedDataFromUrl['description'])) $this->data['description']      = $extractedDataFromUrl['description'];
            if(isset($extractedDataFromUrl['image']))       $this->data['image']            = $extractedDataFromUrl['image'];
            if(isset($extractedDataFromUrl['keywords']))    $this->data['linkTags']         = $extractedDataFromUrl['keywords'];
        }else{
            return $this->jsonRepsonse->error($errors, Response::HTTP_EXPECTATION_FAILED, 'Cant procces url data');
        }

        return true;

    }

}