<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 5/30/17
 * Time: 12:59 PM
 */

namespace AppBundle\Controller;


use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class InitController
 * @package AppBundle\Controller
 */
class InitController extends Controller
{

    /**
     * @ApiDoc(description="init call")
     */
    public function initAction(){
        $initContext = $this->get('app.contexts_api.api_init_context');
        $initContextResponse = $initContext->getInitDataResponse();
        return JsonResponse::create($initContextResponse->getData(), $initContextResponse->getCode());
    }

}