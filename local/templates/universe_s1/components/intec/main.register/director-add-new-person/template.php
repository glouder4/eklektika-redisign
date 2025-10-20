<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\FileHelper;

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if ($arResult['SHOW_SMS_FIELD'] == true)
    CJSCore::Init('phone_auth');

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this, true));
$arSvg = [
    'SHOW_PASSWORD' => FileHelper::getFileData(__DIR__.'/svg/eye_open.svg'),
    'HIDE_PASSWORD' => FileHelper::getFileData(__DIR__.'/svg/eye_close.svg')
];
$sPrefix = 'C_MAIN_REGISTER_TEMPLATE_2_TEMPLATE_';

?>
<div class="notification-overlay" id="notificationOverlay">
    <div class="notification" id="notification">
        <div class="notification-icon" id="notificationIcon">
            <div class="loading-spinner" id="loadingSpinner"></div>
            <span id="statusIcon" style="display: none;">✓</span>
        </div>
        <h3 class="notification-title" id="notificationTitle">Обработка запроса</h3>
        <p class="notification-message" id="notificationMessage">Пожалуйста, подождите...</p>
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>
    </div>
</div>

<div class="container">
<?php
    // Проверка на наличие компании
    if ($arResult['SHOW_ERROR_NO_HEAD_COMPANY']):
?>
    <div class="ns-bitrix c-main-register c-main-register-template-2">
        <div class="registration-error-block">
            <div class="registration-error-icon">
                <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="32" cy="32" r="32" fill="#FFEBEE"/>
                    <path d="M32 20v16M32 42v2" stroke="#f44336" stroke-width="4" stroke-linecap="round"/>
                </svg>
            </div>
            <h2 class="registration-error-title">Ошибка доступа</h2>
            <p class="registration-error-description">
                Для добавления сотрудника компании необходимо перейти через профиль компании.
            </p>
            <div class="registration-error-actions">
                <a href="/personal/profile/" class="registration-error-button">Перейти в личный кабинет</a>
            </div>
        </div>
    </div>
</div>

<?php
    else:
?>
<div id="<?= $sTemplateId ?>" class="ns-bitrix c-main-register c-main-register-template-2">	
    <script>
        BX.message({
            phone_auth_resend: '<?= GetMessageJS($sPrefix.'phone_auth_resend') ?>',
            phone_auth_resend_link: '<?= GetMessageJS($sPrefix.'phone_auth_resend_link') ?>'
        });
    </script>

    <?php if (count($arResult['ERRORS']) > 0) {
        foreach ($arResult['ERRORS'] as $key => $error)
            if (intval($key) == 0 && $key !== 0)
                $arResult['ERRORS'][$key] = str_replace('#FIELD_NAME#', '&quot;' . Loc::getMessage($sPrefix.'REGISTER_FIELD_' . $key) . '&quot;', $error);

        echo "<div style='color: red;' class='registration-errors-wrapper'>";
        echo implode('<br>', array_map('htmlspecialchars_decode', $arResult['ERRORS']));
        echo "</div><br><br>";
    } else if ($arResult['USE_EMAIL_CONFIRMATION'] === 'Y') { ?>
        <?= Html::tag('p', Loc::getMessage($sPrefix.'REGISTER_EMAIL_WILL_BE_SENT'), []) ?>
    <?php } ?>
    <?php
    $arServices = [];
    ?>
    <?php if ($arResult['SHOW_SMS_FIELD']) { ?>
        <form class="main-register-form intec-ui-form" method="post" action="<?= POST_FORM_ACTION_URI ?>" name="regform">
            <?php if ($arResult['BACKURL'] <> '') { ?>
                <?=Html::hiddenInput('backurl', $arResult['BACKURL'])?>
            <?php } ?>
            <input type="hidden" name="SIGNED_DATA" value="<?= htmlspecialcharsbx($arResult['SIGNED_DATA']) ?>"/>
            <div class="intec-ui-form-fields">
                <div class="intec-ui-form-field intec-ui-form-field-required">
                    <label class="intec-ui-form-field-title" for="SMS_CODE_POPUP_2">
                        <?= Loc::getMessage($sPrefix.'SMS') ?>
                    </label>
                    <div class="intec-ui-form-field-content">
                        <?= Html::input('text', 'SMS_CODE', htmlspecialcharsbx($arResult['SMS_CODE']), [
                            'class' => [
                                'inputtext',
                                'intec-ui' => [
                                    '',
                                    'control-input',
                                    'mod-block',
                                    'mod-round-3',
                                    'size-4'
                                ]
                            ],
                            'size' => 30,
                            'autocomplete' => 'off',
                            'data' => [
                                'role' => 'input'
                            ],
                            'id' => 'SMS_CODE_POPUP_2'
                        ]); ?>
                    </div>
                </div>
                <div class="intec-ui-form-field">
                    <div id="bx_main_register_error" class="intec-ui intec-ui-control-alert intec-ui-scheme-current intec-ui-m-b-20" style="display:none"><? ShowError('error') ?></div>
                    <div id="bx_main_register_resend" class="intec-ui intec-ui-control-alert intec-ui-scheme-current intec-ui-m-b-20"></div>
                </div>
            </div>
            <br><br>
        </form>
        <script>
            new BX.PhoneAuth({
                containerId: 'bx_main_register_resend',
                errorContainerId: 'bx_main_register_error',
                interval: <?= $arResult['PHONE_CODE_RESEND_INTERVAL'] ?>,
                data:
                    <?= CUtil::PhpToJSObject([
                        'signedData' => $arResult['SIGNED_DATA'],
                    ]) ?>,
                onError:
                    function (response) {
                        var errorDiv = BX('bx_main_register_error');
                        var errorNode = BX.findChildByClassName(errorDiv, 'errortext');
                        errorNode.innerHTML = '';
                        for (var i = 0; i < response.errors.length; i++) {
                            errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br>';
                        }
                        errorDiv.style.display = '';
                    }
            });
        </script>
    <?php } else { ?>
        <form class="main-register-form intec-ui-form" method="post" action="<?= $arParams['FORM_ACTION']; ?>" name="regform" enctype="multipart/form-data" id="registrationForm">
            <?php $valueType =  $arResult["VALUES"]["UF_TYPE"] ?? null;
            ?>
            <div class="c-main-register__tabs" style="display: none">
                <label class="intec-ui intec-ui-control-radiobox">
                    <input
                            name="UF_TYPE"
                            type="radio"
                            name="radio-scheme-simple"
                        <?=$valueType == "6" || !$valueType ? "checked" : ""?>
                            value="6"
                    >
                    <span class="intec-ui-part-selector"></span>
                    <span class="intec-ui-part-content">Рекламный агент</span>
                </label>
            </div>
            <div>
                Компания Yo Merch сотрудничает с Рекламными и Event агентствами,<br/>интернет магазинами, типографиями и производственными компаниями
            </div>
            <br><br>
            <?php if ($arResult['BACKURL'] <> '') { ?>
                <input type="hidden" name="backurl" value="<?= $arResult['BACKURL'] ?>"/>
            <?php } ?>
            <input type="hidden" name="head_company_id" value="<?=$arResult['HEAD_COMPANY_B24_ID'];?>" required/>
            <input type="hidden" name="head_company_element_id" value="<?=$arResult['HEAD_COMPANY_ID'];?>" required/>


            <div id="diler-registration-fields" class="intec-ui-form-fields " style="margin-bottom: 20px">
                <div class="intec-grid intec-grid-i-18 intec-grid-wrap">

                    <?php
                    foreach ($arResult['SORTED_FIELDS'] as $key => $FIELDS_BLOCK){
                        ?>
                        <div class="block-fields" data-index="<?=$key;?>" style="display: <?=($key == 0) ? "block" : "block";?>; width: 75%;">
                            <?php
                            foreach ($FIELDS_BLOCK as $FIELD => $arUserField){
                                if (substr($FIELD, 0, 3) === 'UF_'){
                                    ?>

                                    <?php if ($arUserField["USER_TYPE_ID"] == "file") {?>
                                        <div class="parent--wrapper intec-ui-form-field <?= $arResult['REQUIRED_FIELDS_FLAGS'][$FIELD] == 'Y' || ( isset($arUserField["MANDATORY"]) && $arUserField["MANDATORY"] == "Y" )    ? 'os_required' : '' ?> <?= $arResult['REQUIRED_FIELDS_FLAGS'][$FIELD] == 'Y' ? 'intec-ui-form-field-required' : '' ?>" data-name="<?=$arUserField["FIELD_NAME"]?>">
                                            <label class="intec-ui-form-field-title" for="REGISTER_<?=$arUserField["FIELD_NAME"]?>">
                                                <?= $arUserField["EDIT_FORM_LABEL"] ?>:
                                                <?php if ($arUserField["MANDATORY"] == "Y") {?>
                                                    <span class="starrequired">*</span>
                                                <?php } ?>
                                            </label>
                                            <div class="intec-ui-form-field-content">
                                                <input
                                                        type="file"
                                                        id="REGISTER_<?=$arUserField["FIELD_NAME"]?>"
                                                        class="intec-ui intec-ui-control-input intec-ui-mod-block intec-ui-size-4"
                                                        name="<?=$arUserField["FIELD_NAME"]?>"
                                                        data-role="input">
                                            </div>
                                            <div class="ui-error-message" style="display: none;"><span>Поле обязательно для заполнения</span></div>
                                        </div>
                                    <?php }
                                    elseif ($arUserField["USER_TYPE_ID"] == "boolean") {?>
                                        <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current" data-name="<?=$arUserField["FIELD_NAME"]?>">
                                            <input type="checkbox" name="<?=$arUserField["FIELD_NAME"]?>">
                                            <span class="intec-ui-part-selector"></span>
                                            <span class="intec-ui-part-content"><?= $arUserField["EDIT_FORM_LABEL"] ?></span>
                                        </label>
                                    <?php } else {?>
                                        <div class="parent--wrapper intec-ui-form-field <?= $arResult['REQUIRED_FIELDS_FLAGS'][$FIELD] == 'Y' || ( isset($arUserField["MANDATORY"]) && $arUserField["MANDATORY"] == "Y" )    ? 'os_required' : '' ?> <?= $arResult['REQUIRED_FIELDS_FLAGS'][$FIELD] == 'Y' ? 'intec-ui-form-field-required' : '' ?>" data-name="<?=$arUserField["FIELD_NAME"]?>">
                                            <label class="intec-ui-form-field-title" for="REGISTER_<?=$arUserField["FIELD_NAME"]?>">
                                                <?= $arUserField["EDIT_FORM_LABEL"] ?>:
                                                <?php if ($arUserField["MANDATORY"] == "Y") {?>
                                                    <span class="starrequired">*</span>
                                                <?php } ?>
                                            </label>
                                            <div class="intec-ui-form-field-content">
                                                <input
                                                        type="text"
                                                        id="REGISTER_<?=$arUserField["FIELD_NAME"]?>"
                                                        class="intec-ui intec-ui-control-input intec-ui-mod-block intec-ui-size-4"
                                                        name="<?=$arUserField["FIELD_NAME"]?>"
                                                        value="<?=$arResult["VALUES"][$arUserField["FIELD_NAME"]]?>"
                                                        data-role="input">
                                            </div>
                                            <div class="ui-error-message" style="display: none;"><span>Поле обязательно для заполнения</span></div>
                                        </div>
                                    <?php }?>

                                    <?php
                                }
                                else{
                                    ?>
                                    <?php if ($FIELD == 'PERSONAL_PHOTO' || $FIELD == 'WORK_LOGO') continue; ?>
                                    <?php if ($FIELD == 'AUTO_TIME_ZONE' && $arResult['TIME_ZONE_ENABLED'] == true) { ?>
                                        <div class="intec-ui-form-field <?= $arResult['REQUIRED_FIELDS_FLAGS'][$FIELD] == 'Y' ? 'intec-ui-form-field-required' : '' ?>">
                                            <label class="intec-ui-form-field-title" for="REGISTER_AUTO_TIME_ZONE">
                                                <?= Loc::getMessage($sPrefix.'main_profile_time_zones_auto') ?>
                                            </label>
                                            <div class="intec-ui-form-field-content">
                                                <?= Html::beginTag('select', [
                                                    'class' => [
                                                        'intec-ui' => [
                                                            '',
                                                            'control-input',
                                                            'mod-block',
                                                            'size-4'
                                                        ]
                                                    ],
                                                    'name' => 'REGISTER[AUTO_TIME_ZONE]',
                                                    'onchange' => 'this.form.elements[\'REGISTER[TIME_ZONE]\'].disabled=(this.value != \'N\')',
                                                    'data' => [
                                                        'role' => 'input'
                                                    ],
                                                    'id' => 'REGISTER_AUTO_TIME_ZONE'
                                                ]) ?>
                                                <option value="">
                                                    <?= Loc::getMessage($sPrefix.'main_profile_time_zones_auto_def') ?>
                                                </option>
                                                <option value="Y"<?= $arResult['VALUES'][$FIELD] == 'Y' ? ' selected="selected"' : '' ?>>
                                                    <?= Loc::getMessage($sPrefix.'main_profile_time_zones_auto_yes') ?>
                                                </option>
                                                <option value="N"<?= $arResult['VALUES'][$FIELD] == 'N' ? ' selected="selected"' : '' ?>>
                                                    <?= Loc::getMessage($sPrefix.'main_profile_time_zones_auto_no') ?>
                                                </option>
                                                <?= Html::endTag('select') ?>
                                            </div>
                                        </div>
                                        <div class="intec-ui-form-field">
                                            <label class="intec-ui-form-field-title" for="REGISTER_TIME_ZONE">
                                                <?= Loc::getMessage($sPrefix.'main_profile_time_zones_zones') ?>
                                            </label>
                                            <div class="intec-ui-form-field-content">
                                                <?= Html::beginTag('select', [
                                                    'class' => [
                                                        'intec-ui' => [
                                                            '',
                                                            'control-input',
                                                            'mod-block',
                                                            'size-4'
                                                        ]
                                                    ],
                                                    'name' => 'REGISTER[TIME_ZONE]',
                                                    'data' => [
                                                        'role' => 'input'
                                                    ],
                                                    'disabled' => !isset($_REQUEST['REGISTER']['TIME_ZONE']) ? 'disabled' : null,
                                                    'id' => 'REGISTER_TIME_ZONE'
                                                ]) ?>
                                                <?php foreach ($arResult['TIME_ZONE_LIST'] as $tz => $tz_name) { ?>
                                                    <?= Html::tag('option', htmlspecialcharsbx($tz_name), [
                                                        'value' => htmlspecialcharsbx($tz),
                                                        'selected' => $arResult['VALUES']['TIME_ZONE'] == $tz ? 'selected' : null
                                                    ]) ?>
                                                <?php } ?>
                                                <?= Html::endTag('select') ?>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="parent--wrapper intec-ui-form-field <?= $arResult['REQUIRED_FIELDS_FLAGS'][$FIELD] == 'Y' || ( isset($arUserField["MANDATORY"]) && $arUserField["MANDATORY"] == "Y" )    ? 'os_required' : '' ?> <?= $arResult['REQUIRED_FIELDS_FLAGS'][$FIELD] == 'Y' ? 'intec-ui-form-field-required' : '' ?>" data-name="<?=$FIELD?>">
                                            <label class="intec-ui-form-field-title" for="REGISTER_<?=$FIELD?>_POPUP2">
                                                <?= Loc::getMessage($sPrefix.'REGISTER_FIELD_' . $FIELD) ?>
                                            </label>
                                            <div class="intec-ui-form-field-content">
                                                <?php switch ($FIELD) {
                                                case 'PASSWORD': ?>
                                                    <?= Html::input(
                                                        'password',
                                                        'REGISTER['.$FIELD.']',
                                                        $arResult['VALUES'][$FIELD],
                                                        [
                                                            'class' => [
                                                                'bx-auth-input',
                                                                'intec-ui' => [
                                                                    '',
                                                                    'control-input',
                                                                    'mod-block',
                                                                    'size-4'
                                                                ]
                                                            ],
                                                            'size' => 30,
                                                            'autocomplete' => 'off',
                                                            'data' => [
                                                                'role' => 'input',
                                                                'code' => 'password'
                                                            ],
                                                            'id' => 'REGISTER_'.$FIELD.'_POPUP2'
                                                        ]) ?>
                                                    <div class="main-register-form-password-icon intec-ui-picture" data-role="password.change" data-action="show" data-active="true" data-target="password">
                                                        <?= $arSvg['SHOW_PASSWORD'] ?>
                                                    </div>
                                                    <div class="main-register-form-password-icon intec-ui-picture" data-role="password.change" data-action="hide" data-active="false" data-target="password">
                                                        <?= $arSvg['HIDE_PASSWORD'] ?>
                                                    </div>
                                                <?php if ($arResult['SECURE_AUTH']) { ?>
                                                    <span class="bx-auth-secure" id="bx_auth_secure" title="<?= Loc::getMessage("C_MAIN_REGISTER_TEMPLATE_2_TEMPLATE_AUTH_SECURE_NOTE") ?>" style="display:none">
                                                                            <div class="bx-auth-secure-icon"></div>
                                                                        </span>
                                                    <noscript>
                                                                            <span class="bx-auth-secure" title="<?= Loc::getMessage("C_MAIN_REGISTER_TEMPLATE_2_TEMPLATE_AUTH_NONSECURE_NOTE") ?>">
                                                                                <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
                                                                            </span>
                                                    </noscript>
                                                    <script type="text/javascript">
                                                        document.getElementById('bx_auth_secure').style.display = 'inline-block';
                                                    </script>
                                                <?php } ?>
                                                <? break;
                                                case 'CONFIRM_PASSWORD': ?>
                                                    <?= Html::input('password', 'REGISTER['.$FIELD.']', $arResult['VALUES'][$FIELD], [
                                                        'class' => [
                                                            'bx-auth-input',
                                                            'intec-ui' => [
                                                                '',
                                                                'control-input',
                                                                'mod-block',
                                                                'size-4'
                                                            ]
                                                        ],
                                                        'size' => 30,
                                                        'autocomplete' => 'off',
                                                        'data' => [
                                                            'role' => 'input',
                                                            'code' => 'confirm_password'
                                                        ],
                                                        'id' => 'REGISTER_'.$FIELD.'_POPUP2'
                                                    ]) ?>
                                                    <?=Html::tag('div', $arSvg['SHOW_PASSWORD'], [
                                                        'class' => [
                                                            'main-register-form-password-icon',
                                                            'intec-ui-picture'
                                                        ],
                                                        'data' => [
                                                            'role' => 'password.change',
                                                            'action' => 'show',
                                                            'active' => 'true',
                                                            'target' => 'confirm_password'
                                                        ]
                                                    ])?>
                                                    <?=Html::tag('div', $arSvg['HIDE_PASSWORD'], [
                                                        'class' => [
                                                            'main-register-form-password-icon',
                                                            'intec-ui-picture'
                                                        ],
                                                        'data' => [
                                                            'role' => 'password.change',
                                                            'action' => 'hide',
                                                            'active' => 'false',
                                                            'target' => 'confirm_password'
                                                        ]
                                                    ])?>
                                                    <? break;
                                                case 'PERSONAL_GENDER': ?>
                                                <?= Html::beginTag('select', [
                                                    'class' => [
                                                        'intec-ui' => [
                                                            '',
                                                            'control-input',
                                                            'mod-block',
                                                            'size-4'
                                                        ]
                                                    ],
                                                    'name' => 'REGISTER['.$FIELD.']',
                                                    'data' => [
                                                        'role' => 'input'
                                                    ],
                                                    'id' => 'REGISTER_'.$FIELD.'_POPUP2'
                                                ]) ?>
                                                    <option value="">
                                                        <?= Loc::getMessage($sPrefix.'USER_DONT_KNOW') ?>
                                                    </option>
                                                    <option value="M"<?= $arResult['VALUES'][$FIELD] == 'M' ? ' selected="selected"' : '' ?>>
                                                        <?= Loc::getMessage($sPrefix.'USER_MALE') ?>
                                                    </option>
                                                    <option value="F"<?= $arResult['VALUES'][$FIELD] == 'F' ? ' selected="selected"' : '' ?>>
                                                        <?= Loc::getMessage($sPrefix.'USER_FEMALE') ?>
                                                    </option>
                                                <?= Html::endTag('select') ?>
                                                <? break;
                                                case 'PERSONAL_COUNTRY':
                                                case 'WORK_COUNTRY': ?>
                                                    <?= Html::beginTag('select', [
                                                        'class' => [
                                                            'intec-ui' => [
                                                                '',
                                                                'control-input',
                                                                'mod-block',
                                                                'size-4'
                                                            ]
                                                        ],
                                                        'name' => 'REGISTER['.$FIELD.']',
                                                        'data' => [
                                                            'role' => 'input'
                                                        ],
                                                        'id' => 'REGISTER_'.$FIELD.'_POPUP2'
                                                    ]) ?>
                                                    <?php foreach ($arResult['COUNTRIES']['reference_id'] as $key => $value) { ?>
                                                        <?= Html::tag('option', $arResult['COUNTRIES']['reference'][$key], [
                                                            'value' => $value,
                                                            'selected' => $value == $arResult['VALUES'][$FIELD] ? 'selected' : null
                                                        ]) ?>
                                                    <? } ?>
                                                    <?= Html::endTag('select') ?>
                                                    <? break;
                                                case 'PERSONAL_NOTES':
                                                case 'WORK_NOTES': ?>
                                                    <?= Html::textarea('REGISTER['.$FIELD.']', $arResult['VALUES'][$FIELD], [
                                                        'class' => [
                                                            'intec-ui' => [
                                                                '',
                                                                'control-input',
                                                                'mod-block'
                                                            ]
                                                        ],
                                                        'cols' => 30,
                                                        'rows' => 5,
                                                        'data' => [
                                                            'role' => 'input'
                                                        ],
                                                        'id' => 'REGISTER_'.$FIELD.'_POPUP2'
                                                    ]) ?>
                                                    <? break;
                                                default: ?>
                                                <?php
                                                //if( isset($arResult['VALUES'][$FIELD]) ):                                                             ?>
                                                <?= Html::input('text', 'REGISTER['.$FIELD.']', $arResult['VALUES'][$FIELD], [
                                                    'class' => [
                                                        'date-picker',
                                                        'intec-ui' => [
                                                            '',
                                                            'control-input',
                                                            'mod-block',
                                                            'size-4'
                                                        ]
                                                    ],
                                                    'size' => 30,
                                                    'placeholder' => $FIELD == 'PERSONAL_BIRTHDAY' ? $arResult['DATE_FORMAT'] : null,
                                                    'data' => [
                                                        'role' => 'input'
                                                    ],
                                                    'id' => 'REGISTER_'.$FIELD.'_POPUP2'
                                                ]) ?>
                                                <?php
                                                //endif;
                                                ?>
                                                <?php if ($FIELD == 'PERSONAL_BIRTHDAY') { ?>
                                                    <div class="main-register-form-calendar-icon">
                                                        <?php $APPLICATION->IncludeComponent(
                                                            'bitrix:main.calendar',
                                                            '', [
                                                            'SHOW_INPUT' => 'N',
                                                            'FORM_NAME' => 'regform',
                                                            'INPUT_NAME' => 'REGISTER[PERSONAL_BIRTHDAY]',
                                                            'SHOW_TIME' => 'N'
                                                        ],
                                                            null,[
                                                                'HIDE_ICONS' => 'Y'
                                                            ]
                                                        ); ?>
                                                    </div>
                                                <?php } ?>
                                                <?php } ?>
                                            </div>
                                            <div class="ui-error-message" style="display: none;"><span>Поле обязательно для заполнения</span></div>
                                        </div>
                                    <?php } ?>
                                    <?php
                                }
                            }

                            if( $key + 1 == count($arResult['SORTED_FIELDS']) ){
                                if ($arResult['USE_CAPTCHA'] == 'Y') { ?>
                                    <div class="intec-ui-form-field intec-ui-form-field-required">
                                        <div class="intec-ui-form-field-title" for="">
                                            <?= Loc::getMessage($sPrefix.'REGISTER_CAPTCHA_TITLE') ?>
                                        </div>
                                        <div class="intec-ui-form-field-content">
                                            <div class="intec-ui-m-b-10">
                                                <?=Html::hiddenInput('captcha_sid', $arResult['CAPTCHA_CODE']);?>
                                                <?=Html::img(
                                                    '/bitrix/tools/captcha.php?captcha_sid='.$arResult["CAPTCHA_CODE"], [
                                                        'width' => 180,
                                                        'height' => 40,
                                                        'alt' => 'CAPTCHA'
                                                    ]
                                                );?>
                                            </div>
                                            <div>
                                                <label class="intec-ui-form-field-title" for="captcha_word_popup_2">
                                                    <?= Loc::getMessage($sPrefix.'REGISTER_CAPTCHA_PROMT');?>:
                                                </label>
                                                <div class="intec-ui-form-field-content">
                                                    <?=Html::textInput('captcha_word', '', [
                                                        'class' => [
                                                            'intec-ui' => [
                                                                '',
                                                                'control-input',
                                                                'mod-block',
                                                                'size-4'
                                                            ]
                                                        ],
                                                        'maxlength' => 50,
                                                        'id' => 'captcha_word_popup_2'
                                                    ]);?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }

                                ?>
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                    ?>

                </div>

                <div id="diler-registration-fields-controls">
                    <!--<button id="prev-step" type="button" disabled="true" class="main-register-button intec-ui intec-ui-control-button intec-ui-mod-round-2 intec-ui-size-4">
                        Назад
                    </button>
                    <button id="next-step" type="button" class="main-register-button intec-ui intec-ui-control-button intec-ui-mod-round-2 intec-ui-scheme-current intec-ui-size-4">
                        Далее
                    </button>-->

                    <div id="submitFormBtn">
                        <?= Html::submitInput(Loc::getMessage($sPrefix.'AUTH_REGISTER'), [
                            'class' => [
                                'main-register-button',
                                'intec-ui' => [
                                    '',
                                    'control-button',
                                    'mod-round-2',
                                    'scheme-current',
                                    'size-4'
                                ]
                            ],
                            'name' => 'register_submit_button',
                            'required' => true,
                            'id' => 'registerSubmitBtn'
                        ]);?>
                    </div>
                </div>
            </div>


        </form>
    <?php } ?>
</div>

<div style="display: none;" id="personal-confirmation">
            <div class="personal-confirmation-wrapper">
                <div class="modal-personal-confrmation--title">
                    <h3>Согласие на обработку персональных данных</h3>
                </div>
                <?
                    $APPLICATION->IncludeFile(SITE_DIR."include/soglasiye-na-obrabotku.php", array(), array(
                            "MODE" => "html",
                            "NAME" => "",
                        )
                    );
                ?>
            </div>
            <div id="successFields">
                <label for="personalConfirmation">
                    <input type="checkbox" id="personalConfirmation" />
                    <span class="intec-ui-part-selector"></span>
                    <span class="intec-ui-part-content">Я внимательно ознакомился с политикой конфиденциальности</span>
                </label>
            </div>
        </div>
</div>
<script>

    template.load(function (data) {
        var $ = this.getLibrary('$');
        var root = data.nodes;
        var inputs = $('[data-role="input"]', root);
        var update;
        var buttonPasswordChange = $('[data-role="password.change"]', root);
		$("[name='REGISTER[EMAIL]").on("input", function() {
			$("[name='REGISTER[LOGIN]").val($(this).val());
		});
		let showFieldsForType = function (type) {
			switch(type) {
				case "4":
					$("[data-name=UF_JUR_ADDRESS]").hide();
					$("[data-name=UF_SPERE]").hide();
					$("[data-name=UF_NAME_COMPANY]").hide();
					$("[data-name=UF_INN]").hide();
					$("[data-name=UF_SITE]").hide();
					$("[data-name=UF_SITE]").hide();
					$("[data-name=UF_KPP]").hide();					
					$("[data-name=UF_REQ]").hide();					
					$("[data-name=UF_REQ]").hide();					
					$("[data-name=UF_ADVERSTERING_AGENT]").hide();		
					$("[name=UF_ADVERSTERING_AGENT]").attr("checked" , false);
				break;
				case "5":
					$("[data-name=UF_JUR_ADDRESS]").show();
					$("[data-name=UF_SPERE]").show();
					$("[data-name=UF_NAME_COMPANY]").show();
					$("[data-name=UF_INN]").show();
					$("[data-name=UF_SITE]").show();
					$("[data-name=UF_SITE]").show();
					$("[data-name=UF_KPP]").show();					
					$("[data-name=UF_REQ]").show();					
					$("[data-name=UF_REQ]").show();					
					$("[data-name=UF_ADVERSTERING_AGENT]").hide();		
					$("[name=UF_ADVERSTERING_AGENT]").attr("checked" , false);
				break;
				case "6":
					$("[data-name=UF_JUR_ADDRESS]").show();
					$("[data-name=UF_SPERE]").show();
					$("[data-name=UF_NAME_COMPANY]").show();
					$("[data-name=UF_INN]").show();
					$("[data-name=UF_SITE]").show();
					$("[data-name=UF_SITE]").show();
					$("[data-name=UF_KPP]").show();					
					$("[data-name=UF_REQ]").show();					
					$("[data-name=UF_REQ]").show();					
					//$("[data-name=UF_ADVERSTERING_AGENT]").show();		
					$("[name=UF_ADVERSTERING_AGENT]").attr("checked" , true);
				break;
			}
		}
		
		let type = $("[name=UF_TYPE]:checked").val();
		
		showFieldsForType(type);
		
		$(".c-main-register__tabs input[type=radio]").on("change", function() {
			let type = $(this).val();
			
			showFieldsForType(type);
		});
		
 
		function convert(str){
			return str.replace(/&quot;/g,'"')
			.replace(/&gt;/g,'>')
			.replace(/&lt;/g,'<')
			.replace(/&amp;/g,'&')
		}


		
		/*$("#REGISTER_UF_INN").on("input", function() {
			
			let val = $(this).val();			
			let length = val.length;
			if (length == 10 || length == 12) {
				$.ajax({
					url: "ajax.php",
					data: {
						action: "find_company",
						inn: val
					},
					success: function(data) {
						let company = JSON.parse(data);
						if (company && company.INN) {
							$("#REGISTER_UF_KPP").val(company.KPP);
							$("#REGISTER_UF_KPP").attr("readonly", true);
							$("#REGISTER_UF_NAME_COMPANY").val(convert(company.NAME_COMPANY));
							$("#REGISTER_UF_NAME_COMPANY").attr("readonly", true);
							$("#REGISTER_UF_JUR_ADDRESS").val(company.ADDRESS);
							$("#REGISTER_UF_JUR_ADDRESS").attr("readonly", true);		
							$("#REGISTER_UF_SPERE").val(company.SPHERE);
							$("#REGISTER_UF_SPERE").attr("readonly", true);			
							$("#REGISTER_UF_SITE").val(company.WEBSITE);
							$("#REGISTER_UF_SITE").attr("readonly", true);	
							$("[data-name=UF_REQ]").hide();							
							$("#REGISTER_UF_REQ").val('');
						} else {
							$("#REGISTER_UF_KPP").val('');
							$("#REGISTER_UF_KPP").attr("readonly", false);
							$("#REGISTER_UF_NAME_COMPANY").val('');
							$("#REGISTER_UF_NAME_COMPANY").attr("readonly", false);
							$("#REGISTER_UF_JUR_ADDRESS").val('');
							$("#REGISTER_UF_JUR_ADDRESS").attr("readonly", false);		
							$("#REGISTER_UF_SPERE").val('');
							$("#REGISTER_UF_SPERE").attr("readonly", false);	
							$("#REGISTER_UF_SITE").val('');
							$("#REGISTER_UF_SITE").attr("readonly", false);	
							$("[data-name=UF_REQ]").show();
						}
					}
				});
			}
		});*/
		
        //for adaptation window
        window.dispatchEvent(new Event('resize'));

        buttonPasswordChange.on('click', function () {
            var currentButton = $(this)[0];
            $('[data-target="' + currentButton.dataset.target + '"]', root).each(function () {
                $(this)[0].dataset.active = true;
            });
            currentButton.dataset.active = false;
            var targetInput = $('[data-role="input"][data-code="' + currentButton.dataset.target + '"]', root)[0];

            if (currentButton.dataset.action == 'show') {
                targetInput.setAttribute('type', 'text');
            } else if (currentButton.dataset.action == 'hide') {
                targetInput.setAttribute('type', 'password');
            }
        });

        update = function () {
            var self = $(this);

            if (self.val() != '') {
                self.addClass('completed');
            } else {
                self.removeClass('completed');
            }
        };

        inputs.each(function () {
            update.call(this);
        });

        inputs.on('change', function () {
            update.call(this);
        });

        BX.ajax({
            'method': 'GET',
            'headers': [
                {'name': 'X-Bitrix-Csrf-Token', 'value': BX.bitrix_sessid()}
            ],
            'dataType': 'html',
            'url': '/bitrix/tools/public_session.php?k=' + <?= JavaScript::toObject($_SESSION['fixed_session_id']) ?>,
            'data':  '',
            'lsId': 'sess_expand'
        });
    }, {
        'name': '[Component] bitrix:main.register (template.2)',
        'nodes': <?= JavaScript::toObject('#'.$sTemplateId) ?>,
        'loader': {
            'name': 'lazy'
        }
    });
</script>

<script>
    // Функция валидации обязательных полей
    function validateRequiredFields() {
        let isValid = true;
        let firstErrorField = null;
        
        // Находим все обязательные поля
        const requiredFields = document.querySelectorAll('.parent--wrapper.os_required');
        
        requiredFields.forEach(function(fieldWrapper) {
            const input = fieldWrapper.querySelector('input[data-role="input"], textarea[data-role="input"], select[data-role="input"]');
            const errorMessage = fieldWrapper.querySelector('.ui-error-message');
            
            if (input) {
                const isVisible = fieldWrapper.offsetParent !== null; // Проверяем, видимо ли поле
                let isEmpty = false;
                let errorText = 'Поле обязательно для заполнения';
                
                // Проверяем только видимые поля
                if (isVisible) {
                    // Проверка в зависимости от типа поля
                    if (input.type === 'file') {
                        // Для файлов проверяем наличие выбранных файлов
                        isEmpty = input.files.length === 0;
                    } else {
                        // Для остальных полей проверяем значение
                        const value = input.value.trim();
                        isEmpty = value === '';
                        
                        // Дополнительная валидация для email
                        if (!isEmpty && input.type === 'text' && input.name.includes('EMAIL')) {
                            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            if (!emailRegex.test(value)) {
                                isEmpty = true;
                                errorText = 'Введите корректный email адрес';
                            }
                        }
                        
                        // Дополнительная валидация для телефона
                        if (!isEmpty && input.name.includes('PHONE')) {
                            const phoneValue = value.replace(/[\s\-\(\)]/g, '');
                            if (phoneValue.length < 11) {
                                isEmpty = true;
                                errorText = 'Введите корректный номер телефона';
                            }
                        }
                        
                        // Дополнительная валидация для подтверждения пароля
                        if (!isEmpty && input.name.includes('CONFIRM_PASSWORD')) {
                            const passwordInput = document.querySelector('input[name="REGISTER[PASSWORD]"]');
                            if (passwordInput && value !== passwordInput.value) {
                                isEmpty = true;
                                errorText = 'Пароли не совпадают';
                            }
                        }
                    }
                    
                    if (isEmpty) {
                        // Поле пустое или невалидное - добавляем ошибку
                        fieldWrapper.classList.add('has-error');
                        if (errorMessage) {
                            const errorSpan = errorMessage.querySelector('span');
                            if (errorSpan) {
                                errorSpan.textContent = errorText;
                            }
                            errorMessage.style.display = 'block';
                        }
                        isValid = false;
                        
                        // Запоминаем первое поле с ошибкой
                        if (!firstErrorField) {
                            firstErrorField = fieldWrapper;
                        }
                    } else {
                        // Поле заполнено корректно - убираем ошибку
                        fieldWrapper.classList.remove('has-error');
                        if (errorMessage) {
                            errorMessage.style.display = 'none';
                        }
                    }
                }
            }
        });
        
        // Прокручиваем к первому полю с ошибкой
        if (!isValid && firstErrorField) {
            firstErrorField.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
            
            // Фокусируемся на поле с ошибкой
            const errorInput = firstErrorField.querySelector('input[data-role="input"], textarea[data-role="input"], select[data-role="input"]');
            if (errorInput) {
                setTimeout(() => errorInput.focus(), 500);
            }
        }
        
        return isValid;
    }

    // Функция для показа уведомления
    function showNotification(type, title, message, redirectUrl = null) {
        const overlay = document.getElementById('notificationOverlay');
        const notification = document.getElementById('notification');
        const notificationIcon = document.getElementById('notificationIcon');
        const statusIcon = document.getElementById('statusIcon');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const notificationTitle = document.getElementById('notificationTitle');
        const notificationMessage = document.getElementById('notificationMessage');
        const progressFill = document.getElementById('progressFill');
        
        // Настройка стилей в зависимости от типа
        notification.className = 'notification ' + type;
        notificationIcon.className = 'notification-icon ' + type;
        progressFill.className = 'progress-fill ' + type;
        
        if (type === 'success') {
            statusIcon.innerHTML = '✓';
            statusIcon.style.display = 'block';
            loadingSpinner.style.display = 'none';
        } else {
            statusIcon.innerHTML = '✕';
            statusIcon.style.display = 'block';
            loadingSpinner.style.display = 'none';
        }
        
        notificationTitle.textContent = title;
        notificationMessage.textContent = message;
        overlay.style.display = 'flex';
        
        // Запускаем прогресс-бар
        progressFill.style.width = '0%';
        setTimeout(() => {
            progressFill.style.width = '100%';
        }, 10);
        
        // Обработка завершения
        setTimeout(() => {
            if (type === 'success' && redirectUrl) {
                window.location.href = redirectUrl;
            } else if (type === 'error') {
                overlay.style.display = 'none';
            }
        }, 3000);
    }

    // Модифицируем обработчик отправки формы
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('registrationForm');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Валидируем обязательные поля
                if (!validateRequiredFields()) {
                    // Если валидация не прошла, просто выходим
                    // Ошибки уже показаны под полями
                    return;
                }
                
                // Показываем уведомление о обработке
                showNotification('success', 'Обработка запроса', 'Отправляем данные для регистрации...');
                
                // Собираем данные формы
                const formData = new FormData(form);
                
                // Отправляем AJAX запрос
                fetch('/director/person/add-new-person-action.php', { 
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', 'Успешно!', data.message || 'Регистрация завершена успешно', '/company/profile/<?=$arResult['HEAD_COMPANY_B24_ID'];?>');
                    } else {
                        showNotification('error', 'Ошибка!', data.message || 'Произошла ошибка при регистрации');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('error', 'Ошибка!', 'Произошла ошибка при отправке формы');
                });
            });
        }
    });

    let activeBlock = 0;

    $('#next-step').hide();
    $('#submitFormBtn').show();

    // Убираем ошибку при изменении поля
    $('.block-fields input, .block-fields textarea, .block-fields select').on('input change', function (){
        const fieldWrapper = $(this).closest('.parent--wrapper');
        if (fieldWrapper.length) {
            fieldWrapper.removeClass('has-error');
            fieldWrapper.find('.ui-error-message').hide();
        }
    })

    $('input#REGISTER_EMAIL_POPUP2').change(function (){
        $('input#REGISTER_LOGIN_POPUP2').val($(this).val());
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Маска для телефона (нативный JS, без плагинов)
        const phoneInput = document.querySelector('input[name="REGISTER[PERSONAL_PHONE]"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                let formattedValue = '';
                
                if (value.length > 0) {
                    formattedValue = '+' + value.substring(0, 1);
                    if (value.length > 1) {
                        formattedValue += ' (' + value.substring(1, 4);
                        if (value.length > 4) {
                            formattedValue += ') ' + value.substring(4, 7);
                            if (value.length > 7) {
                                formattedValue += '-' + value.substring(7, 9);
                                if (value.length > 9) {
                                    formattedValue += '-' + value.substring(9, 11);
                                }
                            }
                        }
                    }
                }
                
                e.target.value = formattedValue;
            });
            
            phoneInput.addEventListener('focus', function(e) {
                if (e.target.value === '') {
                    e.target.value = '+';
                }
            });
            
            phoneInput.addEventListener('blur', function(e) {
                if (e.target.value === '+') {
                    e.target.value = '';
                }
            });
        }
        
        // Маска для даты рождения (нативный JS, без плагинов)
        const birthdayInput = document.querySelector('input[name="REGISTER[PERSONAL_BIRTHDAY]"]');
        if (birthdayInput) {
            birthdayInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                let formattedValue = '';
                
                if (value.length > 0) {
                    formattedValue = value.substring(0, 2);
                    if (value.length > 2) {
                        formattedValue += '.' + value.substring(2, 4);
                        if (value.length > 4) {
                            formattedValue += '.' + value.substring(4, 8);
                        }
                    }
                }
                
                e.target.value = formattedValue;
            });
            
            birthdayInput.setAttribute('placeholder', 'ДД.ММ.ГГГГ');
            birthdayInput.setAttribute('maxlength', '10');
        }
    })
</script>

<?php
    endif;
?>