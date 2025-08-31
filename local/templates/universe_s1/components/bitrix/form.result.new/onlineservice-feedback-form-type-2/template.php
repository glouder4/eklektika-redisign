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
<div style="background-color: #D6C2F3; position: relative;">
    <div class="feedback__form--background__elements">
        <div class="feedback__form--background__element">
            <img src="/local/templates/universe_s1/components/bitrix/form.result.new/onlineservice-feedback-form-type-3/assets/bg-item-mobile.png" alt="">
        </div>
        <div class="feedback__form--background__element desktop">
            <img src="/local/templates/universe_s1/components/bitrix/form.result.new/onlineservice-feedback-form-type-3/assets/bg-item.png" alt="">
        </div>
    </div>
    <div class="container">
        <div class="feedback__form">
            <div class="feedback__form--form_wrapper">
                <div class="feedback__form--form_title">
                    <span>Закажите подробную<br/>консультацию</span>
                </div>
                <form class="feedback__form--form" id="feedback__form--form">
                    <?=bitrix_sessid_post()?>
                    <input type="hidden" name="webform_id" value="<?=$arParams['WEB_FORM_ID'];?>">
                    <div class="feedback__form--form--item">
                        <input class="feedback__form--form--item_name" name="fio" type="text" placeholder="Имя" />
                    </div>
                    <div class="feedback__form--form--item">
                        <input class="feedback__form--form--item_email" name="email" type="text" placeholder="E-mail" />
                    </div>
                    <div class="feedback__form--form--item">
                        <input class="feedback__form--form--item_tel" name="phone" type="text" placeholder="Телефон" />
                    </div>
                    <div class="feedback__form--form--item textarea">
                        <textarea class="feedback__form--form--item_message" name="message" placeholder="Ваше сообщение"></textarea>
                    </div>

                    <div class="feedback__form--form--action_item">
                        <button type="submit" id="feedback__form-SendForm" class="feedback__form--form--action_item--send_form">Заказать консультацию</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('feedback__form--form').onsubmit = function (e) {
            e.preventDefault();

            let form = this;
            let requiredFields = ['fio', 'phone','webform_id'];
            let valid = true;
            requiredFields.forEach(function (name) {
                let el = form.elements[name];
                if (!el || !el.value.trim()) {
                    el.style.borderColor = 'red';
                    valid = false;
                } else {
                    el.style.borderColor = '';
                }
            });
            if (!valid) return;

            let formData = new FormData(form);
            formData.append('sessid', BX.message && BX.message('bitrix_sessid') ? BX.message('bitrix_sessid') : (form.sessid ? form.sessid.value : ''));
            formData.append('submit', 'Y');

            fetch('/local/templates/onlineservice-custom-template/ajax/feedback__form--form.php', {
                method: 'POST',
                body: formData
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        form.reset();
                    } else {
                        alert(data.error || 'Ошибка отправки');
                    }
                })
                .catch(() => alert('Ошибка соединения'));
        };
    })
</script>