<?php
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