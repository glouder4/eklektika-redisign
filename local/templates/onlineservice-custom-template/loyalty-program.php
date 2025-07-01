<?php
$APPLICATION->SetTitle("Программа лояльности");
?>
<div class="container">
    <?$APPLICATION->IncludeComponent(
        "bitrix:breadcrumb",
        "onlineservice-breadcrumbs",
        Array(),
        false
    );?>
</div>
<main class="main-content">
    <div class="container">
        <div class="page__title-wrapper">
            <h1 class="page__title-wrapper-title">
                <?$APPLICATION->ShowTitle(false);?>
            </h1>
        </div>
    </div>
    <div class="loyalty-program--banner">
        <div class="backround-element">
            <img class="bg-image" src="/local/templates/onlineservice-custom-template/components/loyalty-program/assets/background-element.png" alt="">
        </div>
        <div class="loyalty-program--banner_data container">
            <div class="loyalty-program--banner_data--items">
                <div class="banner-title">
                    <span class="title">Мы&nbsp;разработали специальную партнёрскую программу для&nbsp;рекламных, креативных и&nbsp;BTL агентств</span>
                </div>
                <div class="banner-list">
                    <p>Вы получаете:</p>
                    <ul>
                        <li>
                            Прозрачную систему скидок без&nbsp;обязательств по&nbsp;объёму продаж
                        </li>
                        <li>
                            Широкий перечень максимально полезных бесплатных для&nbsp;вас&nbsp;сервисов
                        </li>
                        <li>Личный кабинет для&nbsp;удобства работы</li>
                    </ul>
                </div>
                <div class="banner-action">
                    <a href="#" class="btn">Стать партнером</a>
                </div>
            </div>
        </div>
    </div>
    <div class="discounts-list--section">
        <div class="container">
            <div class="discounts--list--title">
                <h3 class="title">
                    Система скидок от Yoliba by Эклектика
                </h3>
            </div>
            <div class="discounts--list-block">
                <div class="discounts--list-block--text">
                    <span class="text">Базовая скидка при регистрации на сайте с первого заказа -20%</span>
                </div>
                <div class="discounts--list-block--image">
                    <img src="/local/templates/onlineservice-custom-template/components/loyalty-program/assets/discounts-image.png" alt="">
                </div>
            </div>

            <div class="discounts-list--additional-info">
                <div class="discounts-list--additional-info--icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
                        <circle cx="40" cy="40" r="40" fill="#EF4A85"/>
                        <path d="M27.0889 38.4636L52.723 26.8057" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M47.9336 25L52.7261 26.8055L50.9428 31.598" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M53.8633 54H48.2906V38.3966C48.2906 38.101 48.4081 37.8175 48.6171 37.6085C48.8261 37.3995 49.1096 37.2821 49.4052 37.2821H52.7488C53.0443 37.2821 53.3278 37.3995 53.5368 37.6085C53.7459 37.8175 53.8633 38.101 53.8633 38.3966V54Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M42.7178 54H37.1451V41.7402C37.1451 41.4446 37.2626 41.1611 37.4716 40.9521C37.6806 40.7431 37.9641 40.6257 38.2597 40.6257H41.6032C41.8988 40.6257 42.1823 40.7431 42.3913 40.9521C42.6004 41.1611 42.7178 41.4446 42.7178 41.7402V54Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M31.5723 54H25.9996V45.0838C25.9996 44.7882 26.1171 44.5047 26.3261 44.2957C26.5351 44.0867 26.8186 43.9693 27.1142 43.9693H30.4577C30.7533 43.9693 31.0368 44.0867 31.2458 44.2957C31.4548 44.5047 31.5723 44.7882 31.5723 45.0838V54Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="discounts-list--additional-info--text">
                    <p>Повышающаяся скидка при достижении определенного<br/>совокупного объема продаж</p>
                </div>
            </div>

            <div class="discounts-list--items">
                <div class="discounts-list--items_list">
                    <div class="discounts-list--items_list--item">
                        <div class="items_list--item--background-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="288" height="231" viewBox="0 0 288 231" fill="none">
                                <path d="M69.766 115.5L4 2H224.359L285 115.5L224.359 229H4L69.766 115.5Z" stroke="#FBB040" stroke-width="4"/>
                            </svg>
                        </div>
                        <div class="items_list--item--title">
                            -22 <span>%</span>
                        </div>
                        <div class="items_list--item--description">
                            <p>от 500 000 руб.</p>
                        </div>
                    </div>
                    <div class="discounts-list--items_list--item">
                        <div class="items_list--item--background-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="288" height="231" viewBox="0 0 288 231" fill="none">
                                <path d="M69.766 115.5L4 2H224.359L285 115.5L224.359 229H4L69.766 115.5Z" stroke="#FBB040" stroke-width="4"/>
                            </svg>
                        </div>
                        <div class="items_list--item--title">
                            -24 <span>%</span>
                        </div>
                        <div class="items_list--item--description">
                            <p>от 1 000 000 руб.</p>
                        </div>
                    </div>
                    <div class="discounts-list--items_list--item">
                        <div class="items_list--item--background-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="288" height="231" viewBox="0 0 288 231" fill="none">
                                <path d="M69.766 115.5L4 2H224.359L285 115.5L224.359 229H4L69.766 115.5Z" stroke="#FBB040" stroke-width="4"/>
                            </svg>
                        </div>
                        <div class="items_list--item--title">
                            -26 <span>%</span>
                        </div>
                        <div class="items_list--item--description">
                            <p>от 1 500 000 руб.</p>
                        </div>
                    </div>
                    <div class="discounts-list--items_list--item">
                        <div class="items_list--item--background-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="288" height="231" viewBox="0 0 288 231" fill="none">
                                <path d="M69.766 115.5L4 2H224.359L285 115.5L224.359 229H4L69.766 115.5Z" stroke="#FBB040" stroke-width="4"/>
                            </svg>
                        </div>
                        <div class="items_list--item--title">
                            -28 <span>%</span>
                        </div>
                        <div class="items_list--item--description">
                            <p>от 2 000 000 руб.</p>
                        </div>
                    </div>
                    <div class="discounts-list--items_list--item">
                        <div class="items_list--item--background-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="288" height="231" viewBox="0 0 288 231" fill="none">
                                <path d="M69.766 115.5L4 2H224.359L285 115.5L224.359 229H4L69.766 115.5Z" stroke="#FBB040" stroke-width="4"/>
                            </svg>
                        </div>
                        <div class="items_list--item--title">
                            -30 <span>%</span>
                        </div>
                        <div class="items_list--item--description">
                            <p>от 3 000 000 руб.</p>
                        </div>
                    </div>
                </div>
                <span class="additional-list-text">Минимальный заказ&nbsp;— 30 000&nbsp;₽</span>
            </ul>
        </div>
    </div>
    </div>
    <div class="partnership-services-section">
        <div class="container">
            <div class="partnership-services-section--title">
                <h3 class="title">Наши сервисы в рамках партнерской программы</h3>
            </div>
            <div class="partnership-services-section--list">
                <div class="partnership-services-section--list_item">
                    <div class="partnership-services-section--list_item--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="55" height="52" viewBox="0 0 55 52" fill="none">
                            <path d="M19.0732 12.1951V0M43.4634 12.1951V0M53.2195 39.0244V50H9.31707V42.6829M52.8854 18.2927H8.9561M2 42.0732V42.6829H45.6585L46.0244 42.0732L46.5951 40.8756C50.9565 31.709 53.2195 21.6854 53.2195 11.5341V6.09756H9.31707V11.2878C9.31717 21.5175 7.01913 31.6166 2.59268 40.839L2 42.0732Z" stroke="#57B0EA" stroke-width="3"/>
                        </svg>
                    </div>
                    <div class="partnership-services-section--list_item--description">
                        <p>Пост оплата<br/>до&nbsp;30&nbsp;рабочих дней</p>
                    </div>
                </div>
                <div class="partnership-services-section--list_item">
                    <div class="partnership-services-section--list_item--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="42" height="54" viewBox="0 0 42 54" fill="none">
                            <path d="M30.8462 5.84668H36.6154C37.6354 5.84668 38.6137 6.2519 39.335 6.97319C40.0563 7.69449 40.4615 8.67277 40.4615 9.69283V48.1544C40.4615 49.1744 40.0563 50.1527 39.335 50.874C38.6137 51.5953 37.6354 52.0005 36.6154 52.0005H5.84615C4.82609 52.0005 3.84781 51.5953 3.12651 50.874C2.40522 50.1527 2 49.1744 2 48.1544V9.69283C2 8.67277 2.40522 7.69449 3.12651 6.97319C3.84781 6.2519 4.82609 5.84668 5.84615 5.84668H11.6154" stroke="#FBB040" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M27.0018 2H15.4633C13.3392 2 11.6172 3.72198 11.6172 5.84615V7.76923C11.6172 9.8934 13.3392 11.6154 15.4633 11.6154H27.0018C29.126 11.6154 30.848 9.8934 30.848 7.76923V5.84615C30.848 3.72198 29.126 2 27.0018 2Z" stroke="#FBB040" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M11.6172 32.7697L19.3095 38.5389L28.9249 23.1543" stroke="#FBB040" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="partnership-services-section--list_item--description">
                        <p>Резервирование товара<br/>до&nbsp;7&nbsp;рабочих дней</p>
                    </div>
                </div>
                <div class="partnership-services-section--list_item">
                    <div class="partnership-services-section--list_item--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="46" height="50" viewBox="0 0 46 50" fill="none">
                            <path d="M42.6736 26.3564H38.2205V23.6346C38.2205 20.281 35.581 17.5401 32.2909 17.417V11.369C32.2909 9.60446 30.8702 8.16883 29.1237 8.16883H23.4446V7.37465C23.4446 3.30824 20.1702 0 16.1455 0C12.1207 0 8.84636 3.30824 8.84636 7.37465V8.16893H3.1672C1.42081 8.16893 0 9.60446 0 11.369V46.8C0 48.5645 1.42081 50 3.1672 50H19.6512C19.905 50 20.1377 49.9088 20.319 49.7572C20.6642 49.9127 21.046 50 21.4479 50H42.6736C44.2071 50 45.4545 48.7395 45.4545 47.1902V29.1663C45.4545 27.6169 44.2071 26.3564 42.6736 26.3564ZM36.1239 23.6346V26.3564L27.9976 26.3565V23.6346C27.9976 21.3709 29.8203 19.5295 32.0607 19.5295C34.3011 19.5295 36.1239 21.3709 36.1239 23.6346ZM10.943 7.37465C10.943 4.47636 13.2768 2.11834 16.1455 2.11834C19.0141 2.11834 21.3479 4.47636 21.3479 7.37465V8.16883L10.943 8.16893V7.37465ZM3.1672 10.2873H8.84646V11.4026C7.03767 11.8742 5.69753 13.5366 5.69753 15.5094C5.69753 17.8477 7.5804 19.75 9.89477 19.75C12.2092 19.75 14.0921 17.8477 14.0921 15.5094C14.0921 13.5366 12.752 11.8742 10.9431 11.4026V10.2873L21.348 10.2872V11.4029C19.5399 11.875 18.2002 13.537 18.2002 15.5094C18.2002 17.8477 20.0831 19.75 22.3975 19.75C24.7118 19.75 26.5947 17.8477 26.5947 15.5094C26.5947 13.5362 25.254 11.8736 23.4447 11.4023V10.2872H29.1239C29.7143 10.2872 30.1945 10.7725 30.1945 11.369V17.704C27.7077 18.5047 25.9011 20.8601 25.9011 23.6346V26.3565H21.448C19.9145 26.3565 18.667 27.6169 18.667 29.1662V39.9709H2.09663V11.369C2.09663 10.7726 2.57685 10.2873 3.1672 10.2873ZM9.89477 16.1129C10.4737 16.1129 10.9431 15.6387 10.9431 15.0537V13.673C11.5711 14.0404 11.9955 14.725 11.9955 15.5093C11.9955 16.6795 11.0531 17.6316 9.89477 17.6316C8.73644 17.6316 7.79416 16.6795 7.79416 15.5093C7.79416 14.725 8.21857 14.0403 8.84646 13.673V15.0537C8.84646 15.6387 9.31581 16.1129 9.89477 16.1129ZM22.3963 16.1128C22.9752 16.1128 23.4446 15.6386 23.4446 15.0536V13.6724C24.0731 14.0397 24.498 14.7247 24.498 15.5094C24.498 16.6796 23.5556 17.6317 22.3974 17.6317C21.239 17.6317 20.2967 16.6796 20.2967 15.5094C20.2967 14.7255 20.7207 14.0412 21.3479 13.6738V15.0537C21.3479 15.6386 21.8173 16.1128 22.3963 16.1128ZM2.09663 46.8V42.0893H18.6669V47.1901C18.6669 47.4286 18.6966 47.6602 18.7523 47.8816H3.1672C2.57685 47.8817 2.09663 47.3964 2.09663 46.8ZM43.3579 47.1901C43.3579 47.5714 43.0509 47.8816 42.6736 47.8816H21.4479C21.0705 47.8816 20.7635 47.5714 20.7635 47.1901V29.1663C20.7635 28.7851 21.0705 28.4749 21.4479 28.4749H25.901V30.8125C25.901 31.3974 26.3704 31.8716 26.9493 31.8716C27.5283 31.8716 27.9976 31.3974 27.9976 30.8125V28.4749L36.1239 28.4748V30.8125C36.1239 31.3974 36.5932 31.8716 37.1722 31.8716C37.7511 31.8716 38.2205 31.3974 38.2205 30.8125V28.4748H42.6736C43.051 28.4748 43.3579 28.785 43.3579 29.1663V47.1901Z" fill="#4BD783"/>
                        </svg>
                    </div>
                    <div class="partnership-services-section--list_item--description">
                        <p>Предоставление образцов<br/>на&nbsp;срок до&nbsp;1&nbsp;месяца</p>
                    </div>
                </div>
                <div class="partnership-services-section--list_item">
                    <div class="partnership-services-section--list_item--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 54 54" fill="none">
                            <path d="M9.69231 52.0001H5.84615C4.82609 52.0001 3.84781 51.5949 3.12651 50.8736C2.40522 50.1523 2 49.1741 2 48.154V17.3848H52V48.154C52 49.1741 51.5948 50.1523 50.8735 50.8736C50.1522 51.5949 49.1739 52.0001 48.1538 52.0001H44.3077" stroke="#FF5B36" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M17.3828 42.3848L26.9982 52.0001L36.6136 42.3848" stroke="#FF5B36" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M27 51.9998V28.9229" stroke="#FF5B36" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M43.5 3.92308C43.1711 3.35284 42.7013 2.87657 42.1356 2.53986C41.5699 2.20315 40.9273 2.01726 40.2692 2H13.7308C13.0727 2.01726 12.4301 2.20315 11.8644 2.53986C11.2987 2.87657 10.8289 3.35284 10.5 3.92308L2 17.3846H52L43.5 3.92308Z" stroke="#FF5B36" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M27 2V17.3846" stroke="#FF5B36" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="partnership-services-section--list_item--description">
                        <p>Возможность аннулирования заказа<br/>и&nbsp;возврата небрендированной<br/>продукции</p>
                    </div>
                </div>
                <div class="partnership-services-section--list_item">
                    <div class="partnership-services-section--list_item--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="56" height="50" viewBox="0 0 56 50" fill="none">
                            <path d="M3.78788 3.74824V33.7342H51.7677V3.74824H3.78788ZM53.0303 0C54.425 0 55.5556 1.11876 55.5556 2.49883V34.9836C55.5556 36.3636 54.425 37.4824 53.0303 37.4824L29.3333 37.4806V46.25L38.8816 46.2518C39.9276 46.2518 40.7755 47.0908 40.7755 48.1259C40.7755 49.1609 39.9276 50 38.8816 50H16.6145C15.5685 50 14.7205 49.1609 14.7205 48.1259C14.7205 47.0908 15.5685 46.2518 16.6145 46.2518L25.5472 46.25V37.4806L2.52525 37.4824C1.13059 37.4824 0 36.3636 0 34.9836V2.49883C0 1.11876 1.13059 0 2.52525 0H53.0303Z" fill="#744A9E"/>
                        </svg>
                    </div>
                    <div class="partnership-services-section--list_item--description">
                        <p>Подготовка<br/>технического макета</p>
                    </div>
                </div>
                <div class="partnership-services-section--list_item">
                    <div class="partnership-services-section--list_item--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="61" height="50" viewBox="0 0 61 50" fill="none">
                            <mask id="mask0_616_13719" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="0" width="61" height="50">
                                <path d="M60.1495 0H0V50H60.1495V0Z" fill="white"/>
                            </mask>
                            <g mask="url(#mask0_616_13719)">
                                <path d="M59.8314 5.50935L54.6512 0.327737C54.4723 0.151562 54.2379 0.0427483 53.9918 0.0168403L54.0086 0L53.8467 0.00388621C53.5643 0.0103632 53.2974 0.123063 53.0954 0.32126L48.9358 4.4808C48.7972 4.61941 48.7 4.78911 48.6508 4.97435L39.2889 14.3363C38.8576 14.7638 38.855 15.462 39.2889 15.8998C39.5027 16.1162 39.7851 16.2237 40.0688 16.2237C40.3524 16.2237 40.6348 16.1162 40.8525 15.8998L49.8219 6.93041L53.4218 10.5304L35.3328 28.6194L31.7004 24.9871L35.5789 21.1086C36.0038 20.6798 36.0038 19.9829 35.5789 19.5529C35.3704 19.3443 35.0931 19.229 34.7978 19.229C34.5024 19.229 34.2252 19.3443 34.0166 19.5529L33.4946 20.0671L29.335 24.2266C29.1433 24.4184 29.0319 24.6671 29.0151 24.9339L26.0901 32.7543L22.1896 36.6547L18.7179 29.7101C18.5858 29.4458 18.3591 29.2489 18.0793 29.1556C17.7995 29.0624 17.5003 29.0831 17.236 29.2153C16.9743 29.3461 16.7774 29.5702 16.6829 29.8487L12.3756 42.6913C11.233 40.4062 10.2046 38.0356 9.31848 35.6404C11.645 30.5845 13.3718 25.9819 14.4521 21.9584C15.7022 17.3299 16.0299 13.8025 15.4535 11.1742C15.1128 9.61839 14.4483 8.40846 13.478 7.57552C12.4598 6.69724 11.0465 6.23348 9.39357 6.23348H9.03607L9.0892 6.25291C7.71475 6.32028 6.49578 6.79957 5.54753 7.65065C4.60578 8.49267 3.90367 9.69607 3.46064 11.2286C2.69117 13.8686 2.63936 17.3558 3.3013 21.8911C3.90496 25.9677 5.11487 30.5715 6.89476 35.5744C4.86745 39.9192 2.60049 44.2134 0.158655 48.338C-0.152241 48.8613 0.0200473 49.5401 0.543391 49.8523C0.715679 49.9534 0.906105 50 1.09394 50C1.46961 50 1.8375 49.8096 2.04347 49.4676C4.24954 45.799 6.28462 41.7237 8.02564 38.1743L8.30675 38.888C9.33527 41.4983 10.3988 44.1966 11.5815 46.3262C11.7266 46.584 11.9624 46.7693 12.2461 46.8483C12.5297 46.9286 12.829 46.8923 13.0855 46.7472C13.3238 46.6138 13.5026 46.3975 13.5907 46.1384L17.9368 33.1002L20.9006 39.0046C21.174 39.55 21.8398 39.7702 22.3839 39.4981C22.4888 39.4451 22.5847 39.3764 22.6689 39.2922L27.7495 34.2116L35.7732 31.2024C36.0323 31.1053 36.2408 30.9174 36.3665 30.6726L55.0488 11.9903L55.5787 12.5201L48.0497 20.0489C47.6184 20.479 47.6184 21.1798 48.0497 21.6125C48.2635 21.825 48.5459 21.9312 48.827 21.9312C49.1081 21.9312 49.3905 21.825 49.6042 21.6125L57.9247 13.2921C58.3495 12.8634 58.3495 12.1664 57.9247 11.7377L56.5424 10.3554L59.8328 7.06513C60.2576 6.63636 60.2576 5.93943 59.8328 5.50935H59.8314ZM57.5113 6.30085L54.9193 8.89295L51.287 5.26063L53.8804 2.66723L57.5127 6.29954L57.5113 6.30085ZM30.5994 27.0558L33.312 29.7684L28.9671 31.4019L30.6006 27.0558H30.5994ZM7.01264 9.2958C7.65775 8.72066 8.42334 8.45251 9.42205 8.45251H9.48688V8.44085C10.5983 8.45121 11.4131 8.71159 12.0479 9.2608C12.6554 9.78545 13.0673 10.5679 13.307 11.6547C13.785 13.8466 13.4417 17.3053 12.342 21.3962C11.4014 24.8899 9.73553 28.9147 8.21348 32.4149C6.79242 27.9924 5.9245 24.5401 5.48665 21.5607C4.89206 17.5242 4.92315 14.0771 5.57474 11.8555C5.90895 10.7091 6.39344 9.84768 7.01135 9.2958H7.01264Z" fill="#EF4A85"/>
                                <path d="M37.3615 18.8145H37.3667C37.9716 18.8119 38.4651 18.3183 38.4677 17.7133C38.4703 17.1045 37.9767 16.6071 37.3667 16.6045H37.3627C36.7552 16.6045 36.2604 17.0967 36.2578 17.7133C36.2578 18.0087 36.3743 18.2859 36.5842 18.4932C36.7928 18.7005 37.0687 18.8132 37.3615 18.8132V18.8145Z" fill="#EF4A85"/>
                            </g>
                        </svg>
                    </div>
                    <div class="partnership-services-section--list_item--description">
                        <p>Согласование сигнального образца<br/>перед запуском партии в производство</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="partnership-services-section second">
        <div class="container">
            <div class="partnership-services-section--title">
                <h3 class="title">Наши супер сервисы в рамках партнерской программы</h3>
            </div>
            <div class="partnership-services-section--list">
                <div class="partnership-services-section--list_item">
                    <div class="partnership-services-section--list_item--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="52" height="54" viewBox="0 0 52 54" fill="none">
                            <path d="M2.3231 49.7192L7.45801 13.4753C7.59775 12.489 8.44205 11.7559 9.43823 11.7559H42.1284C43.1013 11.7559 43.9331 12.456 44.0991 13.4146L50.3751 49.6585C50.5868 50.8813 49.6454 51.9998 48.4044 51.9998H4.30333C3.08716 51.9998 2.1525 50.9234 2.3231 49.7192Z" stroke="#57B0EA" stroke-width="3"/>
                            <path d="M19.6797 16.6341V8.09756C19.6797 4.72997 22.4097 2 25.7772 2V2C29.1448 2 31.8748 4.72997 31.8748 8.09756V16.6341" stroke="#57B0EA" stroke-width="3"/>
                            <circle cx="19.6816" cy="18.4628" r="1.54878" stroke="#57B0EA" stroke-width="3"/>
                            <circle cx="31.8769" cy="18.4628" r="1.54878" stroke="#57B0EA" stroke-width="3"/>
                            <path d="M21.3125 37.6367H30.2556" stroke="#57B0EA" stroke-width="3"/>
                            <path d="M19.0781 42.8535L24.662 29.8244C25.0853 28.8367 26.4855 28.8367 26.9088 29.8244L32.4928 42.8535" stroke="#57B0EA" stroke-width="3"/>
                        </svg>
                    </div>
                    <div class="partnership-services-section--list_item--description">
                        <p>Подготовка макета-привязки&nbsp;—<br/>при&nbsp;заказе от&nbsp;100 000&nbsp;₽</p>
                    </div>
                </div>
                <div class="partnership-services-section--list_item">
                    <div class="partnership-services-section--list_item--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="41" height="52" viewBox="0 0 41 52" fill="none">
                            <path d="M10.5 13H28.5V28" stroke="#FBB040" stroke-width="3"/>
                            <path d="M18 44.5H33M41 44.5H33M11 28.5H33V44.5" stroke="#FBB040" stroke-width="3"/>
                            <path d="M0 2H6C8.76142 2 11 4.23858 11 7V37" stroke="#FBB040" stroke-width="3"/>
                            <circle cx="11" cy="44.5" r="6" stroke="#FBB040" stroke-width="3"/>
                        </svg>
                    </div>
                    <div class="partnership-services-section--list_item--description">
                        <p>Комплектация заказа/переупаковка<br/>в&nbsp;рамках задачи брендирования&nbsp;—<br/>при&nbsp;заказе от&nbsp;500 000&nbsp;₽</p>
                    </div>
                </div>
                <div class="partnership-services-section--list_item">
                    <div class="partnership-services-section--list_item--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="46" height="50" viewBox="0 0 46 50" fill="none">
                            <path d="M42.6736 26.3564H38.2205V23.6346C38.2205 20.281 35.581 17.5401 32.2909 17.417V11.369C32.2909 9.60446 30.8702 8.16883 29.1237 8.16883H23.4446V7.37465C23.4446 3.30824 20.1702 0 16.1455 0C12.1207 0 8.84636 3.30824 8.84636 7.37465V8.16893H3.1672C1.42081 8.16893 0 9.60446 0 11.369V46.8C0 48.5645 1.42081 50 3.1672 50H19.6512C19.905 50 20.1377 49.9088 20.319 49.7572C20.6642 49.9127 21.046 50 21.4479 50H42.6736C44.2071 50 45.4545 48.7395 45.4545 47.1902V29.1663C45.4545 27.6169 44.2071 26.3564 42.6736 26.3564ZM36.1239 23.6346V26.3564L27.9976 26.3565V23.6346C27.9976 21.3709 29.8203 19.5295 32.0607 19.5295C34.3011 19.5295 36.1239 21.3709 36.1239 23.6346ZM10.943 7.37465C10.943 4.47636 13.2768 2.11834 16.1455 2.11834C19.0141 2.11834 21.3479 4.47636 21.3479 7.37465V8.16883L10.943 8.16893V7.37465ZM3.1672 10.2873H8.84646V11.4026C7.03767 11.8742 5.69753 13.5366 5.69753 15.5094C5.69753 17.8477 7.5804 19.75 9.89477 19.75C12.2092 19.75 14.0921 17.8477 14.0921 15.5094C14.0921 13.5366 12.752 11.8742 10.9431 11.4026V10.2873L21.348 10.2872V11.4029C19.5399 11.875 18.2002 13.537 18.2002 15.5094C18.2002 17.8477 20.0831 19.75 22.3975 19.75C24.7118 19.75 26.5947 17.8477 26.5947 15.5094C26.5947 13.5362 25.254 11.8736 23.4447 11.4023V10.2872H29.1239C29.7143 10.2872 30.1945 10.7725 30.1945 11.369V17.704C27.7077 18.5047 25.9011 20.8601 25.9011 23.6346V26.3565H21.448C19.9145 26.3565 18.667 27.6169 18.667 29.1662V39.9709H2.09663V11.369C2.09663 10.7726 2.57685 10.2873 3.1672 10.2873ZM9.89477 16.1129C10.4737 16.1129 10.9431 15.6387 10.9431 15.0537V13.673C11.5711 14.0404 11.9955 14.725 11.9955 15.5093C11.9955 16.6795 11.0531 17.6316 9.89477 17.6316C8.73644 17.6316 7.79416 16.6795 7.79416 15.5093C7.79416 14.725 8.21857 14.0403 8.84646 13.673V15.0537C8.84646 15.6387 9.31581 16.1129 9.89477 16.1129ZM22.3963 16.1128C22.9752 16.1128 23.4446 15.6386 23.4446 15.0536V13.6724C24.0731 14.0397 24.498 14.7247 24.498 15.5094C24.498 16.6796 23.5556 17.6317 22.3974 17.6317C21.239 17.6317 20.2967 16.6796 20.2967 15.5094C20.2967 14.7255 20.7207 14.0412 21.3479 13.6738V15.0537C21.3479 15.6386 21.8173 16.1128 22.3963 16.1128ZM2.09663 46.8V42.0893H18.6669V47.1901C18.6669 47.4286 18.6966 47.6602 18.7523 47.8816H3.1672C2.57685 47.8817 2.09663 47.3964 2.09663 46.8ZM43.3579 47.1901C43.3579 47.5714 43.0509 47.8816 42.6736 47.8816H21.4479C21.0705 47.8816 20.7635 47.5714 20.7635 47.1901V29.1663C20.7635 28.7851 21.0705 28.4749 21.4479 28.4749H25.901V30.8125C25.901 31.3974 26.3704 31.8716 26.9493 31.8716C27.5283 31.8716 27.9976 31.3974 27.9976 30.8125V28.4749L36.1239 28.4748V30.8125C36.1239 31.3974 36.5932 31.8716 37.1722 31.8716C37.7511 31.8716 38.2205 31.3974 38.2205 30.8125V28.4748H42.6736C43.051 28.4748 43.3579 28.785 43.3579 29.1663V47.1901Z" fill="#4BD783"/>
                        </svg>
                    </div>
                    <div class="partnership-services-section--list_item--description">
                        <p>Предоставление новинок и&nbsp;образцов без&nbsp;условия<br/>возврата&nbsp;— для&nbsp;партнёров, достигших объёма<br/>продаж более 3 000 000&nbsp;₽</p>
                    </div>
                </div>
                <div class="partnership-services-section--list_item">
                    <div class="partnership-services-section--list_item--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 54 54" fill="none">
                            <path d="M48.1538 11.6152H5.84615C3.72198 11.6152 2 13.3372 2 15.4614V26.9999C2 29.124 3.72198 30.846 5.84615 30.846H48.1538C50.278 30.846 52 29.124 52 26.9999V15.4614C52 13.3372 50.278 11.6152 48.1538 11.6152Z" stroke="#FF5B36" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M48.1514 30.8457V48.1534C48.1514 49.1735 47.7462 50.1517 47.0249 50.873C46.3036 51.5943 45.3254 51.9995 44.3053 51.9995H9.6899C8.66984 51.9995 7.69156 51.5943 6.97026 50.873C6.24897 50.1517 5.84375 49.1735 5.84375 48.1534V30.8457" stroke="#FF5B36" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M27 11.6152V51.9999" stroke="#FF5B36" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M38.5379 2L26.9994 11.6154L15.4609 2" stroke="#FF5B36" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="partnership-services-section--list_item--description">
                        <p>Бонус менеджерам партнёров при&nbsp;переходе<br/>на&nbsp;очередной рубеж продаж</p>
                    </div>
                </div>
                <div class="partnership-services-section--list_item">
                    <div class="partnership-services-section--list_item--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="54" height="54" viewBox="0 0 54 54" fill="none">
                            <path d="M51.1564 36.1955L36.1955 51.1564C35.6547 51.6966 34.9216 52 34.1572 52C33.3928 52 32.6596 51.6966 32.1188 51.1564L2.4278 21.4654C2.27861 21.3228 2.1632 21.1486 2.09 20.9557C2.0168 20.7627 1.98767 20.5558 2.00475 20.3501L4.27388 5.61997C4.29239 5.26897 4.44015 4.93723 4.68869 4.68869C4.93723 4.44015 5.26897 4.29239 5.61997 4.27388L20.3501 2.00475C20.5558 1.98767 20.7627 2.0168 20.9557 2.09C21.1486 2.1632 21.3228 2.27861 21.4654 2.4278L51.1564 32.1188C51.6966 32.6596 52 33.3928 52 34.1572C52 34.9216 51.6966 35.6547 51.1564 36.1955V36.1955Z" stroke="#744A9E" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15.88 17.8118C16.9421 17.8118 17.803 16.9509 17.803 15.8888C17.803 14.8268 16.9421 13.9658 15.88 13.9658C14.818 13.9658 13.957 14.8268 13.957 15.8888C13.957 16.9509 14.818 17.8118 15.88 17.8118Z" stroke="#744A9E" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="partnership-services-section--list_item--description">
                        <p>Дополнительная скидка 2%<br/>при&nbsp;предоплате 50% на&nbsp;товар в&nbsp;пути</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?$APPLICATION->IncludeComponent(
        "bitrix:form.result.new",
        "onlineservice-feedback-form-type-2",
        Array(
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
            "CHAIN_ITEM_LINK" => "",
            "CHAIN_ITEM_TEXT" => "",
            "CONSENT_URL" => "",
            "EDIT_URL" => "result_edit.php",
            "IGNORE_CUSTOM_TEMPLATE" => "N",
            "LIST_URL" => "result_list.php",
            "SEF_MODE" => "N",
            "SUCCESS_URL" => "",
            "USE_EXTENDED_ERRORS" => "N",
            "VARIABLE_ALIASES" => Array(
                "RESULT_ID" => "RESULT_ID",
                "WEB_FORM_ID" => "WEB_FORM_ID"
            ),
            "WEB_FORM_ID" => "2"
        )
    );?>
</main>