<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 5/25/17
 * Time: 12:44 PM
 */

namespace AppBundle\Services;


class UrlValidator
{
    //TODO make better test
    public function isUrlValid($url){
        if (@fopen($url,"rb")) {
            return true;
        }
        return false;
    }

}