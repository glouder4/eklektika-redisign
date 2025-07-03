<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="products-card-section-list">
    <?foreach($arResult["ITEMS"] as $arItem):?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="products-card-section--list_item">
            <div class="products-card-section--list_item--image--wrapper">
                <div class="products-card-section--list_item--image">
                    <img
                            class="image"
                            src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                            width="<?=$arItem["PREVIEW_PICTURE"]["WIDTH"]?>"
                            height="<?=$arItem["PREVIEW_PICTURE"]["HEIGHT"]?>"
                            alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>"
                            title="<?=$arItem["PREVIEW_PICTURE"]["TITLE"]?>"
                    />
                </div>
                <div class="products-card-section--list_item--image-action">
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
                        <circle cx="25" cy="25" r="25" fill="white"/>
                        <path d="M22.8182 30.1166L27 25.0583L22.8182 20" stroke="#222222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
            <div class="products-card-section--list_item--name">
                <span class="name"><?echo $arItem["NAME"]?></span>
            </div>
        </a>

    <?endforeach;?>

    <?php
        if( count($arResult["ITEMS"]) == 0 ){
            ?>
            <div class="products-card-section-list">
                <a href="/services/s_dtf_pechat/" rel="nofollow" class="products-card-section--list_item">
                    <div class="products-card-section--list_item--image--wrapper">
                        <div class="products-card-section--list_item--image">
                            <img src="/local/templates/onlineservice-custom-template/services/assets/blue/product1.png" alt="Дизайн студия" class="image">
                        </div>
                        <div class="products-card-section--list_item--image-action">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
                                <circle cx="25" cy="25" r="25" fill="white"/>
                                <path d="M22.8182 30.1166L27 25.0583L22.8182 20" stroke="#222222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="products-card-section--list_item--name">
                        <span class="name">DTF печать</span>
                    </div>
                </a>
                <a href="/services/s_tampopechat/" class="products-card-section--list_item">
                    <div class="products-card-section--list_item--image--wrapper">
                        <div class="products-card-section--list_item--image">
                            <img src="/local/templates/onlineservice-custom-template/services/assets/blue/product2.png" alt="Дизайн студия" class="image">
                        </div>
                        <div class="products-card-section--list_item--image-action">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
                                <circle cx="25" cy="25" r="25" fill="white"/>
                                <path d="M22.8182 30.1166L27 25.0583L22.8182 20" stroke="#222222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="products-card-section--list_item--name">
                        <span class="name">Тампопечать</span>
                    </div>
                </a>
                <a href="/services/s_lazernaya-gravirovka/" class="products-card-section--list_item">
                    <div class="products-card-section--list_item--image--wrapper">
                        <div class="products-card-section--list_item--image">
                            <img src="/local/templates/onlineservice-custom-template/services/assets/blue/product3.png" alt="Лазерная гравировка" class="image">
                        </div>
                        <div class="products-card-section--list_item--image-action">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
                                <circle cx="25" cy="25" r="25" fill="white"/>
                                <path d="M22.8182 30.1166L27 25.0583L22.8182 20" stroke="#222222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="products-card-section--list_item--name">
                        <span class="name">Лазерная гравировка</span>
                    </div>
                </a>
                <a href="/services/s_polnocvetnaya-uf-pechat/" class="products-card-section--list_item">
                    <div class="products-card-section--list_item--image--wrapper">
                        <div class="products-card-section--list_item--image">
                            <img src="/local/templates/onlineservice-custom-template/services/assets/blue/product4.png" alt="УФ печать" class="image">
                        </div>
                        <div class="products-card-section--list_item--image-action">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
                                <circle cx="25" cy="25" r="25" fill="white"/>
                                <path d="M22.8182 30.1166L27 25.0583L22.8182 20" stroke="#222222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="products-card-section--list_item--name">
                        <span class="name">УФ печать</span>
                    </div>
                </a>
                <a href="/services/s_tisnenie/" class="products-card-section--list_item">
                    <div class="products-card-section--list_item--image--wrapper">
                        <div class="products-card-section--list_item--image">
                            <img src="/local/templates/onlineservice-custom-template/services/assets/blue/product5.png" alt="Тиснение" class="image">
                        </div>
                        <div class="products-card-section--list_item--image-action">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
                                <circle cx="25" cy="25" r="25" fill="white"/>
                                <path d="M22.8182 30.1166L27 25.0583L22.8182 20" stroke="#222222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="products-card-section--list_item--name">
                        <span class="name">Тиснение</span>
                    </div>
                </a>
                <a href="/services/s_sublimacionnaya_pechat/" class="products-card-section--list_item">
                    <div class="products-card-section--list_item--image--wrapper">
                        <div class="products-card-section--list_item--image">
                            <img src="/local/templates/onlineservice-custom-template/services/assets/blue/product6.png" alt="Сублимационная печать" class="image">
                        </div>
                        <div class="products-card-section--list_item--image-action">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
                                <circle cx="25" cy="25" r="25" fill="white"/>
                                <path d="M22.8182 30.1166L27 25.0583L22.8182 20" stroke="#222222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="products-card-section--list_item--name">
                        <span class="name">Сублимационная печать</span>
                    </div>
                </a>
            </div>
            <?php
        }
    ?>
</div>
