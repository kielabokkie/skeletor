<?php
namespace Skeletor\Api;

abstract class Api
{
    /**
     * @param $data
     * @return string
     */
    public function jsonEncode($data)
    {
        return json_encode($data);
    }

    /**
     * @param $data
     * @param bool $assoc
     * @return mixed
     */
    public function jsonDecode($data, $assoc = true)
    {
        return json_decode($data, $assoc);
    }
}