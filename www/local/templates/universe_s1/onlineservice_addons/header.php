<?php
    use Bitrix\Main\Page\Asset;
    Asset::getInstance()->addCss("/local/templates/universe_s1/onlineservice_addons/styles/template.css");
    Asset::getInstance()->addCss("/local/templates/universe_s1/onlineservice_addons/styles/header.css");


    Asset::getInstance()->addCss("/local/templates/universe_s1/onlineservice_addons/styles/footer.css");

    Asset::getInstance()->addJs("/local/templates/universe_s1/onlineservice_addons/scripts/template.js",true);
    Asset::getInstance()->addJs("/local/templates/universe_s1/onlineservice_addons/scripts/header.js",true);

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
?>
