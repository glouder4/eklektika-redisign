<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\Core;
use Bitrix\Main\Loader;
use intec\core\bitrix\Component;
use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

if (!Loader::includeModule('intec.core') || !Loader::includeModule('iblock'))
    return;

/** @var InnerTemplate $oTemplate */
$oTemplate = $arResult['TEMPLATE'];
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$sSiteUrl = Core::$app->request->getHostInfo().SITE_DIR;

$arVisual = $arResult['VISUAL'];
$arData = [
    'id' => $sTemplateId
];

?>
<?php
if( isset($_GET['test']) ){
    ?>
    <footer class="footer__main">
        <div class="footer__main-brand_info">
            <div class="footer__main-brand_info-logo">
                <svg xmlns="http://www.w3.org/2000/svg" width="233" height="78" viewBox="0 0 233 78" fill="none">
                    <g clip-path="url(#clip0_389_2226)">
                        <path d="M66.8644 55.8792C59.6857 55.8792 54.4555 50.1131 54.4555 42.7743C54.4555 35.4356 59.7882 29.6695 66.8644 29.6695C74.0431 29.6695 79.2733 35.4356 79.2733 42.7743C79.2733 50.1131 73.9405 55.8792 66.8644 55.8792ZM66.8644 20.3389C53.8402 20.3389 44.0977 30.1937 44.0977 42.6695C44.0977 55.2502 53.8402 65.0002 66.7618 65.0002C79.786 65.0002 89.426 55.1453 89.426 42.6695C89.5286 30.1937 79.8886 20.3389 66.8644 20.3389Z" fill="white"/>
                        <path d="M33.7399 21.7015L22.3565 49.1693L11.0757 21.5967H0L17.1263 61.3306L9.53741 77.9999H20.7157L44.8156 21.5967L33.7399 21.7015Z" fill="white"/>
                        <path d="M85.7342 1.46777H0V11.0081H85.7342V1.46777Z" fill="white"/>
                        <path d="M233 1.46777H157.828V11.0081H233V1.46777Z" fill="white"/>
                        <path d="M121.731 0C118.347 0 115.578 2.83065 115.578 6.29032C115.578 9.75 118.347 12.5806 121.731 12.5806C125.116 12.5806 127.884 9.75 127.884 6.29032C127.884 2.83065 125.116 0 121.731 0Z" fill="white"/>
                        <path d="M159.678 55.8791C152.397 55.8791 147.064 50.1129 147.064 42.7742C147.064 35.4355 152.499 29.6694 159.678 29.6694C166.959 29.6694 172.292 35.4355 172.292 42.7742C172.394 50.1129 166.959 55.8791 159.678 55.8791ZM161.011 20.3387C155.781 20.3387 151.166 22.121 147.474 24.9516V1.46777H137.219V33.5484V63.8468H146.346L146.654 59.7581C150.448 63.1129 155.371 65.1049 161.011 65.1049C173.42 65.1049 182.65 55.25 182.65 42.7742C182.65 30.1936 173.42 20.3387 161.011 20.3387Z" fill="white"/>
                        <path d="M126.857 21.5967H116.602V63.8467H126.857V21.5967Z" fill="white"/>
                        <path d="M106.247 1.46777H95.9922V63.742H106.247V1.46777Z" fill="white"/>
                        <path d="M210.441 55.8792C203.159 55.8792 197.827 50.1131 197.827 42.7743C197.827 35.4356 203.262 29.6695 210.441 29.6695C217.722 29.6695 223.055 35.4356 223.055 42.7743C223.055 50.1131 217.722 55.8792 210.441 55.8792ZM223.465 25.6856C219.67 22.3308 214.748 20.3389 209.107 20.3389C196.698 20.3389 187.469 30.1937 187.469 42.6695C187.469 55.2502 196.698 65.0002 209.107 65.0002C214.748 65.0002 219.67 63.0082 223.465 59.6534L223.772 63.7421H232.9V21.5969H223.772L223.465 25.6856Z" fill="white"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_389_2226">
                            <rect width="233" height="78" fill="white"/>
                        </clipPath>
                    </defs>
                </svg>
            </div>
            <div class="footer__main-brand_info-links">
                <a href="#" class="footer__main-brand_info-links-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                        <path d="M14.1339 13.8834L14.6157 13.4048L13.4969 12.2808L13.0173 12.7593L14.1339 13.8834ZM16.2309 13.2041L18.2497 14.3017L19.0051 12.9093L16.9873 11.8128L16.2309 13.2041ZM18.6374 16.4821L17.1373 17.9748L18.254 19.0978L19.7541 17.6062L18.6374 16.4821ZM16.2225 18.4545C14.6907 18.5981 10.7291 18.4703 6.43797 14.2045L5.32029 15.3275C10.0023 19.9831 14.4593 20.2113 16.3704 20.0327L16.2225 18.4545ZM6.43797 14.2045C2.34859 10.1373 1.67037 6.7177 1.58586 5.23344L0.00335245 5.32323C0.108994 7.19097 0.948843 10.9814 5.32029 15.3275L6.43797 14.2045ZM7.89054 7.67587L8.19373 7.37373L7.07816 6.25077L6.77497 6.55184L7.89054 7.67587ZM8.43459 3.53367L7.10351 1.75467L5.83476 2.70544L7.16584 4.48339L8.43459 3.53367ZM2.62326 1.36696L0.964689 3.01497L2.08238 4.13899L3.73989 2.49099L2.62326 1.36696ZM7.33275 7.11386C6.77285 6.55184 6.77285 6.55184 6.77285 6.55396H6.77074L6.76757 6.55818C6.71737 6.6088 6.67242 6.66437 6.63341 6.72404C6.57636 6.80855 6.51403 6.91948 6.46121 7.05998C6.33262 7.42267 6.30058 7.81258 6.36825 8.1914C6.50981 9.1052 7.13943 10.3127 8.75152 11.9163L9.8692 10.7923C8.35959 9.29218 8.00041 8.37944 7.93385 7.94842C7.90216 7.74348 7.93491 7.64206 7.94442 7.61882C7.95076 7.60474 7.95076 7.60262 7.94442 7.61248C7.935 7.62706 7.9244 7.64084 7.91272 7.65368L7.90216 7.66425L7.8916 7.67376L7.33275 7.11386ZM8.75152 11.9163C10.3647 13.52 11.5785 14.1453 12.4933 14.2848C12.9613 14.3566 13.3385 14.2996 13.6248 14.1929C13.7851 14.1342 13.935 14.05 14.0684 13.9436L14.1213 13.896L14.1287 13.8897L14.1318 13.8865L14.1329 13.8844C14.1329 13.8844 14.1339 13.8834 13.5751 13.3213C13.0152 12.7593 13.0184 12.7583 13.0184 12.7583L13.0205 12.7562L13.0226 12.7541L13.0289 12.7488L13.0395 12.7382L13.0796 12.7065C13.0895 12.7002 13.087 12.7009 13.0723 12.7086C13.0458 12.7181 12.9423 12.7509 12.7342 12.7192C12.2968 12.6516 11.3778 12.2924 9.8692 10.7923L8.75152 11.9163ZM7.10351 1.75361C6.02597 0.316888 3.90892 0.0887023 2.62326 1.36696L3.73989 2.49099C4.3019 1.93215 5.29916 1.99025 5.83476 2.70544L7.10351 1.75361ZM1.58692 5.2345C1.56579 4.86898 1.73376 4.48655 2.08238 4.14005L0.963633 3.01603C0.396339 3.58015 -0.0505247 4.3788 0.00335245 5.32323L1.58692 5.2345ZM17.1373 17.9748C16.8479 18.2643 16.5352 18.427 16.2235 18.4555L16.3704 20.0327C17.1468 19.9599 17.7817 19.569 18.255 19.0989L17.1373 17.9748ZM8.19373 7.37373C9.2343 6.3395 9.31142 4.70523 8.43565 3.53472L7.1669 4.48444C7.59263 5.05385 7.52925 5.80073 7.0771 6.25182L8.19373 7.37373ZM18.2508 14.3028C19.1139 14.7718 19.248 15.8768 18.6385 16.4832L19.7562 17.6062C21.1718 16.198 20.7355 13.8496 19.0061 12.9104L18.2508 14.3028ZM14.6157 13.4059C15.0213 13.0023 15.6742 12.903 16.232 13.2051L16.9884 11.8138C15.8432 11.1906 14.4234 11.3628 13.498 12.2818L14.6157 13.4059Z" fill="#EF4A85"/>
                    </svg>
                    +7 (495) 129-53-72
                </a>
                <a href="#" class="footer__main-brand_info-links-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17" fill="none">
                        <path d="M2.38472 16.0167C1.85864 16.0167 1.40843 15.8296 1.03411 15.4553C0.659796 15.081 0.472317 14.6304 0.47168 14.1037V2.62545C0.47168 2.09936 0.659158 1.64916 1.03411 1.27484C1.40907 0.900518 1.85927 0.71304 2.38472 0.712402H17.6891C18.2152 0.712402 18.6657 0.89988 19.0406 1.27484C19.4156 1.64979 19.6028 2.1 19.6021 2.62545V14.1037C19.6021 14.6298 19.415 15.0803 19.0406 15.4553C18.6663 15.8302 18.2158 16.0174 17.6891 16.0167H2.38472ZM10.0369 9.3211L2.38472 4.53849V14.1037H17.6891V4.53849L10.0369 9.3211ZM10.0369 7.40805L17.6891 2.62545H2.38472L10.0369 7.40805ZM2.38472 4.53849V2.62545V14.1037V4.53849Z" fill="#EF4A85"/>
                    </svg>
                    team@eklektika.ru
                </a>
            </div>
            <div class="footer__main-brand_info-social-links">
                <div  class="footer__main-brand_info-social-links--title"><span>Мы в сети</span></div>
                <div class="footer__main-brand_info-social-links--wrapper">
                    <a href="#" class="footer__main-brand_info-social-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="20" viewBox="0 0 32 20" fill="none">
                            <path d="M30.5747 19.3077H27.1105C25.8004 19.3077 25.4057 18.2543 23.0573 15.9214C21.0048 13.9578 20.138 13.7137 19.6188 13.7137C18.9008 13.7137 18.7049 13.9096 18.7049 14.8907V17.983C18.7049 18.8194 18.4317 19.3092 16.2305 19.3092C14.095 19.1668 12.0242 18.5227 10.1885 17.4302C8.35289 16.3376 6.80499 14.8278 5.67232 13.025C2.98384 9.70152 1.11266 5.80175 0.207275 1.63515C0.207275 1.11976 0.404624 0.652588 1.39441 0.652588H4.8556C5.74519 0.652588 6.0655 1.04591 6.41466 1.95463C8.09516 6.86442 10.9628 11.1352 12.1272 11.1352C12.5735 11.1352 12.7678 10.9393 12.7678 9.8347V4.77723C12.6205 2.47002 11.3863 2.27562 11.3863 1.44075C11.4019 1.22049 11.5034 1.01494 11.6693 0.867777C11.8351 0.720611 12.0522 0.643415 12.2744 0.652588H17.7152C18.459 0.652588 18.7049 1.02029 18.7049 1.90339V8.73007C18.7049 9.46699 19.0237 9.71113 19.2484 9.71113C19.6947 9.71113 20.0378 9.46699 20.8561 8.65623C22.6092 6.53236 24.0421 4.16676 25.1097 1.63364C25.2184 1.32917 25.4248 1.06851 25.697 0.891581C25.9693 0.714655 26.2925 0.631232 26.6171 0.654095H30.0798C31.1182 0.654095 31.3383 1.16949 31.1182 1.9049C29.8584 4.70534 28.3001 7.36377 26.4699 9.8347C26.0964 10.3998 25.9477 10.6937 26.4699 11.3568C26.813 11.8722 28.0274 12.8788 28.8426 13.8358C30.0297 15.0112 31.015 16.3705 31.7603 17.8594C32.0579 18.8179 31.563 19.3077 30.5747 19.3077Z" fill="white"/>
                        </svg>
                    </a>
                    <a href="#" class="footer__main-brand_info-social-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6.22907 0.370634C7.2883 0.321871 7.62603 0.311035 10.3242 0.311035C13.0224 0.311035 13.3601 0.322774 14.4185 0.370634C15.4768 0.418493 16.1992 0.587356 16.8313 0.832072C17.4932 1.08221 18.0937 1.47321 18.5904 1.97889C19.0961 2.47465 19.4862 3.07425 19.7354 3.73706C19.981 4.36916 20.149 5.09157 20.1977 6.14809C20.2465 7.20913 20.2573 7.54685 20.2573 10.2441C20.2573 12.9414 20.2456 13.2801 20.1977 14.3393C20.1499 15.3958 19.981 16.1182 19.7354 16.7503C19.4861 17.4132 19.0953 18.0138 18.5904 18.5103C18.0937 19.016 17.4932 19.4061 16.8313 19.6553C16.1992 19.9009 15.4768 20.0689 14.4203 20.1177C13.3601 20.1664 13.0224 20.1773 10.3242 20.1773C7.62603 20.1773 7.2883 20.1655 6.22907 20.1177C5.17255 20.0698 4.45014 19.9009 3.81804 19.6553C3.15519 19.406 2.55462 19.0152 2.05807 18.5103C1.55291 18.0141 1.16187 17.4138 0.91215 16.7512C0.667434 16.1191 0.499474 15.3967 0.450712 14.3402C0.401949 13.2792 0.391113 12.9414 0.391113 10.2441C0.391113 7.54685 0.402852 7.20823 0.450712 6.1499C0.498571 5.09157 0.667434 4.36916 0.91215 3.73706C1.16214 3.07437 1.55348 2.4741 2.05897 1.97799C2.55491 1.47294 3.15486 1.08191 3.81713 0.832072C4.44924 0.587356 5.17255 0.419396 6.22907 0.370634ZM14.3372 2.15859C13.2897 2.11073 12.9755 2.1008 10.3233 2.1008C7.67118 2.1008 7.35693 2.11073 6.30944 2.15859C5.34051 2.20284 4.81496 2.36448 4.46459 2.50083C4.00135 2.68144 3.66994 2.89545 3.32228 3.24311C2.99244 3.56349 2.73877 3.95387 2.58001 4.38542C2.44366 4.73578 2.28202 5.26134 2.23777 6.23027C2.18991 7.27776 2.17998 7.592 2.17998 10.2441C2.17998 12.8963 2.18991 13.2105 2.23777 14.258C2.28202 15.227 2.44366 15.7525 2.58001 16.1029C2.73894 16.5336 2.99268 16.9246 3.32228 17.2452C3.64285 17.5748 4.03386 17.8285 4.46459 17.9875C4.81496 18.1238 5.34051 18.2854 6.30944 18.3297C7.35693 18.3776 7.67028 18.3875 10.3233 18.3875C12.9764 18.3875 13.2897 18.3776 14.3372 18.3297C15.3061 18.2854 15.8317 18.1238 16.182 17.9875C16.6453 17.8069 16.9767 17.5928 17.3244 17.2452C17.654 16.9246 17.9077 16.5336 18.0666 16.1029C18.203 15.7525 18.3646 15.227 18.4089 14.258C18.4567 13.2105 18.4667 12.8963 18.4667 10.2441C18.4667 7.592 18.4567 7.27776 18.4089 6.23027C18.3646 5.26134 18.203 4.73578 18.0666 4.38542C17.886 3.92217 17.672 3.59077 17.3244 3.24311C17.004 2.91326 16.6136 2.6596 16.182 2.50083C15.8317 2.36448 15.3061 2.20284 14.3372 2.15859ZM9.05459 13.3063C9.76315 13.6012 10.5521 13.641 11.2868 13.4189C12.0214 13.1967 12.6561 12.7264 13.0826 12.0883C13.509 11.4502 13.7006 10.6838 13.6248 9.92006C13.5489 9.15632 13.2102 8.44262 12.6666 7.90083C12.3201 7.55451 11.9011 7.28933 11.4397 7.12438C10.9784 6.95943 10.4862 6.89882 9.99868 6.94691C9.51112 6.995 9.04028 7.15059 8.62007 7.40249C8.19985 7.65439 7.84072 7.99632 7.56851 8.40368C7.29631 8.81103 7.11781 9.27367 7.04586 9.75829C6.97391 10.2429 7.01031 10.7375 7.15244 11.2063C7.29456 11.6752 7.53887 12.1067 7.86778 12.4698C8.1967 12.8329 8.60203 13.1186 9.05459 13.3063ZM6.71399 6.63391C7.18809 6.15981 7.75093 5.78373 8.37038 5.52715C8.98982 5.27057 9.65374 5.1385 10.3242 5.1385C10.9947 5.1385 11.6586 5.27056 12.2781 5.52715C12.8975 5.78373 13.4604 6.15981 13.9345 6.63391C14.4086 7.10801 14.7846 7.67086 15.0412 8.2903C15.2978 8.90975 15.4299 9.57366 15.4299 10.2441C15.4299 10.9146 15.2978 11.5785 15.0412 12.198C14.7846 12.8174 14.4086 13.3803 13.9345 13.8544C12.977 14.8119 11.6783 15.3498 10.3242 15.3498C8.97012 15.3498 7.67148 14.8119 6.71399 13.8544C5.7565 12.8969 5.21858 11.5982 5.21858 10.2441C5.21858 8.89005 5.7565 7.5914 6.71399 6.63391ZM16.5622 5.89886C16.6797 5.78803 16.7738 5.65476 16.8388 5.50693C16.9039 5.3591 16.9386 5.19972 16.941 5.03823C16.9433 4.87674 16.9132 4.71641 16.8525 4.56675C16.7918 4.41709 16.7017 4.28113 16.5875 4.16692C16.4733 4.05272 16.3373 3.96259 16.1876 3.90187C16.038 3.84115 15.8777 3.81108 15.7162 3.81343C15.5547 3.81579 15.3953 3.85052 15.2475 3.91558C15.0996 3.98063 14.9664 4.07469 14.8555 4.19217C14.64 4.42066 14.522 4.72415 14.5266 5.03823C14.5311 5.3523 14.6579 5.65223 14.8801 5.87434C15.1022 6.09645 15.4021 6.22325 15.7162 6.22783C16.0302 6.23241 16.3337 6.1144 16.5622 5.89886Z" fill="white"/>
                        </svg>
                    </a>
                    <a href="#" class="footer__main-brand_info-social-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="20" viewBox="0 0 23 20" fill="none">
                            <path d="M22.596 1.78782L19.2837 18.0373C19.0367 19.1817 18.4026 19.4395 17.4865 18.9228L12.5181 15.083L10.0858 17.5195C9.83986 17.7784 9.5928 18.0373 9.02873 18.0373L9.4168 12.6819L18.6847 3.85667C19.0716 3.45 18.5786 3.30223 18.0856 3.63558L6.56255 11.242L1.59302 9.65425C0.500948 9.28539 0.500948 8.50871 1.84008 7.99322L21.1508 0.126788C22.1019 -0.168761 22.913 0.349023 22.596 1.78782Z" fill="white"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="footer__main-categories__columns">
            <div class="footer__main-categories__columns-column-1">
                <div class="footer__main-categories__columns-column--title">
                    <span>Виды нанесения</span>
                </div>
                <ul class="footer__main-categories__columns-column--list">
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Виды печати на сувениры
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            DTF печать
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Тампонная печать до 4 цветов
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Лазерная гравировка
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Полноцветная УФ-печать
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Тиснение логотипов
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Сублимационная печать
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Шелкография на ткани
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Нанесение логотипов на ежедневники
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Печать на текстиле оптом
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Кастомизация
                        </a>
                    </li>
                </ul>
            </div>
            <div class="footer__main-categories__columns-column-2">
                <div class="footer__main-categories__columns-column--title">
                    <span>Для клиентов</span>
                </div>
                <ul class="footer__main-categories__columns-column--list">
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Программа привилегий и бонусов для дилеров
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Гибкая система оплаты
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Разработка дизайна сувенирной продукции
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Товар на складе в Москве
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Доставка
                        </a>
                    </li>
                </ul>
            </div>
            <div class="footer__main-categories__columns-column-3">
                <div class="footer__main-categories__columns-column--title">
                    <span>О нас</span>
                </div>
                <ul class="footer__main-categories__columns-column--list">
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            О компании
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Новости
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Бренды
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Новинки
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Акции и Скидки
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Контакты
                        </a>
                    </li>
                    <li class="footer__main-categories__columns-column--list_item">
                        <a href="#" class="footer__main-categories__columns-column--list_item_link">
                            Как проехать
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer__main-adres-info">
            <div class="footer__main-adres-info--item">
                <div class="footer__main-adres-info--item_title">
                    <span>Почтовый адрес</span>
                </div>
                <div class="footer__main-adres-info--item_data">
                    <p>109428, г. Москва, Рязанский проспект, 16, строение 3, помещение I, комната 39, этаж&nbsp;7</p>
                </div>
            </div>
            <div class="footer__main-adres-info--item">
                <div class="footer__main-adres-info--item_title">
                    <span>Офис</span>
                </div>
                <div class="footer__main-adres-info--item_data">
                    <p>109428, г.&nbsp;Москва,&nbsp;Рязанский проспект,&nbsp;д.24,&nbsp;корп.2</p>
                </div>
            </div>
            <div class="footer__main-adres-info--item">
                <div class="footer__main-adres-info--item_title">
                    <span>Склад</span>
                </div>
                <div class="footer__main-adres-info--item_data">
                    <p>109428, Москва, Рязанский проспект, д.16, стр.3</p>
                </div>
            </div>
            <div class="footer__main-adres-info--item">
                <div class="footer__main-adres-info--item_title">
                    <span>Режим работы</span>
                </div>
                <div class="footer__main-adres-info--item_data">
                    <p>Пн-Пт: 9:30-18:00</p>
                </div>
            </div>
        </div>
    </footer>

    <div style="display: none;" id="promotions-action-popup">
        <div class="promotions-action-popup-block">
            <div class="promotions-action-popup-image">
                <img src="/local/templates/onlineservice-custom-template/components/promotions-and-discounts/promotions/assets/okak.png" alt="">
            </div>
            <div class="promotions-action-popup-data">
                <h2>Если вы это видите</h2>
                <p>То мы забыли предоставить информацию разработчику, но скоро всё исправим!</p>
            </div>
        </div>
    </div>
    <div style="display: none;" id="news-action-popup">
        <div class="promotions-action-popup-block">
            <div class="promotions-action-popup-image">
                <img src="/local/templates/onlineservice-custom-template/components/promotions-and-discounts/promotions/assets/okak.png" alt="">
            </div>
            <div class="promotions-action-popup-data">
                <h2>Если вы это видите</h2>
                <p>То мы забыли предоставить информацию разработчику, но скоро всё исправим!</p>
            </div>
        </div>
    </div>
    <?php
}
else{
    ?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'widget',
        'c-footer',
        'c-footer-template-1',
        'vcard'
    ],
    'data' => [
        'theme' => $arResult['THEME']
    ]
]) ?>
    <div class="widget-content">
        <div style="display: none;">
            <span class="url">
                <span class="value-title" title="<?= $sSiteUrl ?>"></span>
            </span>
            <span class="fn org">
                <?= $arResult['COMPANY_NAME'] ?>
            </span>
            <img class="photo" src="<?= $sSiteUrl.'include/logotype.png' ?>" alt="<?= $arResult['COMPANY_NAME'] ?>" />
        </div>
        <?php if ($arParams['PRODUCTS_VIEWED_SHOW'] === 'Y') { ?>
            <div class="widget-part">
                <?php include(__DIR__.'/parts/products.viewed.php') ?>
            </div>
        <?php } ?>
        <div class="widget-view">
            <?php if (!empty($oTemplate)) { ?>
                <?= $oTemplate->render(
                    $arParams,
                    $arResult,
                    $arData
                ) ?>
            <?php } ?>
        </div>
    </div>
<?= Html::endTag('div');

        }
?>