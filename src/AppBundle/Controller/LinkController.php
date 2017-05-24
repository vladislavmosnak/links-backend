<?php
/**
 * Created by PhpStorm.
 * User: vladi
 * Date: 21.5.2017.
 * Time: 18.21
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LinkController extends Controller
{

    public function allAction(){
        $allLinksContext = $this->get('app.contexts_api.api_all_links_context');
        $allLinksResponse = $allLinksContext->getAllLinksResponse();
        return JsonResponse::create($allLinksResponse->getData(), $allLinksResponse->getCode());
    }

    public function singleAction($id){
        $singleLinkContex = $this->get('app.contexts_api.api_single_link_context');
        $linkResponse = $singleLinkContex->getSingleLinkResponse($id);
        return JsonResponse::create($linkResponse->getData(), $linkResponse->getCode());
    }

    public function createAction(Request $request){
        $newLinkContext = $this->get('app.contexts_api.api_create_link_context');
        $isRequestValid = $newLinkContext->isRequestValidInContext($request->request->all());
        if($isRequestValid !== true)
            return JsonResponse::create($newLinkContext->getContextData(), 400);
        $newLinkResponse = $newLinkContext->createLinkResponse($request->request->all());
        return JsonResponse::create($newLinkResponse->getData(), $newLinkResponse->getCode());
    }
}