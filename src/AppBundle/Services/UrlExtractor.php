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
    //TODO add image to response
    public function getDataFromUrl($url){
        $return = array(
            'title'         => null,
            'description'   => null,
            'author'        => null,
            'image'         => null,
        );
        $metaData = get_meta_tags($url);
        if(isset($metaData['title']))       $return['title']        = $metaData['title'];
        if(isset($metaData['description'])) $return['description']  = $metaData['description'];
        if(isset($metaData['author']))      $return['author']       = $metaData['author'];
        if(isset($metaData['twitter:image']))       $return['image']       = $metaData['twitter:image'];
        return $return;
    }

}