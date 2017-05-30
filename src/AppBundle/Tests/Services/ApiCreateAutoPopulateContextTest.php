<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 5/30/17
 * Time: 1:27 PM
 */

namespace Test\AppBundle\Services;

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../../../../app/AppKernel.php';


class ApiCreateAutoPopulateContextTest extends TestCase
{
    protected static $kernel;
    protected static $container;

    public static function setUpBeforeClass()
    {
        self::$kernel = new \AppKernel('dev', true);
        self::$kernel->boot();

        self::$container = self::$kernel->getContainer();
    }

    public function get($serviceId)
    {
        return self::$kernel->getContainer()->get($serviceId);
    }

    public function testpopulateAndValidate(){
        $apiCreateAutoPopulateContext = $this->get('app.contexts_api.api_create_auto_populate_context');
        $result = $apiCreateAutoPopulateContext->populateAndValidate(array(
            'url'       => 'https://www.google.rs/',
            'category'  => 1
        ));
        $this->assertTrue($result);

        $result = $apiCreateAutoPopulateContext->populateAndValidate(array(
            'url'       => 'https://www.invalidurl.invalid/',
            'category'  => 1
        ));
        $this->assertInstanceOf('AppBundle\Services\ApiPrepared', $result);

        $result = $apiCreateAutoPopulateContext->populateAndValidate(array(
            'url'       => 'https://www.invalidurl.invalid/',
            'category'  => 99999999999999999999999999999
        ));
        $this->assertInstanceOf('AppBundle\Services\ApiPrepared', $result);

        $result = $apiCreateAutoPopulateContext->populateAndValidate(array(
            'url'       => 'https://www.invalidurl.invalid/'
        ));
        $this->assertInstanceOf('AppBundle\Services\ApiPrepared', $result);

        $result = $apiCreateAutoPopulateContext->populateAndValidate(array(
            'category'  => 1
        ));
        $this->assertInstanceOf('AppBundle\Services\ApiPrepared', $result);

    }
}