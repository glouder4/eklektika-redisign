<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

class intec_eklectika extends CModule {
    var $MODULE_ID = "intec.eklectika";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $PARTNER_NAME;
    var $PARTNER_URI;

    public function __construct () {
        /** @var array $arModuleVersion */
        require('version.php');

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage('intec.cosmo_import.install.index.name');
        $this->MODULE_DESCRIPTION = Loc::getMessage('intec.cosmo_import.install.index.description');
        $this->PARTNER_NAME = "INTEC";
        $this->PARTNER_URI = "https://intecweb.ru/";
    }

    function InstallDB() {
        global $DB;       
    }

    function UnInstallDB()
    {
        global $DB;
    }

    function DoInstall()
    {
        require_once(__DIR__.'/../classes/Loader.php');

        global $APPLICATION;
        parent::DoInstall();
		
        RegisterModule($this->MODULE_ID);
    }

    function DoUninstall()
    {
        require_once(__DIR__.'/../classes/Loader.php');

        global $APPLICATION;
        parent::DoUninstall();
        
        UnRegisterModule($this->MODULE_ID);
    }

    function GetInstallDirectory()
    {
        return __DIR__;
    }
}