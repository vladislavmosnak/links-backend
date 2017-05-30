<?php
namespace AppBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LinkController extends Controller
{
    /**
     * ### Get all links ##
     *
     * ### SUCCESS RESPONSE ###
     * {
     *" status": true,
     *  "message": "All Links",
     *  "data": [
     *  {
     *      "id": 1,
            *"url": "https://blog.embed.ly/creating-a-facebook-like-url-submission-tool-with-embedly-ed00dcc91c62",
     *      "title": "Creating a Facebook-like URL submission tool with Embedly",
     *      "description": "One of our clients is currently using Ext and Embedly to implement a Facebook-like link submission tool. While writing up some examples we went overboard and ended up coding the whole thing. It’s…",
     *      "image": "dummy image",
     *      "author": "",
     *      "category": {
     *          "id": 1,
     *          "name": "learning"
     *      }
     *  },
     *  {
     *      "id": 1,
     *      "url": "https://blog.embed.ly/creating-a-facebook-like-url-submission-tool-with-embedly-ed00dcc91c62",
     *      "title": "Creating a Facebook-like URL submission tool with Embedly",
     *      "description": "One of our clients is currently using Ext and Embedly to implement a Facebook-like link submission tool. While writing up some examples we went overboard and ended up coding the whole thing. It’s…",
     *      "image": "dummy image",
     *      "author": "",
     *      "category": {
     *          "id": 1,
     *          "name": "learning"
     *      }
     *  },
     *]
     *}
     *
     * @ApiDoc(description="Get all links")
     */
    public function allAction(){
        $allLinksContext    = $this->get('app.contexts_api.api_all_links_context');
        $allLinksResponse   = $allLinksContext->getAllLinksResponse();
        return JsonResponse::create($allLinksResponse->getData(), $allLinksResponse->getCode());
    }

    /**
     * ### Get single link ##
     *
     * ### SUCCESS RESPONSE ###
     * {
     *    "status": true,
     *    "message": "",
     *    "data": {
     *      "id": 1,
     *      "url": "https://blog.embed.ly/creating-a-facebook-like-url-submission-tool-with-embedly-ed00dcc91c62",
     *      "title": "Creating a Facebook-like URL submission tool with Embedly",
     *      "description": "One of our clients is currently using Ext and Embedly to implement a Facebook-like link submission tool. While writing up some examples we went overboard and ended up coding the whole thing. It’s…",
     *      "image": "dummy image",
     *      "author": "",
     *      "category": {
     *          "id": 1,
     *          "name": "learning"
     *      }
     *    }
     * }
     *
     * ### NONT FOUND RESPONSE ###
     * {
     *  "status": false,
     *  "message": "",
     *  "data": [
     *      "Link not found"
     *  ]
     * }
     *
     * @ApiDoc(description="Get single link")
     */
    public function singleAction($id){
        $singleLinkContex = $this->get('app.contexts_api.api_single_link_context');
        $linkResponse = $singleLinkContex->getSingleLinkResponse($id);
        return JsonResponse::create($linkResponse->getData(), $linkResponse->getCode());
    }

    /**
     * ### Creates new link ##
     *
     * ### SUCCESS RESPONSE ###
     *{
     *   "status": true,
     *   "message": "Link created",
     *   "data": {
     *       "id": 24,
     *       "url": "http://google.rs",
     *       "title": "213",
     *       "description": "qaweqw",
     *       "image": "Default image",
     *       "author": "Unknown author",
     *       "category": {
     *           "id": 1,
     *           "name": "learning"
     *       }
     *   }
     *}
     *
     * ### BAD REQUEST RESPONSE ###
     * {
     *  "status": false,
     *  "message": "",
     *  "data": [
     *      "Missing url",
     *      "Missing title",
     *      "Missing description",
     *      "Missing category"
     *  ]
     * }
     *
     * ### UNPROCESSABLE RESPONSE ###
     *
     * {
     *  "status": false,
     *  "message": "",
     *  "data": [
     *      "Url is not valid"
     *  ]
     * }
     *
     * @ApiDoc(
     *     description="Creates new link",
     *     parameters={
     *      {"name"="url", "dataType"="string", "required"=true, "description"="url"},
     *      {"name"="title", "dataType"="string", "required"=true, "description"="title"},
     *      {"name"="description", "dataType"="string", "required"=true, "description"="description"},
     *      {"name"="category", "dataType"="integer", "required"=true, "description"="category ID"},
     *      {"name"="image", "dataType"="string", "required"=false, "description"="image url"},
     *      {"name"="author", "dataType"="string", "required"=false, "description"="author name"},
     *      {"name"="autopopulate", "dataType"="bool", "required"=false, "description"="autopopulate option, default false"}
     *  }, statusCodes={
     *      201="Returned when successful",
     *     400="Missing params",
     *     422={
     *          "Url is not valid",
     *          "Category was not found"
     *     }
     *  })
     */
    public function createAction(Request $request){
        $newLinkContext     = $this->get('app.contexts_api.api_create_link_context');
        $data               = $request->request->all();
        $validationResponse = $newLinkContext->populateAndValidate($data);
        if($validationResponse !== true)
            return JsonResponse::create($validationResponse->getData(), $validationResponse->getCode());
        $newLinkResponse = $newLinkContext->createLinkResponse();
        return JsonResponse::create($newLinkResponse->getData(), $newLinkResponse->getCode());
    }

    /**
     * ### Creates new link - autopopulates links properties ###
     * In case no autopopulated data, if provided optional params will bi used,
     * if not, default one will be set to url property
     *
     * ### SUCCESS RESPONSE ###
     *  {
     *   "status": true,
     *   "message": "Link created",
     *   "data": {
     *       "id": 24,
     *       "url": "http://google.rs",
     *       "title": "213",
     *       "description": "qaweqw",
     *       "image": "Default image",
     *       "author": "Unknown author",
     *       "category": {
     *           "id": 1,
     *           "name": "learning"
     *       }
     *   }
     * }
     *
     * ### BAD REQUEST RESPONSE ###
     * {
     *  "status": false,
     *  "message": "",
     *  "data": [
     *      "Missing url",
     *      "Missing title",
     *      "Missing description",
     *      "Missing category"
     *  ]
     * }
     *
     * ### UNPROCESSABLE RESPONSE ###
     *
     * {
     *  "status": false,
     *  "message": "",
     *  "data": [
     *      "Url is not valid"
     *  ]
     * }
     *
     * @ApiDoc(
     *     description="Creates new link - autopopulates link properties",
     *     parameters={
     *      {"name"="url", "dataType"="string", "required"=true, "description"="url"},
     *      {"name"="category", "dataType"="integer", "required"=true, "description"="category ID"},
     *      {"name"="title", "dataType"="string", "required"=false, "description"="title"},
     *      {"name"="description", "dataType"="string", "required"=false, "description"="description"},
     *      {"name"="image", "dataType"="string", "required"=false, "description"="image url"},
     *      {"name"="author", "dataType"="string", "required"=false, "description"="author name"},
     *      {"name"="autopopulate", "dataType"="bool", "required"=false, "description"="autopopulate option, default false"}
     *  }, statusCodes={
     *      201="Returned when successful",
     *     400="Missing params",
     *     422={
     *          "Url is not valid",
     *          "Category was not found"
     *     }
     *  })
     */ //TODO update dosc for tags
    public function createAutoPopulateAction(Request $request){
        $autopopulateContext = $this->get('app.contexts_api.api_create_auto_populate_context');
        $data = $request->request->all();
        $validationResponse = $autopopulateContext->populateAndValidate($data);
        if($validationResponse !== true){
            return JsonResponse::create($validationResponse->getData(), $validationResponse->getCode());
        }
        $newLinkResponse = $autopopulateContext->createLinkResponse();
        return JsonResponse::create($newLinkResponse->getData(), $newLinkResponse->getCode());
    }

    public function searchAction(Request $request){
        $searchContext = $this->get('app.contexts_api.api_search_link_context');
        $searchContext->getBy($request->request->all());
    }

    //TODO adddocs here
    /**
     * ### Get infro about URL ###
     * Provides info line title, description, keywords, etc
     *
     * ### SUCCESS RESPONSE ###
     *
     * @ApiDoc(
     *     description="Creates new link - autopopulates link properties",
     *     parameters={
     *      {"name"="url", "dataType"="string", "required"=true, "description"="url"},
     *  }, statusCodes={
     *      200="Returned when successful",
     *     400="Missing params",
     *  })
     */
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