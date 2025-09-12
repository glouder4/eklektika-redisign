<?php include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

/**
 * @var CMain $APPLICATION
 */

CHTTP::SetStatus('404 Not Found');

@define('ERROR_404', 'Y');

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

$APPLICATION->SetTitle('Политика обработки персональных данных');

?>
    <div class="intec-page intec-page-404 intec-content">
        <div class="intec-content-wrapper">
            <div class="container">
                <?
                $APPLICATION->IncludeFile(SITE_DIR."include/politika-konfidencialnosty.php", array(), array(
                        "MODE" => "html",
                        "NAME" => "",
                    )
                );
                ?>
            </div>
        </div>
    </div>
<?php require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php') ?>