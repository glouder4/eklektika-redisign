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
<div style="background-color: #FCC233;" id="<?= $sTemplateId ?>">
    <div class="container">
        <div class="feedback-banner">
            <div class="feedback-banner--bg-elements">
                <img class="feedback-banner--bg-elements--item" src="/local/templates/universe_s1/components/bitrix/form.result.new/onlineservice-feedback-form-type-1/assets/bg-item.png" alt="">
                <img class="feedback-banner--bg-elements--item mobile" src="/local/templates/universe_s1/components/bitrix/form.result.new/onlineservice-feedback-form-type-1/assets/bg-item-mobile.png" alt="">
            </div>
            <div class="feedback-banner-data">
                <div class="feedback-banner-data--title">
                    <span>
                        Оставьте заявку и&nbsp;с&nbsp;вами свяжутся в&nbsp;течении пары минут
                    </span>
                </div>
                <button class="feedback-banner-data--action-btn" data-action="onlineservice-action.forms.call.open">
                    Оставить заявку
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('[data-action="onlineservice-action.forms.call.open"]').click(function(){
            $('.header__callback-btn[data-action="forms.call.open"]').trigger('click');
        })
    })
</script>