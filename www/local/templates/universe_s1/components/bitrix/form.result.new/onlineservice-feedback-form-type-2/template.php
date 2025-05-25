<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\FileHelper;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('intec.core'))
    return;

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

?>
<div style="background-color: #F5AAC1; position: relative;">
    <div class="feedback__form--background__elements">
        <div class="feedback__form--background__element">
            <img src="/local/templates/onlineservice-custom-template/components/brands/feedback-form/assets/bg-el.png" alt="">
        </div>
        <div class="feedback__form--background__element desktop">
            <img src="/local/templates/onlineservice-custom-template/components/brands/feedback-form/assets/bg-el-desktop.png" alt="">
        </div>
    </div>
    <div class="container">
        <div class="feedback__form">
            <div class="feedback__form--form_wrapper">
                <div class="feedback__form--form_title">
                    <span>Оставьте заявку и&nbsp;с&nbsp;вами свяжутся в&nbsp;течении пары минут</span>
                </div>
                <div class="feedback__form--form">
                    <div class="feedback__form--form--item">
                        <input class="feedback__form--form--item_name" type="text" placeholder="Имя" />
                    </div>
                    <div class="feedback__form--form--item">
                        <input class="feedback__form--form--item_email" type="text" placeholder="E-mail" />
                    </div>
                    <div class="feedback__form--form--item">
                        <input class="feedback__form--form--item_tel" type="text" placeholder="Телефон" />
                    </div>
                    <div class="feedback__form--form--item textarea">
                        <textarea class="feedback__form--form--item_message" placeholder="Ваше сообщение"></textarea>
                    </div>

                    <div class="feedback__form--form--action_item">
                        <button id="feedback__form-SendForm" class="feedback__form--form--action_item--send_form">Оставить заявку</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {

    })
</script>