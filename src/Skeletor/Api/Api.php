<?php
namespace Skeletor\Api;

abstract class Api
{
    /**
     * @param array $data
     * @return string
     */
    public function jsonEncode(array $data)
    {
        return json_encode($data);
    }

    /**
     * @param array $data
     * @param bool $assoc
     * @return mixed
     */
    public function jsonDecode(array $data, bool $assoc = true)
    {
        return json_decode($data, $assoc);
    }
}