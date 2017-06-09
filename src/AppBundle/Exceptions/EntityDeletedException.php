<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 6/9/17
 * Time: 3:15 PM
 */

namespace AppBundle\Exceptions;


class EntityDeletedException extends \Exception
{
    protected $message = 'Etity was deleted';
    protected $code    = '001';

}