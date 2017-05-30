<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 5/30/17
 * Time: 12:38 PM
 */

namespace AppBundle\Controller;


use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class CategoryController extends Controller
{
    /**
     * @ApiDoc(description="Get all categories")
     */
    public function allCategoriesAction(){
        $allCategoriesContext   = $this->get('app.contexts_api.api_all_categories_context');
        $allCategoriesResponse  = $allCategoriesContext->getAllLinksResponse();
        return JsonResponse::create($allCategoriesResponse->getData(), $allCategoriesResponse->getCode());
    }

}