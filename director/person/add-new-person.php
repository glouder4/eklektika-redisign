<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Добавление сотрудника");
?>
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <?php $APPLICATION->IncludeComponent(
                "intec:main.register",
                "director-add-new-person",
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
                    "SET_TITLE" => "N",
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
                    ),
                    "FORM_ACTION" => "/director/person/add-new-person-action.php",
                    "USER_PROPERTY_NAME" => "",
                    "USE_BACKURL" => "Y",
                    "COMPONENT_TEMPLATE" => "director-add-new-person",
                    "CONSENT_URL" => ""
                ),
                false
            );?>
        </div>
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>