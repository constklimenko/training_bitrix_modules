<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
Loc::loadMessages(__FILE__);
if (class_exists('constklimenko_schemaorg')) {
    return;
}
class constklimenko_schemaorg extends CModule{
    var $MODULE_GROUP_RIGHTS = 'Y';
    var $errors = false;
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $PARTNER_NAME;

    /**
     * constklimenko_schemaorg constructor.
     */
    public function __construct()
    {
        $this->MODULE_ID 		   = "constklimenko.schemaorg";
        $arModuleVersion = array();

        include(__DIR__.'/version.php');

        if (!empty($arModuleVersion['VERSION']))
        {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
            $this->MODULE_NAME 		   = "Пример модуля";
            $this->MODULE_DESCRIPTION  = "описание модуля";
            $this->PARTNER_NAME 	   = "Константин Клименко";

    }


    public function DoInstall()
    {
        $this->InstallFiles();
        $this->InstallDB(false);
        $this->InstallEvents();
        RegisterModule($this->MODULE_ID);
        return true;
    }

    public function DoUninstall()
    {
        $this->UnInstallDB(false);
        $this->UnInstallFiles();
        $this->UnInstallEvents();
        UnRegisterModule($this->MODULE_ID);
        return true;
    }

    public function InstallFiles(){
        CopyDirFiles(
            __DIR__."/../components",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/", true, true
        );
        return true;
    }

    public function UnInstallFiles(){
        DeleteDirFilesEx(
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/constklimenko/"
        );
        return true;
    }

    public function InstallEvents()
    {
        RegisterModuleDependences("main", "OnEndBufferContent", $this->MODULE_ID, "Schemaorg\Organization", "createMicrodata");
        return true;
    }

    public function UnInstallEvents()
    {
        UnRegisterModuleDependences("main", "OnEndBufferContent", $this->MODULE_ID, "Schemaorg\Organization", "createMicrodata");
        return false;
    }


}