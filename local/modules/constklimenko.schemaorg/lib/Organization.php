<?php
/**
 * Created by PhpStorm.
 * User: constklimenko
 * Date: 16.08.23
 * Time: 10:13
 */

namespace Schemaorg;

use Bitrix\Main\Config\Option;

class Organization
{
    /**
     * @param $content
     */
    public static function createMicrodata(&$content){

        if (\CSite::InDir('/bitrix/')) {
            return;
        };
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
            return;
        }
        if ((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
            return;
        }

        $json = self::getJSON();

        $content = str_replace( '<head>', '<head><script type=\'application/ld+json\'>'. $json .'</script>', $content);
    }

    /**
     * @return string
     */
    public static function getJSON()
    {
        $arMicrodata = [
            "@context"=> "http://www.schema.org"
        ];

        $arMicrodata["@type"] = "Organization";
        $arMicrodata["name"] = self::getOption('name');
        $arMicrodata["url"] = self::getOption('url');

        $json = json_encode($arMicrodata);

        return $json;
    }

    /**
     * @param $optionName
     * @return string
     */
    public static function getOption($optionName){
        include(__DIR__ . "/../install/module.cfg.php");
        return Option::get($arModuleCfg['MODULE_ID'], $optionName, SITE_ID);
    }
}