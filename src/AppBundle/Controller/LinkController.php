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
        $allLinksContext    = $this->get('app.contexts_api.api_all_links_context');
        $allLinksResponse   = $allLinksContext->getAllLinksResponse();
        return JsonResponse::create($allLinksResponse->getData(), $allLinksResponse->getCode());
    }
 
    public function singleAction($id){
        $singleLinkContex = $this->get('app.contexts_api.api_single_link_context');
        $linkResponse = $singleLinkContex->getSingleLinkResponse($id);
        return JsonResponse::create($linkResponse->getData(), $linkResponse->getCode());
    }

    public function createAction(Request $request){
        $newLinkContext     = $this->get('app.contexts_api.api_create_link_context');
        $data               = $request->request->all();
        $validationResponse = $newLinkContext->populateAndValidate($data);
        if($validationResponse !== true)
            return JsonResponse::create($validationResponse->getData(), $validationResponse->getCode());
        $newLinkResponse = $newLinkContext->createLinkResponse();
        return JsonResponse::create($newLinkResponse->getData(), $newLinkResponse->getCode());
    }

    public function searchAction(Request $request){
        $searchContext = $this->get('app.contexts_api.api_search_link_context');
        $searchContext->getBy($request->request->all());
    }

    public function getUrlInfoAction(Request $request){
        $getUrlInfoContext = $this->get('app.contexts_api.get_url_info_context');
        $data = $request->query->all();
        $getUrlInfoContextValidationRepsonse = $getUrlInfoContext->populateAndValidate($data);
        if($getUrlInfoContextValidationRepsonse !== true){
            return JsonResponse::create($getUrlInfoContextValidationRepsonse->getData(), $getUrlInfoContextValidationRepsonse->getCode());
        }
        $getUrlInfoContextDataResponse = $getUrlInfoContext->extractInfoFromUrlResponse();
        return JsonResponse::create($getUrlInfoContextDataResponse->getData(), $getUrlInfoContextDataResponse->getCode());
    }
}