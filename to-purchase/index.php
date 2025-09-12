<?
use Bitrix\Main\Page\Asset;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("К покупкам");

Asset::getInstance()->addCss("/local/templates/onlineservice-custom-template/components/to-purchase/styles/style.css");
?>

<div class="container">
    <div class="page-description">
        <p>Откройте для себя мир YO!Merch!<br/>
            Уникальную продукцию теперь можно приобрести в Интернет магазинах!</p>
    </div>

    <div class="purchase-cards-list--wrapper">
        <div class="purchase-cards-list">
            <a href="#" class="purchase-card">
                <img src="/local/templates/onlineservice-custom-template/components/to-purchase/assets/ozon.png" alt="Ozon">
            </a>
        </div>
    </div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>