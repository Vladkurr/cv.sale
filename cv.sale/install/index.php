<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bex\D7dull\ExampleTable;

Loc::loadMessages(__FILE__);

class CV_Sale extends CModule
{
    public function __construct()
    {
        $arModuleVersion = [];

        include(__DIR__ . '/version.php');

        $this->exclusionAdminFiles = [
            "..",
            ".",
            "menu.php",
            "operation_description.php",
            "task_description.php",
        ];

        $this->MODULE_ID = 'cv.sale';
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage('CV_SALE_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('CV_SALE_MODULE_DESCRIPTION');

        $this->PARTNER_NAME = Loc::getMessage('CV_SALE_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('CV_SALE_PARTNER_URI');

        $this->MODULE_SORT = 1;
        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = "Y";
        $this->MODULE_GROUP_RIGHTS = "Y";

        $this->MODULE_DIR = dirname(__DIR__);
    }


    public function InstallFiles()
    {
        CopyDirFiles(
            $this->MODULE_DIR . "/install/components/" . $this->MODULE_ID,
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/" . $this->MODULE_ID,
            true,
            true);

        return true;
    }

    public function UnInstallFiles()
    {
        DeleteDirFilesEx("/bitrix/components/" . $this->MODULE_ID);

        return true;
    }

    public function doInstall()
    {
        global $APPLICATION;
        $this->InstallFiles();
        ModuleManager::registerModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile(Loc::getMessage("CV_SALE_INSTALL_TITLE"), $this->MODULE_DIR . "/install/step.php");
    }

    public function doUninstall()
    {
        global $APPLICATION;
        ModuleManager::unRegisterModule($this->MODULE_ID);
        $APPLICATION->IncludeAdminFile(Loc::getMessage("CV_SALE_UNINSTALL_TITLE"), $this->MODULE_DIR . "/install/unstep.php");
        $this->UnInstallFiles();
    }

}