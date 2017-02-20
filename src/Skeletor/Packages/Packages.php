<?php
namespace Skeletor\Packages;

class Packages
{
    function getPackages()
    {
        return [
            'Behat' => [
                'name' => 'Behat',
                'slug' => 'behat/behat',
                'version' => ''
            ],
            'JSON API Behat Extension' => [
                'name' => 'JSON API Behat Extension',
                'slug' => 'kielabokkie/jsonapi-behat-extension',
                'version' => ' --dev'
            ],
        ];
    }

    function getDefaultPackages()
    {
        return [
            'PixelFusion Git Hooks' => [
                'name' => 'PixelFusion Git Hooks',
                'slug' => 'pixelfusion/git-hooks',
                'version' => ''
            ],
        ];
    }
}