<?php
/**
 * Created by PhpStorm.
 * Date: 2019/1/5
 * Time: 下午10:49
 */

namespace App\Util;

class JsonPResolver
{
    public static function resolve($jsonp)
    {
        preg_match('/jQuery.*\((.*)\)/', $jsonp, $matchs);
        if (!isset($matchs[1])) {
            $jsonp = preg_replace('/jsonp.*\(/', '', $jsonp);
            $jsonp = preg_replace('/\)/', '', $jsonp);
            return $jsonp;
        }
        return $matchs[1];
    }

    public static function resolve2($jsonp)
    {
        preg_match('/var aff_data=\((.*)\)/', $jsonp, $matchs);
        return json_decode($matchs[1], true);
    }

}