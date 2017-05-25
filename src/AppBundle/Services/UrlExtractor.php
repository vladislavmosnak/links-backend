<?php
/**
 * Created by PhpStorm.
 * User: vmosnak
 * Date: 5/25/17
 * Time: 1:05 PM
 */

namespace AppBundle\Services;


class UrlExtractor
{
    //TODO make smarter extractor
    public function getDataFromUrl($url){
        $metaData = get_meta_tags($url);
        if(isset($metaData['title'], $metaData['description'])) return $metaData;
        return false;
    }

}