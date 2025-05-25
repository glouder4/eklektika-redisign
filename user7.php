<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
use intec\eklectika\advertising_agent\Company;
CModule::IncludeModule("intec.eklectika");
?>
<pre>
<?
$company = Company::findByIdB24(2552);
print_r($company);
?>
</pre>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>