<?php
namespace Skeletor\Api;

abstract class Api
{
    public function jsonEncode($data)
    {
        return json_encode($data);
    }

    public function jsonDecode($data, $assoc = true)
    {
        return json_decode($data, $assoc);
    }
}