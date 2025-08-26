<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

CJSCore::Init();
?>
<a rel="nofollow" href="/personal/profile/" class="header__icon header__icon--user">
    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="17" viewBox="0 0 19 17" fill="none">
        <path d="M9.62908 8.2992C11.4609 8.2992 12.9459 6.81421 12.9459 4.98238C12.9459 3.15055 11.4609 1.66555 9.62908 1.66555C7.79725 1.66555 6.31226 3.15055 6.31226 4.98238C6.31226 6.81421 7.79725 8.2992 9.62908 8.2992Z" stroke="white" stroke-width="1.08595" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M17.2424 16.307C16.7252 14.6942 15.7092 13.2873 14.3409 12.2891C12.9726 11.2909 11.3226 10.7531 9.62891 10.7531C7.93521 10.7531 6.28525 11.2909 4.91692 12.2891C3.54859 13.2873 2.53259 14.6942 2.01538 16.307H17.2424Z" stroke="white" stroke-width="1.08595" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    <span class="header__icon-title">Профиль</span>
</a>

<div id="profile_fields--wrapper">
    <?php
    if( !$USER->IsAuthorized() ){ ?>
    <div id="header-dropdown-panel-wrapper">
        <div class="fields-wrapper">
            <div class="errors">
                <?
                    if ($arResult['SHOW_ERRORS'] === 'Y' && $arResult['ERROR'] && !empty($arResult['ERROR_MESSAGE']))
                    {
                        ShowMessage($arResult['ERROR_MESSAGE']);
                    }
                ?>
            </div>
            <div class="inputs-wrapper">
                <form name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
                    <?if($arResult["BACKURL"] <> ''):?>
                        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
                    <?endif?>
                    <?foreach ($arResult["POST"] as $key => $value):?>
                        <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
                    <?endforeach?>
                    <input type="hidden" name="AUTH_FORM" value="Y" />
                    <input type="hidden" name="TYPE" value="AUTH" />

                    <div class="form-fields">
                        <div class="field_wrapper auth-login--field_wrapper">
                            <div class="form-field-name">
                                <?=GetMessage("AUTH_LOGIN")?>
                                <span class="required-field">*</span>
                            </div>
                            <input type="text" name="USER_LOGIN" maxlength="50" value="" size="17" />
                            <script>
                                BX.ready(function() {
                                    var loginCookie = BX.getCookie("<?=CUtil::JSEscape($arResult["~LOGIN_COOKIE_NAME"])?>");
                                    if (loginCookie)
                                    {
                                        var form = document.forms["system_auth_form<?=$arResult["RND"]?>"];
                                        var loginInput = form.elements["USER_LOGIN"];
                                        loginInput.value = loginCookie;
                                    }
                                });
                            </script>
                        </div>
                        <div class="field_wrapper auth-password--field_wrapper">
                            <div class="form-field-name">
                                <?=GetMessage("AUTH_PASSWORD")?>
                                <span class="required-field">*</span>
                            </div>
                            <input type="password" name="USER_PASSWORD" maxlength="255" size="17" autocomplete="off" />
                            <?if($arResult["SECURE_AUTH"]):?>
                                <span class="bx-auth-secure" id="bx_auth_secure<?=$arResult["RND"]?>" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
                                        <div class="bx-auth-secure-icon"></div>
                                    </span>
                                <noscript>
                                        <span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
                                            <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
                                        </span>
                                </noscript>
                                <script>
                                    document.getElementById('bx_auth_secure<?=$arResult["RND"]?>').style.display = 'inline-block';
                                </script>
                            <?endif?>
                        </div>
                    </div>

                    <div class="form-actions">
                        <?if ($arResult["STORE_PASSWORD"] == "Y"):?>
                            <div id="store_password">
                                <input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y" />
                                <label for="USER_REMEMBER_frm" title="<?=GetMessage("AUTH_REMEMBER_ME")?>"><?echo GetMessage("AUTH_REMEMBER_SHORT")?></label>
                            </div>
                        <?endif?>
                        <div id="forgot-password">
                            <noindex><a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a></noindex>
                        </div>
                    </div>
                    
                    <div class="form-actions btns">
                        <input type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" />

                        <noindex><a class="register-btn" href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_REGISTER")?></a></noindex>
                    </div>

                    <table width="95%">

                        <?if ($arResult["CAPTCHA_CODE"]):?>
                            <tr>
                                <td colspan="2">
                                    <?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:<br />
                                    <input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
                                    <img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /><br /><br />
                                    <input type="text" name="captcha_word" maxlength="50" value="" /></td>
                            </tr>
                        <?endif?>

                    </table>
                </form>
            </div>
        </div>
    </div>
    <?php }
    else{ ?>
        <div class="widgets">
            <div class="widget">
                <a href="/personal/profile/" rel="nofollow" class="widget-item">
                    <div class="data">
                        <span><?=$USER->GetFullName();?></span>
                    </div>
                </a>
            </div>
            <div class="widget">
                <a href="/personal/profile/" rel="nofollow" class="widget-item">
                    <div class="data">
                        <span>Личный кабинет</span>
                    </div>
                </a>
            </div>
            <div class="widget">
                <a href="/personal/profile/orders/?filter_status=N" rel="nofollow" class="widget-item">
                    <div class="data">
                        <span>Заказы</span>
                    </div>
                </a>
            </div>
            <div class="widget">
                <a href="/personal/profile/orders/?filter_date_from=&filter_status[]=R&filter_status[]=RO&filter_status[]=RС" rel="nofollow" class="widget-item">
                    <div class="data">
                        <span>Резервы</span>
                    </div>
                </a>
            </div>
            <div class="widget">
                <a href="/personal/profile/orders/?filter_date_from=&filter_date_to=&filter_status=OB" rel="nofollow" class="widget-item">
                    <div class="data">
                        <span>Образцы</span>
                    </div>
                </a>
            </div>
            <div class="widget">
                <a href="/personal/profile/kp/" rel="nofollow" class="widget-item">
                    <div class="data">
                        <span>Коммерческие предложения</span>
                    </div>
                </a>
            </div>
            <div class="widget">
                <a href="/personal/profile/" rel="nofollow" class="widget-item">
                    <div class="data">
                        <span>Написать менеджеру</span>
                    </div>
                </a>
            </div>
            <div class="widget">
                <a href="/logout.php" rel="nofollow" class="widget-item">
                    <div class="data">
                        <span style="color: #EF4A85!important;">Выход</span>
                    </div>
                </a>
            </div>
        </div>
    <?php }
    ?>
</div>

<div id="top_header-auth_reg-btns--wrapper">
    <?php
    if( !$USER->IsAuthorized() ){ ?>
        <a href="/personal/profile/" rel="nofollow" class="top_header-btn auth">Войти</a>
        <a href="/personal/profile/registration.php" rel="nofollow" class="top_header-btn reg">Стать дилером</a>
    <?php }
    else{ ?>
        <a href="/logout.php" rel="nofollow" class="top_header-btn auth">Выйти</a>
    <?php }
    ?>
</div>
