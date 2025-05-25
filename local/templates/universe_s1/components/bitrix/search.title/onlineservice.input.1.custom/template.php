<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent $component
 */

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = &$arResult['VISUAL'];

?>
<div id="<?= $sTemplateId ?>">
    <?= Html::beginForm($arResult['FORM_ACTION'], 'get', [
        'class' => 'search-title-form header__search'
    ]) ?>
    <?= Html::textInput('q', null, [
        'class' => [
            'search-title-input header__search-input'
        ],
        'id' => $arVisual['INPUT']['ID'],
        'maxlength' => 100,
        'autocomplete' => 'off',
        'placeholder' => 'Поиск'
    ]) ?>
    <button class="header__search-btn">
                        <span class="header__icon header__icon--search">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M11.2428 10.4946L15 14.2302L14.2255 15L10.4601 11.2572C9.18861 12.2994 7.56132 12.8132 5.91785 12.6915C4.27437 12.5697 2.74168 11.8218 1.63963 10.6038C0.53758 9.38584 -0.0486902 7.79187 0.00316912 6.15456C0.0550284 4.51724 0.74101 2.96308 1.91796 1.81641C3.09491 0.66974 4.67189 0.0191525 6.31982 0.000416158C7.96774 -0.0183202 9.55928 0.596242 10.7623 1.71585C11.9653 2.83546 12.6869 4.37362 12.7764 6.00933C12.866 7.64504 12.3165 9.25192 11.2428 10.4946ZM11.6903 6.35423C11.6903 4.95782 11.1322 3.6186 10.1388 2.63119C9.14545 1.64378 7.79814 1.08906 6.39329 1.08906C4.98843 1.08906 3.64112 1.64378 2.64774 2.63119C1.65436 3.6186 1.09628 4.95782 1.09628 6.35423C1.09628 7.75064 1.65436 9.08985 2.64774 10.0773C3.64112 11.0647 4.98843 11.6194 6.39329 11.6194C7.79814 11.6194 9.14545 11.0647 10.1388 10.0773C11.1322 9.08985 11.6903 7.75064 11.6903 6.35423Z" fill="#744A9E"/>
                            </svg>
                        </span>
    </button>
    <?= Html::endForm() ?>

    <?php if ($arVisual['TIPS']['USE']) { ?>
        <script type="text/javascript">
            template.load(function () {
                var $ = this.getLibrary('$');
                var component = new JCTitleSearch(<?= JavaScript::toObject([
                    'AJAX_PAGE' => POST_FORM_ACTION_URI,
                    'CONTAINER_ID' => $sTemplateId,
                    'INPUT_ID' => $arVisual['INPUT']['ID'],
                    'MIN_QUERY_LEN' => 2
                ]) ?>);

                component.onFocusLost = function () {};

                component.adjustResultNode = function () {
                    var self = component;

                    if(!(BX.type.isElementNode(self.RESULT) && BX.type.isElementNode(self.CONTAINER)))
                        return { top: 0, right: 0, bottom: 0, left: 0, width: 0, height: 0 };

                    self.RESULT.style.position = 'absolute';
                    self.RESULT.style.left = '';
                    self.RESULT.style.top = '';
                    self.RESULT.style.width = '';

                    var position = BX.pos(self.CONTAINER);
                    var width = self.RESULT.clientWidth;

                    if (position.width > width) {
                        self.RESULT.style.left = (position.left + ((position.width - width) / 2)) + 'px';
                    } else {
                        if ((width + position.left) > document.documentElement.clientWidth) {
                            self.RESULT.style.left = (document.documentElement.clientWidth - width) / 2 + 'px';
                        } else {
                            self.RESULT.style.left = position.left + 'px';
                        }
                    }

                    self.RESULT.style.top = (position.bottom + 14) + 'px';
                    self.RESULT.style.width = self.RESULT.clientWidth + 'px';

                    return position;
                };

                $(document).on('click', function (event) {
                    var target = $(event.target);

                    if (!target.isOrClosest([component.CONTAINER, component.RESULT]))
                        component.RESULT.style.display = 'none';
                });
            }, {
                'name': '[Component] bitrix:search.title (input.1)'
            });
        </script>
    <?php } ?>
</div>