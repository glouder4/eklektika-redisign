<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Title");
use intec\eklectika\advertising_agent\Company;

CModule::IncludeModule("intec.eklectika");

// создание компании, по умолчанию она неактивная, требуется активация
$companyId = Company::add([
	"NAME_COMPANY" => "asdfasdf asdf ", // название компании
	"INN" => "", // ИНН
	"KPP" => "", // КПП
	"BOSS" => "", // id пользователя, который является директором
	"WEBSITE" => "test.test", // сайт
	"SPHERE" => "", // сфера деятельности
	"ADDRESS" => "", // адрес
	"STAFFS" => "", // ид пользователей, которые являются сотрудниками	
]);

// поиск компании по ИНН
// $company = Company::findByInn(123);

// $idCompany = $company["ID"];

// активация компании
// Company::activate($idCompany);

// обновление компании
// Company::update($idCompany, [
	// "WEBSITE" => "test.test1",
// ]);
// Company::addProfile(67, 79074);


?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>