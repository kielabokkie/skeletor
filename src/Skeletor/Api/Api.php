<?php
namespace Skeletor\Api;

abstract class Api
{
    /**
     * @param string $data
     * @return string
     */
    public function jsonEncode(array $data)
    {
        return json_encode($data);
    }

    /**
     * @param string $data
     * @param bool $assoc
     * @return mixed
     */
    public function jsonDecode(string $data, bool $assoc = true)
    {
        return json_decode($data, $assoc);
    }
}