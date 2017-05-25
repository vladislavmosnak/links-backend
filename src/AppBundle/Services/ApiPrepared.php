<?php
/**
 * Created by PhpStorm.
 * User: vladi
 * Date: 21.5.2017.
 * Time: 19.00
 */

namespace AppBundle\Services;


class ApiPrepared
{
    private $code;
    private $data;

    public function success(array $data, $message = '', $code = 200){
        $this->code = $code;
        $this->data = array(
            'status' => true,
            'message' => $message,
            'data' => $data
        );
        return $this;
    }

    public function error(array $data, $code = 500, $message = ''){
        $this->code = $code;
        $this->data = array(
            'status' => false,
            'message' => $message,
            'data' => $data
        );
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }




}