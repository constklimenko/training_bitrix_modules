<?php
/**
 * Created by PhpStorm.
 * User: constklimenko
 * Date: 7.09.23
 * Time: 9:20
 */
if (file_exists(__DIR__ . "/install/module.cfg.php")) {
    include(__DIR__ . "/install/module.cfg.php");
};

use Bitrix\Main\Loader;
Loader::includeModule($arModuleCfg['MODULE_ID']);

$arClasses=array(
    /* Библиотеки и классы для авто загрузки */
    'Schemaorg\Options'=>'lib/Options.php',
    'Schemaorg\Organization'=>'lib/Organization.php',

);

Loader::registerAutoLoadClasses($arModuleCfg['MODULE_ID'], $arClasses);
