<?php
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

if (!CModule::IncludeModule("iblock")) {
	return;
}

require_once('classes/Loader.php');
Loc::loadMessages(__FILE__);
