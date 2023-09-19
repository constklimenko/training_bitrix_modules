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

    public static function prepareOpenHours($request, $arModuleCfg){
        $openHoursString = '';
        $moduleId = $arModuleCfg['MODULE_ID'];
        foreach ($arModuleCfg['DAYS_OF_WEEK'] as $day => $dayName) {
            if ($request->getPost( 'hours_'.$day.'_checked') !== 'on') continue;
            $HoursBeginning = $request->getPost( 'hours_beginning_'.$day);
            $HoursEnding = $request->getPost( 'hours_ending_'.$day);
            if(!empty($HoursBeginning) && !empty($HoursEnding)){
                $openHoursString .= $day .' '. $HoursBeginning.'-'.$HoursEnding.' ';
            }
        }
        if(!empty($openHoursString)){
            Option::set($moduleId, 'openHours', $openHoursString);
        }else{
            $openHoursString = Option::get($moduleId, 'openHours');
        }
        return $openHoursString;
    }

    public static function parseOpenHours($openHoursString, $arModuleCfg){
        $arExploded =  explode(' ', $openHoursString);
        $arOpenHours = [];
        for($key = 0; $key < count($arExploded); $key = $key + 2){
            $value = $arExploded[$key + 1];
            $name = $arExploded[$key];
            if(!empty($name)){
                $arOpenHours[$name]['open'] = explode('-',$value)[0];
                $arOpenHours[$name]['close'] = explode('-',$value)[1];
            }
        }
        return $arOpenHours;
    }
}