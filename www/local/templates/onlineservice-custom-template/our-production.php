<?php
$APPLICATION->SetTitle("Наше производство, служба качества");
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
    <div class="container">
        <div class="alert-section">
            <div class="alert-card">
                <p>Наличие собственного производства позволяет нам предложить нашим клиентам комплексное обслуживание – от идеи и разработки дизайна до финального нанесения и отгрузки продукции. Это означает, что вся работа выполняется в одном месте, что значительно упрощает коммуникацию и сокращает время на выполнение заказа.</p>
            </div>
        </div>
    </div>
    <section class="content-section">
        <div class="container">
            <div class="content-section--title_wrapper">
                <h2 class="title">Собственное производство и служба качества</h2>
            </div>

            <div class="content-section--services-list">
                <div class="content-section--services-list__item">
                    <div class="list__item--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
                            <circle cx="40" cy="40" r="40" fill="#4BD783"/>
                            <path d="M27.9773 28.9566V44.6092H53.0227V28.9566H27.9773ZM53.6818 27C54.4098 27 55 27.584 55 28.3044V45.2614C55 45.9818 54.4098 46.5658 53.6818 46.5658L41.312 46.5648V51.1425L46.2962 51.1434C46.8422 51.1434 47.2848 51.5814 47.2848 52.1217C47.2848 52.662 46.8422 53.1 46.2962 53.1H34.6728C34.1267 53.1 33.6841 52.662 33.6841 52.1217C33.6841 51.5814 34.1267 51.1434 34.6728 51.1434L39.3356 51.1425V46.5648L27.3182 46.5658C26.5902 46.5658 26 45.9818 26 45.2614V28.3044C26 27.584 26.5902 27 27.3182 27H53.6818Z" fill="white"/>
                        </svg>
                    </div>
                    <div class="list__item--title">
                        <h4 class="title">Широкий спектр технологий нанесения</h4>
                    </div>
                    <div class="list__item--description">
                        <p class="description">
                            Наше производство предлагает разнообразные методы нанесения,<br/> что позволяет нам выбирать наиболее подходящий способ для<br/>каждой конкретной задачи: от печати до трафаретного нанесения и<br/>лазерной гравировки. Это делает наши услуги более<br/>универсальными и востребованными.
                        </p>
                    </div>
                </div>
                <div class="content-section--services-list__item">
                    <div class="list__item--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
                            <circle cx="40" cy="40" r="40" fill="#4BD783"/>
                            <path d="M27.9773 28.9566V44.6092H53.0227V28.9566H27.9773ZM53.6818 27C54.4098 27 55 27.584 55 28.3044V45.2614C55 45.9818 54.4098 46.5658 53.6818 46.5658L41.312 46.5648V51.1425L46.2962 51.1434C46.8422 51.1434 47.2848 51.5814 47.2848 52.1217C47.2848 52.662 46.8422 53.1 46.2962 53.1H34.6728C34.1267 53.1 33.6841 52.662 33.6841 52.1217C33.6841 51.5814 34.1267 51.1434 34.6728 51.1434L39.3356 51.1425V46.5648L27.3182 46.5658C26.5902 46.5658 26 45.9818 26 45.2614V28.3044C26 27.584 26.5902 27 27.3182 27H53.6818Z" fill="white"/>
                        </svg>
                    </div>
                    <div class="list__item--title">
                        <h4 class="title">Подготовка технических макетов</h4>
                    </div>
                    <div class="list__item--description">
                        <p class="description">
                            В процессе подготовки макетов к печати мы уделяем особое внимание<br/>техническим требованиям производства, чтобы обеспечить высокое<br/>качество конечного продукта и избежать возможных ошибок.
                        </p>
                    </div>
                </div>
                <div class="content-section--services-list__item">
                    <div class="list__item--icon">
                        <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="40" cy="40" r="40" fill="#744A9E"/>
                            <path d="M32.4033 37.4859L38.0678 28.4309C38.2873 28.073 38.5958 27.7781 38.9632 27.5748C39.3306 27.3716 39.7443 27.267 40.1641 27.2711V27.2711C40.4948 27.2592 40.8244 27.3137 41.1337 27.4313C41.4429 27.549 41.7254 27.7275 41.9645 27.9562C42.2036 28.1849 42.3944 28.4592 42.5257 28.7629C42.6569 29.0666 42.726 29.3936 42.7287 29.7245V36.2147H52.519C52.8807 36.2264 53.2359 36.3146 53.5611 36.4734C53.8864 36.6322 54.1743 36.8581 54.406 37.1362C54.6377 37.4143 54.808 37.7382 54.9055 38.0868C55.0031 38.4354 55.0258 38.8007 54.9721 39.1587L53.188 50.667C53.1119 51.282 52.8138 51.8481 52.3497 52.2588C51.8857 52.6695 51.2876 52.8966 50.668 52.8973H36.0384C35.342 52.9 34.6546 52.7396 34.0313 52.4289L32.4256 51.626" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M32.4033 37.4863V51.5595" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M27.1151 37.4863H32.4004V51.5595H27.1151C26.8193 51.5595 26.5357 51.442 26.3266 51.2329C26.1175 51.0238 26 50.7401 26 50.4444V38.6015C26 38.3057 26.1175 38.0221 26.3266 37.8129C26.5357 37.6038 26.8193 37.4863 27.1151 37.4863V37.4863Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="list__item--title">
                        <h4 class="title">Скорость и гибкость производства</h4>
                    </div>
                    <div class="list__item--description">
                        <p class="description">
                            Обладая собственным оборудованием, мы можем оперативно<br/>реагировать на изменения и корректировать сроки выполнения проекта.<br/>Если возникают непредвиденные обстоятельства или необходимость<br/>изменения дизайна, мы можем быстро внести коррективы, сохранив<br/>качество и сроки.
                        </p>
                    </div>
                </div>
                <div class="content-section--services-list__item">
                    <div class="list__item--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
                            <circle cx="40" cy="40" r="40" fill="#4BD783"/>
                            <path d="M34.9062 36.5547L40.2845 40.5893C40.6177 40.8392 41.0331 40.9539 41.4473 40.9104C41.8615 40.8669 42.244 40.6685 42.5181 40.3549L53.065 28.3008" stroke="white" stroke-width="2" stroke-linecap="round"/>
                            <path d="M54.7143 39.8569C54.7143 42.9612 53.742 45.9876 51.9338 48.511C50.1256 51.0344 47.5724 52.928 44.6328 53.9258C41.6931 54.9237 38.5148 54.9757 35.5441 54.0745C32.5734 53.1733 29.9597 51.3642 28.0699 48.9013C26.1802 46.4384 25.1094 43.4454 25.0079 40.3427C24.9065 37.24 25.7795 34.1834 27.5043 31.6023C29.2291 29.0212 31.7191 27.0453 34.6246 25.9519C37.53 24.8586 40.705 24.7028 43.7035 25.5065" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="list__item--title">
                        <h4 class="title">Контроль качества на всех этапах</h4>
                    </div>
                    <div class="list__item--description">
                        <p class="description">
                            Внедренный многоступенчатый контроль качества позволяет нам<br/>гарантировать, что каждый этап производства проходит проверки и<br/>соответствует установленным стандартам. Мы следим за качеством на<br/>этапе приемки товара, в процессе брендирования и перед отгрузкой,<br/>что снижает вероятность ошибок.
                        </p>
                    </div>
                </div>
                <div class="content-section--services-list__item">
                    <div class="list__item--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
                            <circle cx="40" cy="40" r="40" fill="#FF5A36"/>
                            <path d="M40.3845 55.0004C47.1606 55.0004 52.6537 49.5073 52.6537 42.7311C52.6537 35.955 47.1606 30.4619 40.3845 30.4619C33.6084 30.4619 28.1152 35.955 28.1152 42.7311C28.1152 49.5073 33.6084 55.0004 40.3845 55.0004Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M37.0381 26H43.7304" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M40.3848 26V30.4615" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M37.0381 38.2695L40.3842 42.7311H45.9612" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M51.5381 29.3457L53.7689 31.5765" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M29.2308 29.3457L27 31.5765" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="list__item--title">
                        <h4 class="title">Экономия времени и ресурсов</h4>
                    </div>
                    <div class="list__item--description">
                        <p class="description">
                            Клиенты могут сосредоточиться на своем бизнесе, не отвлекаясь на<br/>поиск и координацию различных подрядчиков. Мы берем на себя всю<br/>организацию процесса, что значительно освобождает время и силы<br/>наших клиентов для других задач.
                        </p>
                    </div>
                </div>
                <div class="content-section--services-list__item">
                    <div class="list__item--icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
                            <circle cx="40" cy="40" r="40" fill="#57B0EA"/>
                            <path d="M36.5385 45.3075L30.7692 32.6152L25 45.3075C25 46.8376 25.6078 48.3051 26.6898 49.387C27.7717 50.4689 29.2391 51.0768 30.7692 51.0768C32.2993 51.0768 33.7668 50.4689 34.8487 49.387C35.9306 48.3051 36.5385 46.8376 36.5385 45.3075Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M54.9994 45.3075L49.2302 32.6152L43.4609 45.3075C43.4609 46.8376 44.0688 48.3051 45.1507 49.387C46.2326 50.4689 47.7001 51.0768 49.2302 51.0768C50.7603 51.0768 52.2277 50.4689 53.3096 49.387C54.3916 48.3051 54.9994 46.8376 54.9994 45.3075Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M27.3066 32.6152H52.6913" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M40.001 32.6154V28" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="list__item--title">
                        <h4 class="title">Оптимизация ваших затрат</h4>
                    </div>
                    <div class="list__item--description">
                        <p class="description">
                            Собственное производство снижает издержки на аутсорсинг, позволяя<br/>нам предложить более конкурентные цены на услуги. Вы получите<br/>более выгодные условия, заказывая у нас полный комплекс услуг.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="products-card-section">
        <style>
            .products-card-section--list_item:nth-child(1) .products-card-section--list_item--name{
                background-color: #57B0EA;
            }
            .products-card-section--list_item:nth-child(2) .products-card-section--list_item--name{
                background-color: #EF4A85;
            }
            .products-card-section--list_item:nth-child(3) .products-card-section--list_item--name{
                background-color: #FF5B36;
            }
            .products-card-section--list_item:nth-child(4) .products-card-section--list_item--name{
                background-color: #744A9E;
            }
            .products-card-section--list_item:nth-child(5) .products-card-section--list_item--name{
                background-color: #4BD783;
            }
            .products-card-section--list_item:nth-child(6) .products-card-section--list_item--name{
                background-color: #57B0EA;
            }
            .products-card-section--list_item:nth-child(7) .products-card-section--list_item--name{
                background-color: #FBB040;
            }
            .products-card-section--list_item:nth-child(8) .products-card-section--list_item--name{
                background-color: #EF4A85;
            }
            .products-card-section--list_item:nth-child(9) .products-card-section--list_item--name{
                background-color: #744A9E;
            }
        </style>
        <div class="container">
            <div class="products-card-section--title_wrapper">
                <h3 class="title">Возможности нашего производства</h3>
            </div>

            <div class="products-card-section-list">
                <a href="#" class="products-card-section--list_item">
                    <div class="products-card-section--list_item--image--wrapper">
                        <div class="products-card-section--list_item--image">
                            <img src="/local/templates/onlineservice-custom-template/components/our-production/assets/product1.png" alt="Дизайн студия" class="image">
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
                <a href="#" class="products-card-section--list_item">
                    <div class="products-card-section--list_item--image--wrapper">
                        <div class="products-card-section--list_item--image">
                            <img src="/local/templates/onlineservice-custom-template/components/our-production/assets/product2.png" alt="Дизайн студия" class="image">
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
                <a href="#" class="products-card-section--list_item">
                    <div class="products-card-section--list_item--image--wrapper">
                        <div class="products-card-section--list_item--image">
                            <img src="/local/templates/onlineservice-custom-template/components/our-production/assets/product3.png" alt="Лазерная гравировка" class="image">
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
                <a href="#" class="products-card-section--list_item">
                    <div class="products-card-section--list_item--image--wrapper">
                        <div class="products-card-section--list_item--image">
                            <img src="/local/templates/onlineservice-custom-template/components/our-production/assets/product4.png" alt="УФ печать" class="image">
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
                <a href="#" class="products-card-section--list_item">
                    <div class="products-card-section--list_item--image--wrapper">
                        <div class="products-card-section--list_item--image">
                            <img src="/local/templates/onlineservice-custom-template/components/our-production/assets/product5.png" alt="Тиснение" class="image">
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
                <a href="#" class="products-card-section--list_item">
                    <div class="products-card-section--list_item--image--wrapper">
                        <div class="products-card-section--list_item--image">
                            <img src="/local/templates/onlineservice-custom-template/components/our-production/assets/product6.png" alt="Сублимационная печать" class="image">
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
                <a href="#" class="products-card-section--list_item">
                    <div class="products-card-section--list_item--image--wrapper">
                        <div class="products-card-section--list_item--image">
                            <img src="/local/templates/onlineservice-custom-template/components/our-production/assets/product7.png" alt="Шелкография на ткани" class="image">
                        </div>
                        <div class="products-card-section--list_item--image-action">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
                                <circle cx="25" cy="25" r="25" fill="white"/>
                                <path d="M22.8182 30.1166L27 25.0583L22.8182 20" stroke="#222222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="products-card-section--list_item--name">
                        <span class="name">Шелкография на ткани</span>
                    </div>
                </a>
                <a href="#" class="products-card-section--list_item">
                    <div class="products-card-section--list_item--image--wrapper">
                        <div class="products-card-section--list_item--image">
                            <img src="/local/templates/onlineservice-custom-template/components/our-production/assets/product8.png" alt="Печать на ежедневниках" class="image">
                        </div>
                        <div class="products-card-section--list_item--image-action">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
                                <circle cx="25" cy="25" r="25" fill="white"/>
                                <path d="M22.8182 30.1166L27 25.0583L22.8182 20" stroke="#222222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="products-card-section--list_item--name">
                        <span class="name">Печать на ежедневниках</span>
                    </div>
                </a>
                <a href="#" class="products-card-section--list_item">
                    <div class="products-card-section--list_item--image--wrapper">
                        <div class="products-card-section--list_item--image">
                            <img src="/local/templates/onlineservice-custom-template/components/our-production/assets/product9.png" alt="Печать на текстиле оптом" class="image">
                        </div>
                        <div class="products-card-section--list_item--image-action">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50" fill="none">
                                <circle cx="25" cy="25" r="25" fill="white"/>
                                <path d="M22.8182 30.1166L27 25.0583L22.8182 20" stroke="#222222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                    <div class="products-card-section--list_item--name">
                        <span class="name">Печать на текстиле оптом</span>
                    </div>
                </a>
            </div>
        </div>
    </section>
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