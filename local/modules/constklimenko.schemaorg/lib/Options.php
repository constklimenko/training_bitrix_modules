<?php
/**
 * Created by PhpStorm.
 * User: constklimenko
 * Date: 15.08.23
 * Time: 9:53
 */

namespace Schemaorg;

use Bitrix\Main\Config\Option;

/**
 * Class Options
 * @package Schemaorg
 */
class Options
{

    public static function prepareUrl($request, $moduleId){
        $urlOption = $request->getPost('url');
        
        if(!empty($urlOption)){
            Option::set($moduleId, 'url', $urlOption );
        }else{
            $urlOption = Option::get($moduleId, 'url');
            if(empty($urlOption)){
                $urlOption  = $_SERVER['REQUEST_SCHEME'].'://'. $_SERVER['HTTP_HOST'];
            }
        }

        return $urlOption;
    }

    public static function prepareOption($request, $moduleId, $optionName){
        $option = $request->getPost($optionName);

        if(!empty($option)){
            Option::set($moduleId, $optionName, $option );
        }else{
            $option = Option::get($moduleId,  $optionName);
        }
        return $option;
    }
}