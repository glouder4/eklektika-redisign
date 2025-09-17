<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Добавление филала");
?>
<div class="intec-content">
    <div class="intec-content-wrapper">
        <?php $APPLICATION->IncludeComponent(
            "intec:main.register",
            "director-add-new-branch",
            array(
                "AUTH" => "Y",
                "REQUIRED_FIELDS" => array(
                    0 => "EMAIL",
                    1 => "NAME",
                    2 => "LAST_NAME",
                    3 => "PERSONAL_BIRTHDAY",
                    4 => "PERSONAL_PHONE",
                    5 => "PERSONAL_CITY",
                    6 => "WORK_COMPANY",
                    7 => "LOGIN",
                    8 => "PASSWORD",
                    9 => "CONFIRM_PASSWORD"
                ),
                "SET_TITLE" => "Y",
                "SHOW_FIELDS" => array(
                    0 => "EMAIL",
                    1 => "NAME",
                    2 => "SECOND_NAME",
                    3 => "LAST_NAME",
                    4 => "PERSONAL_BIRTHDAY",
                    5 => "PERSONAL_PHONE",
                    6 => "PERSONAL_NOTES",
                    7 => "WORK_COMPANY",
                    8 => "WORK_POSITION",
                ),
                "SUCCESS_PAGE" => "",
                "USER_PROPERTY" => array(
                    0 => "UF_INN",
                    1 => "UF_KPP",
                    2 => "UF_NAME_COMPANY",
                    3 => "UF_JUR_ADDRESS",
                    4 => "UF_SPERE",
                    5 => "UF_SITE",
                    6 => "UF_REQ",
                    7 => "UF_ADVERSTERING_AGENT",
                    8 => "UF_CITY",
                ),
                "FORM_ACTION" => "/director/add_new_branch-action.php",
                "USER_PROPERTY_NAME" => "",
                "USE_BACKURL" => "Y",
                "COMPONENT_TEMPLATE" => "director-add-new-branch",
                "CONSENT_URL" => ""
            ),
            false
        );?>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>