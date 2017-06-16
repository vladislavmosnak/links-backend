<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 6/14/17
 * Time: 2:27 PM
 */

namespace AppBundle\Contexts\Api;


use AppBundle\Model\LinkModel;
use AppBundle\Services\ApiPrepared;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

class ApiDeleteLinkContext
{

    private $linkModel;
    private $jsonReposne;

    public function __construct(LinkModel $linkModel, ApiPrepared $apiPrepared){
        $this->linkModel    = $linkModel;
        $this->jsonReposne  = $apiPrepared;
    }

    public function deleteLinkResponse($id){
        $link = $this->linkModel->deleteLink($id);
        if(!$link)
            return $this->jsonReposne->error(array('Link not found'), Response::HTTP_NOT_FOUND);

        return $this->jsonReposne->error(array('Link deleted'), 200);
    }

}