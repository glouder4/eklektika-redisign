<?php
    use Bitrix\Main\Page\Asset;
    if( isset($_GET['test']) ){
        Asset::getInstance()->addCss("/local/templates/universe_s1/onlineservice_addons/styles/template.css");
        Asset::getInstance()->addCss("/local/templates/universe_s1/onlineservice_addons/styles/header.css");

        Asset::getInstance()->addCss("/local/templates/universe_s1/onlineservice_addons/styles/jquery.fancybox.css");


        Asset::getInstance()->addCss("/local/templates/universe_s1/onlineservice_addons/styles/footer.css");

        Asset::getInstance()->addJs("/local/templates/universe_s1/onlineservice_addons/scripts/template.js",true);
        Asset::getInstance()->addJs("/local/templates/universe_s1/onlineservice_addons/scripts/header.js",true);

        Asset::getInstance()->addJs("/local/templates/universe_s1/onlineservice_addons/scripts/jquery.fancybox.min.js",true);

    }

if ($APPLICATION->GetCurPage(false) === '/'):
    Asset::getInstance()->addCss("/local/templates/universe_s1/onlineservice_addons/components/mainpage/slider/styles/owl.carousel.min.css");
    Asset::getInstance()->addCss("/local/templates/universe_s1/onlineservice_addons/components/mainpage/slider/styles/styles.css");
//<link rel="stylesheet" href="./components/mainpage/slider/styles/owl.carousel.min.css">
//    <link rel="stylesheet" href="./components/mainpage/slider/styles/styles.css">
    Asset::getInstance()->addJs("/local/templates/universe_s1/onlineservice_addons/components/mainpage/slider/scripts/owl.carousel.min.js",true);
    Asset::getInstance()->addJs("/local/templates/universe_s1/onlineservice_addons/components/mainpage/slider/scripts/scripts.js",true);

endif;
if ($APPLICATION->GetCurPage(false) === '/help/brands/'):

endif;

if ($APPLICATION->GetCurPage(false) === '/help/how-to-request/'):
    Asset::getInstance()->addCss("/local/templates/onlineservice-custom-template/components/how-to-request/styles/styles.css");
endif;
if ($APPLICATION->GetCurPage(false) === '/company/contacts/'):
    Asset::getInstance()->addCss("/local/templates/onlineservice-custom-template/components/contacts/map/styles/styles.css");
    Asset::getInstance()->addCss("/local/templates/onlineservice-custom-template/components/contacts/accordion/styles/styles.css");
    Asset::getInstance()->addCss("/local/templates/onlineservice-custom-template/components/contacts/requisites/styles/styles.css");

    Asset::getInstance()->addJs("/local/templates/onlineservice-custom-template/components/contacts/accordion/scripts/script.js",true);
endif;
?>
