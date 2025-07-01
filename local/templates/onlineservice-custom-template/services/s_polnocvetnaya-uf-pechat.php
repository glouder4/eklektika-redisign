<?php
$APPLICATION->SetTitle("УФ-печать");

$asset->addCss("/local/templates/onlineservice-custom-template/services/styles/styles.css");
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
                <div class="alert-card--description">
                    <p>Ультрафиолетовая печать (УФ-печать) применяется для изготовления рекламной полиграфической и иной продукции, а также для персонализации сувениров и брендирования корпоративных подарков. Полноцветное, насыщенное, яркое изображение с возможностью 3D-эффекта можно наносить практически на любые материалы, от бумаги до стекла и металла, с высокой степенью детализации и корректной цветопередачей.</p>
                </div>
            </div>
        </div>
    </div>

    <section class="products-card-section">
        <style>
            .products-card-section--list_item--name{
                background-color: #744A9E;
            }
        </style>
        <div class="container">
            <div class="products-card-section--title_wrapper">
                <h3 class="title">Примеры  УФ печати на разных материалах и разных видах сувенирной продукции </h3>
            </div>

            <div class="products-card-section-list">
                <a href="#" class="products-card-section--list_item">
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
                <a href="#" class="products-card-section--list_item">
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
                <a href="#" class="products-card-section--list_item">
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
                <a href="#" class="products-card-section--list_item">
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
                <a href="#" class="products-card-section--list_item">
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
                <a href="#" class="products-card-section--list_item">
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
        </div>
    </section>

    <section class="service-description-list">
        <div class="container">
            <div class="service-description--title">
                <h4 class="title">Преимущества метода нанесения</h4>
            </div>
            <div class="service-description-list_items">
                <div class="service-description-list--item">
                    <div class="service-description-list--item_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="33" viewBox="0 0 32 33" fill="none">
                            <path d="M10.9062 12.6891L16.2845 16.7706C16.6177 17.0234 17.0331 17.1395 17.4473 17.0955C17.8615 17.0515 18.244 16.8508 18.5181 16.5335L29.065 4.33887" stroke="#744A9E" stroke-width="2" stroke-linecap="round"/>
                            <path d="M30.7143 16.0301C30.7143 19.1706 29.742 22.2323 27.9338 24.7851C26.1256 27.3379 23.5724 29.2536 20.6328 30.263C17.6931 31.2725 14.5148 31.3251 11.5441 30.4134C8.57345 29.5017 5.95968 27.6715 4.06994 25.1799C2.18019 22.6883 1.10939 19.6604 1.00794 16.5215C0.906487 13.3827 1.77947 10.2905 3.50429 7.67931C5.22911 5.06813 7.71911 3.06911 10.6246 1.96304C13.53 0.856956 16.705 0.699377 19.7035 1.51243" stroke="#744A9E" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="service-description-list--item--data">
                        <div class="service-description-list--item_title">
                            <span>Высокая скорость производства</span>
                        </div>
                        <div class="service-description-list--item_description">
                            <p>Наносимые краски затвердевают практически моментально. При печати не используются какие‑либо промежуточные шаблоны, не требуются и подготовительные операции. Поэтому брендирование сувенирной и промопродукции можно произвести в кратчайшие сроки.</p>
                        </div>
                    </div>
                </div>
                <div class="service-description-list--item">
                    <div class="service-description-list--item_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="33" viewBox="0 0 32 33" fill="none">
                            <path d="M10.9062 12.6891L16.2845 16.7706C16.6177 17.0234 17.0331 17.1395 17.4473 17.0955C17.8615 17.0515 18.244 16.8508 18.5181 16.5335L29.065 4.33887" stroke="#744A9E" stroke-width="2" stroke-linecap="round"/>
                            <path d="M30.7143 16.0301C30.7143 19.1706 29.742 22.2323 27.9338 24.7851C26.1256 27.3379 23.5724 29.2536 20.6328 30.263C17.6931 31.2725 14.5148 31.3251 11.5441 30.4134C8.57345 29.5017 5.95968 27.6715 4.06994 25.1799C2.18019 22.6883 1.10939 19.6604 1.00794 16.5215C0.906487 13.3827 1.77947 10.2905 3.50429 7.67931C5.22911 5.06813 7.71911 3.06911 10.6246 1.96304C13.53 0.856956 16.705 0.699377 19.7035 1.51243" stroke="#744A9E" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="service-description-list--item--data">
                        <div class="service-description-list--item_title">
                            <span>Большая площадь нанесения</span>
                        </div>
                        <div class="service-description-list--item_description">
                            <p>УФ-печать относится к широкоформатным методам и позволяет наносить изображение на площадь А3 и более.</p>
                        </div>
                    </div>
                </div>
                <div class="service-description-list--item">
                    <div class="service-description-list--item_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="33" viewBox="0 0 32 33" fill="none">
                            <path d="M10.9062 12.6891L16.2845 16.7706C16.6177 17.0234 17.0331 17.1395 17.4473 17.0955C17.8615 17.0515 18.244 16.8508 18.5181 16.5335L29.065 4.33887" stroke="#744A9E" stroke-width="2" stroke-linecap="round"/>
                            <path d="M30.7143 16.0301C30.7143 19.1706 29.742 22.2323 27.9338 24.7851C26.1256 27.3379 23.5724 29.2536 20.6328 30.263C17.6931 31.2725 14.5148 31.3251 11.5441 30.4134C8.57345 29.5017 5.95968 27.6715 4.06994 25.1799C2.18019 22.6883 1.10939 19.6604 1.00794 16.5215C0.906487 13.3827 1.77947 10.2905 3.50429 7.67931C5.22911 5.06813 7.71911 3.06911 10.6246 1.96304C13.53 0.856956 16.705 0.699377 19.7035 1.51243" stroke="#744A9E" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="service-description-list--item--data">
                        <div class="service-description-list--item_title">
                            <span>Стойкость изображения</span>
                        </div>
                        <div class="service-description-list--item_description">
                            <p>Специальная краска не высыхает, а полимеризируется, плотно прилегает к поверхности и становится устойчивой к воздействию воды, химически активных веществ, стиранию.</p>
                        </div>
                    </div>
                </div>
                <div class="service-description-list--item">
                    <div class="service-description-list--item_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="33" viewBox="0 0 32 33" fill="none">
                            <path d="M10.9062 12.6891L16.2845 16.7706C16.6177 17.0234 17.0331 17.1395 17.4473 17.0955C17.8615 17.0515 18.244 16.8508 18.5181 16.5335L29.065 4.33887" stroke="#744A9E" stroke-width="2" stroke-linecap="round"/>
                            <path d="M30.7143 16.0301C30.7143 19.1706 29.742 22.2323 27.9338 24.7851C26.1256 27.3379 23.5724 29.2536 20.6328 30.263C17.6931 31.2725 14.5148 31.3251 11.5441 30.4134C8.57345 29.5017 5.95968 27.6715 4.06994 25.1799C2.18019 22.6883 1.10939 19.6604 1.00794 16.5215C0.906487 13.3827 1.77947 10.2905 3.50429 7.67931C5.22911 5.06813 7.71911 3.06911 10.6246 1.96304C13.53 0.856956 16.705 0.699377 19.7035 1.51243" stroke="#744A9E" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="service-description-list--item--data">
                        <div class="service-description-list--item_title">
                            <span>Полноцветное фотореалистичное изображение</span>
                        </div>
                        <div class="service-description-list--item_description">
                            <p>В используемых при УФ-печати однокомпонентных чернилах отсутствуют растворители и испаряющиеся вещества, поэтому краски не смешиваются и рисунок получается чётким, ярким с разрешением до 1080 × 1440 dpi.</p>
                        </div>
                    </div>
                </div>
                <div class="service-description-list--item">
                    <div class="service-description-list--item_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="33" viewBox="0 0 32 33" fill="none">
                            <path d="M10.9062 12.6891L16.2845 16.7706C16.6177 17.0234 17.0331 17.1395 17.4473 17.0955C17.8615 17.0515 18.244 16.8508 18.5181 16.5335L29.065 4.33887" stroke="#744A9E" stroke-width="2" stroke-linecap="round"/>
                            <path d="M30.7143 16.0301C30.7143 19.1706 29.742 22.2323 27.9338 24.7851C26.1256 27.3379 23.5724 29.2536 20.6328 30.263C17.6931 31.2725 14.5148 31.3251 11.5441 30.4134C8.57345 29.5017 5.95968 27.6715 4.06994 25.1799C2.18019 22.6883 1.10939 19.6604 1.00794 16.5215C0.906487 13.3827 1.77947 10.2905 3.50429 7.67931C5.22911 5.06813 7.71911 3.06911 10.6246 1.96304C13.53 0.856956 16.705 0.699377 19.7035 1.51243" stroke="#744A9E" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="service-description-list--item--data">
                        <div class="service-description-list--item_title">
                            <span>Неограниченный выбор материалов под нанесение</span>
                        </div>
                        <div class="service-description-list--item_description">
                            <p>Эффект моментального отверждения исключает впитывание части краски в материал, поэтому изображение получается одинаково ярким и чётким на любых поверхностях: бумаге, пластике, дереве, коже, стекле и металле.</p>
                        </div>
                    </div>
                </div>
                <div class="service-description-list--item">
                    <div class="service-description-list--item_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="33" viewBox="0 0 32 33" fill="none">
                            <path d="M10.9062 12.6891L16.2845 16.7706C16.6177 17.0234 17.0331 17.1395 17.4473 17.0955C17.8615 17.0515 18.244 16.8508 18.5181 16.5335L29.065 4.33887" stroke="#744A9E" stroke-width="2" stroke-linecap="round"/>
                            <path d="M30.7143 16.0301C30.7143 19.1706 29.742 22.2323 27.9338 24.7851C26.1256 27.3379 23.5724 29.2536 20.6328 30.263C17.6931 31.2725 14.5148 31.3251 11.5441 30.4134C8.57345 29.5017 5.95968 27.6715 4.06994 25.1799C2.18019 22.6883 1.10939 19.6604 1.00794 16.5215C0.906487 13.3827 1.77947 10.2905 3.50429 7.67931C5.22911 5.06813 7.71911 3.06911 10.6246 1.96304C13.53 0.856956 16.705 0.699377 19.7035 1.51243" stroke="#744A9E" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="service-description-list--item--data">
                        <div class="service-description-list--item_title">
                            <span>Печать на прозрачных поверхностях</span>
                        </div>
                        <div class="service-description-list--item_description">
                            <p>Нанесение изображений методом УФ-печати возможно в несколько слоёв. На прозрачные поверхности в качестве основы наносится краска белого или иного цвета, благодаря чему не нарушается цветопередача основного изображения.</p>
                        </div>
                    </div>
                </div>
                <div class="service-description-list--item">
                    <div class="service-description-list--item_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="33" viewBox="0 0 32 33" fill="none">
                            <path d="M10.9062 12.6891L16.2845 16.7706C16.6177 17.0234 17.0331 17.1395 17.4473 17.0955C17.8615 17.0515 18.244 16.8508 18.5181 16.5335L29.065 4.33887" stroke="#744A9E" stroke-width="2" stroke-linecap="round"/>
                            <path d="M30.7143 16.0301C30.7143 19.1706 29.742 22.2323 27.9338 24.7851C26.1256 27.3379 23.5724 29.2536 20.6328 30.263C17.6931 31.2725 14.5148 31.3251 11.5441 30.4134C8.57345 29.5017 5.95968 27.6715 4.06994 25.1799C2.18019 22.6883 1.10939 19.6604 1.00794 16.5215C0.906487 13.3827 1.77947 10.2905 3.50429 7.67931C5.22911 5.06813 7.71911 3.06911 10.6246 1.96304C13.53 0.856956 16.705 0.699377 19.7035 1.51243" stroke="#744A9E" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="service-description-list--item--data">
                        <div class="service-description-list--item_title">
                            <span>Большой выбор сувениров:</span>
                        </div>
                        <div class="service-description-list--item_description">
                            <div class="ul-list">
                                <ul>
                                    <li>визитницы;</li>
                                    <li>пластиковые карты;</li>
                                    <li>флешки;</li>
                                    <li>ручки;</li>
                                    <li>магниты;</li>
                                    <li>ежедневники</li>
                                </ul>
                                <ul>
                                    <li>чехлы для телефонов и планшетов;</li>
                                    <li>брелки;</li>
                                    <li>подарочные коробки;</li>
                                    <li>награды;</li>
                                    <li>любые предметы из пластика, металла, стекла или дерева.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <style>
            .how_to_request--item:nth-child(1)::before{
                content: "01";
            }
            .how_to_request--item:nth-child(2)::before{
                content: "02";
            }
            .how_to_request--item:nth-child(3)::before{
                content: "03";
            }
            .how_to_request--item:nth-child(4)::before{
                content: "04";
            }
            .how_to_request--item:nth-child(5)::before{
                content: "05";
            }
            .how_to_request--item:nth-child(6)::before{
                content: "06";
            }
        </style>
        <div class="how_to_request">
            <div class="how_to_request--title">
                <h4 class="title">Как заказать</h4>
            </div>
        </div>
        <div class="how_to_request">
            <div class="how_to_request--item">
                <div class="how_to_request--item_image-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
                        <circle cx="40" cy="40" r="40" fill="#FBB040"/>
                        <path d="M46.75 35.0313V32.3537C46.75 31.5193 46.5883 30.6931 46.2742 29.9222C45.9602 29.1514 45.4998 28.4509 44.9194 27.8609C44.3391 27.271 43.6501 26.8029 42.8918 26.4836C42.1335 26.1643 41.3208 26 40.5 26C39.6792 26 38.8665 26.1643 38.1082 26.4836C37.3499 26.8029 36.6609 27.271 36.0806 27.8609C35.5002 28.4509 35.0398 29.1514 34.7258 29.9222C34.4117 30.6931 34.25 31.5193 34.25 32.3537V38.7074C34.9444 38.7074 35.4074 38.7074 36.0357 38.7074V36.8466H43.1786V35.0313H36.0357V32.3537C36.0357 31.15 36.5061 29.9957 37.3433 29.1446C38.1805 28.2935 39.316 27.8153 40.5 27.8153C41.684 27.8153 42.8195 28.2935 43.6567 29.1446C44.4939 29.9957 44.9643 31.15 44.9643 32.3537V38.662C45.5926 38.662 46.0556 38.662 46.75 38.662V36.8466H51.2143V53.1847H29.7857V36.8466H32.4643V35.0313H28V53.2664C28 53.7261 28.1797 54.1671 28.4995 54.4922C28.8193 54.8173 29.2531 55 29.7054 55H51.2946C51.7469 55 52.1807 54.8173 52.5005 54.4922C52.8203 54.1671 53 53.7261 53 53.2664V35.0313H46.75Z" fill="white"/>
                    </svg>
                </div>
                <div class="how_to_request--item_title-wrapper">
                    <span>Выбор товара</span>
                </div>
                <div class="how_to_request--item_description-wrapper">
                    <p>Выберите товар, укажите нужный тираж и&nbsp;сделайте заказ через&nbsp;корзину</p>
                </div>
            </div>
            <div class="how_to_request--item">
                <div class="how_to_request--item_image-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
                        <circle cx="40" cy="40" r="40" fill="#EF4A85"/>
                        <g clip-path="url(#clip0_1809_827)">
                            <mask id="mask0_1809_827" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="20" y="23" width="41" height="35">
                                <path d="M60.4882 23H20.1875V58H60.4882V23Z" fill="white"/>
                            </mask>
                            <g mask="url(#mask0_1809_827)">
                                <path d="M39.532 51.7019C39.2974 51.7019 39.0635 51.6902 38.8336 51.6652C36.8388 51.4524 35.0241 50.2816 33.9913 49.2573C32.3215 47.6016 30.8545 44.9645 30.0656 42.202C29.2354 39.4433 29.0648 36.4796 29.6081 34.2735C29.9464 32.8977 30.8896 31.0316 32.5219 29.9239C33.4199 29.3135 34.5571 28.9137 35.5643 28.8544C36.548 28.7967 37.3112 29.0259 37.8303 29.5373C38.6971 30.3909 38.6394 31.7332 38.588 32.918L38.5864 32.9484C38.5576 35.0617 38.1897 36.2332 36.2698 36.7695C36.2346 36.7789 36.1996 36.7867 36.1638 36.7914C35.9649 37.0205 35.6306 38.0612 35.8846 39.5111C35.947 39.8735 36.0367 40.2532 36.1513 40.6429C36.2658 41.0312 36.3968 41.4022 36.5403 41.7452C37.1428 43.1927 38.0454 43.9395 38.3471 44.0268C38.3846 44.0128 38.4228 44.0019 38.4609 43.9933C40.3863 43.5872 41.2898 44.3822 42.4723 46.204L42.5541 46.3225C43.2713 47.3577 44.0118 48.4271 43.6984 49.5582C43.5059 50.2512 42.9658 50.789 42.0451 51.2045C41.3288 51.5272 40.4261 51.7034 39.5328 51.7034L39.532 51.7019ZM35.8659 30.4041C35.7989 30.4041 35.7288 30.4064 35.6547 30.4103C34.9383 30.4524 34.0528 30.7681 33.3964 31.2132C32.1282 32.0745 31.3775 33.6 31.1203 34.6454C30.6401 36.5942 30.8038 39.2523 31.5584 41.7569L31.5615 41.7678C32.2802 44.2872 33.5984 46.6725 35.0872 48.1489C35.894 48.9486 37.4024 49.9433 38.998 50.1132C39.8158 50.2013 40.7597 50.0703 41.4028 49.7803C41.8549 49.5761 42.1363 49.3485 42.1948 49.1388C42.3133 48.7124 41.7357 47.8784 41.2711 47.208L41.1814 47.0786C41.1768 47.0724 41.1729 47.0661 41.1689 47.0599C40.0909 45.3972 39.7105 45.3278 38.8608 45.5C38.6176 45.5967 38.2638 45.6497 37.8093 45.4868C36.8248 45.1337 35.7358 43.8709 35.0997 42.343C34.9352 41.9494 34.7856 41.5245 34.6546 41.081C34.5236 40.6336 34.4199 40.1955 34.3474 39.7769C34.0653 38.1695 34.3178 36.563 34.9765 35.7812C35.2393 35.4694 35.5729 35.2815 35.9268 35.2449C36.7398 35.011 37.0025 34.7943 37.0266 32.9157C37.0266 32.9079 37.0266 32.9 37.0266 32.8923L37.0282 32.8494C37.0656 31.9896 37.1077 31.0144 36.7343 30.6473C36.5691 30.4852 36.2775 30.4033 35.8644 30.4033L35.8659 30.4041Z" fill="white"/>
                                <path d="M54.9052 30.4028H47.0119C46.5816 30.4028 46.2324 30.0536 46.2324 29.6233C46.2324 29.193 46.5816 28.8438 47.0119 28.8438H54.9052C55.3355 28.8438 55.6847 29.193 55.6847 29.6233C55.6847 30.0536 55.3355 30.4028 54.9052 30.4028Z" fill="white"/>
                                <path d="M54.9052 33.4887H47.0119C46.5816 33.4887 46.2324 33.1394 46.2324 32.7092C46.2324 32.2789 46.5816 31.9297 47.0119 31.9297H54.9052C55.3355 31.9297 55.6847 32.2789 55.6847 32.7092C55.6847 33.1394 55.3355 33.4887 54.9052 33.4887Z" fill="white"/>
                                <path d="M44.8674 41.568C44.6975 41.568 44.5626 41.5212 44.4862 41.4861C44.1432 41.3295 43.9296 40.9779 43.9296 40.5702V40.4322C43.9296 40.4011 43.9312 40.3706 43.9351 40.3395L44.2454 37.7429C42.0323 36.1535 40.7734 33.8571 40.7734 31.3759C40.7734 26.7698 45.1956 23.0234 50.6311 23.0234C52.8176 23.0234 54.888 23.6166 56.6193 24.7384C56.9802 24.9722 57.0839 25.4547 56.85 25.8164C56.6162 26.1773 56.1337 26.281 55.7719 26.0472C54.294 25.0891 52.5159 24.5832 50.6311 24.5832C46.0554 24.5832 42.3324 27.6311 42.3324 31.3775C42.3324 33.4681 43.4869 35.413 45.5004 36.7132C45.7701 36.8878 45.9034 37.2082 45.8426 37.5185L45.6383 39.2271L47.2878 37.9487C47.4827 37.7983 47.7368 37.7476 47.9745 37.8138C48.8273 38.0508 49.7206 38.1716 50.6311 38.1716C55.2068 38.1716 58.9298 35.1238 58.9298 31.3775C58.9298 30.0219 58.441 28.7131 57.5165 27.5921C57.2429 27.2601 57.2897 26.769 57.6218 26.4946C57.9538 26.221 58.4449 26.2678 58.7193 26.5998C59.8769 28.0037 60.4888 29.6555 60.4888 31.3775C60.4888 35.9836 56.0666 39.7306 50.6311 39.7306C49.7152 39.7306 48.8118 39.6246 47.941 39.4158L45.8558 41.0317C45.7755 41.1034 45.7327 41.1424 45.6976 41.1735C45.6618 41.2063 45.6329 41.2328 45.5861 41.2741C45.3211 41.5041 45.0693 41.5695 44.8674 41.5695V41.568Z" fill="white"/>
                                <path d="M37.6875 58C33.0127 58 28.6179 56.1798 25.3128 52.8739C22.0077 49.5688 20.1875 45.1739 20.1875 40.5C20.1875 36.8363 21.3069 33.3285 23.424 30.357C23.6743 30.0062 24.1607 29.9244 24.5114 30.1746C24.8622 30.4241 24.9441 30.9112 24.6939 31.262C22.7653 33.9685 21.7465 37.1629 21.7465 40.5007C21.7465 49.2906 28.8977 56.4417 37.6875 56.4417C46.4773 56.4417 53.6285 49.2906 53.6285 40.5007C53.6285 39.8062 53.5833 39.1054 53.4929 38.4179C53.4375 37.9907 53.7376 37.5994 54.1648 37.5441C54.5919 37.488 54.9833 37.7889 55.0386 38.2161C55.1368 38.9706 55.1875 39.74 55.1875 40.5007C55.1875 50.1503 47.3371 58.0008 37.6875 58.0008V58ZM25.7727 29.5487C25.5755 29.5487 25.3775 29.4738 25.2263 29.3249C24.9191 29.0233 24.9152 28.5291 25.2177 28.2227C28.5314 24.8545 32.9598 23 37.6875 23C40.3628 23 42.9305 23.5878 45.3197 24.7477C45.7071 24.9355 45.8684 25.4017 45.6806 25.7891C45.4928 26.1765 45.0266 26.3379 44.6392 26.15C42.4643 25.0945 40.1258 24.559 37.6883 24.559C33.3815 24.559 29.3475 26.2482 26.3293 29.3164C26.1765 29.4715 25.9754 29.5494 25.7735 29.5494L25.7727 29.5487Z" fill="white"/>
                            </g>
                        </g>
                        <defs>
                            <clipPath id="clip0_1809_827">
                                <rect width="40.625" height="35" fill="white" transform="translate(20.1875 23)"/>
                            </clipPath>
                        </defs>
                    </svg>
                </div>
                <div class="how_to_request--item_title-wrapper">
                    <span>Звонок менеджера</span>
                </div>
                <div class="how_to_request--item_description-wrapper">
                    <p>Менеджер свяжется с&nbsp;вами для&nbsp;уточнения необходимых деталей заказа</p>
                </div>
            </div>
            <div class="how_to_request--item">
                <div class="how_to_request--item_image-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
                        <circle cx="40" cy="40" r="40" fill="#744A9E"/>
                        <path d="M25 30.9429C25 29.3667 25.6261 27.8551 26.7406 26.7406C27.8551 25.6261 29.3667 25 30.9429 25C32.519 25 34.0306 25.6261 35.1451 26.7406C36.2596 27.8551 36.8857 29.3667 36.8857 30.9429V48.7714C36.8857 50.3476 36.2596 51.8592 35.1451 52.9737C34.0306 54.0882 32.519 54.7143 30.9429 54.7143C29.3667 54.7143 27.8551 54.0882 26.7406 52.9737C25.6261 51.8592 25 50.3476 25 48.7714V30.9429Z" stroke="white" stroke-width="2"/>
                        <path d="M36.8866 34.2757L41.8087 29.352C42.9233 28.2375 44.4349 27.6113 46.0111 27.6113C47.5873 27.6113 49.0989 28.2375 50.2134 29.352C51.3279 30.4665 51.9541 31.9782 51.9541 33.5543C51.9541 35.1305 51.3279 36.6422 50.2134 37.7567L35.8555 52.1146" stroke="white" stroke-width="2"/>
                        <path d="M30.9388 54.7138H48.7674C50.3436 54.7138 51.8551 54.0877 52.9696 52.9732C54.0841 51.8587 54.7103 50.3471 54.7103 48.771C54.7103 47.1948 54.0841 45.6832 52.9696 44.5687C51.8551 43.4542 50.3436 42.8281 48.7674 42.8281H45.0531M32.4246 48.771C32.4246 49.165 32.268 49.5429 31.9894 49.8215C31.7108 50.1002 31.3329 50.2567 30.9388 50.2567C30.5448 50.2567 30.1669 50.1002 29.8883 49.8215C29.6097 49.5429 29.4531 49.165 29.4531 48.771C29.4531 48.3769 29.6097 47.999 29.8883 47.7204C30.1669 47.4418 30.5448 47.2853 30.9388 47.2853C31.3329 47.2853 31.7108 47.4418 31.9894 47.7204C32.268 47.999 32.4246 48.3769 32.4246 48.771Z" stroke="white" stroke-width="2"/>
                    </svg>
                </div>
                <div class="how_to_request--item_title-wrapper">
                    <span>Согласование итогового макета</span>
                </div>
                <div class="how_to_request--item_description-wrapper">
                    <p>Согласование итогового заказа с&nbsp;нанесением, подписание документов</p>
                </div>
            </div>
            <div class="how_to_request--item additional">
                <div class="how_to_request--item_image-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
                        <circle cx="40" cy="40" r="40" fill="#4BD783"/>
                        <path d="M44.6417 41.5357C49.7898 41.5357 53.9632 39.6809 53.9632 37.3929C53.9632 35.1048 49.7898 33.25 44.6417 33.25C39.4937 33.25 35.3203 35.1048 35.3203 37.3929C35.3203 39.6809 39.4937 41.5357 44.6417 41.5357Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M35.3203 37.3926V49.8211C35.3203 52.0997 39.4632 53.964 44.6417 53.964C49.8203 53.964 53.9632 52.0997 53.9632 49.8211V37.3926" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M53.9632 43.6074C53.9632 45.886 49.8203 47.7503 44.6417 47.7503C39.4632 47.7503 35.3203 45.886 35.3203 43.6074" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M44.4352 29.1072C42.0147 27.6134 39.1972 26.891 36.3566 27.0358C31.1987 27.0358 27.0352 28.9 27.0352 31.1786C27.0352 32.4008 28.2366 33.4986 30.1423 34.2858" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M30.1423 46.7134C28.2366 45.9263 27.0352 44.8284 27.0352 43.6063V31.1777" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M30.1423 40.4997C28.2366 39.7126 27.0352 38.6147 27.0352 37.3926" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="how_to_request--item_title-wrapper">
                    <span>Оплата</span>
                </div>
                <div class="how_to_request--item_description-wrapper">
                    <p>После&nbsp;согласования заказа менеджер выставляет счёт на&nbsp;оплату</p>

                    <a href="/oplata/" class="how_to_request--item_description--link">Подробнее о способах оплаты</a>
                </div>
            </div>
            <div class="how_to_request--item additional">
                <div class="how_to_request--item_image-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
                        <circle cx="40" cy="40" r="40" fill="#57B0EA"/>
                        <path d="M50.9967 48.0561C50.9967 49.0412 50.6053 49.986 49.9088 50.6825C49.2122 51.3791 48.2675 51.7704 47.2824 51.7704C46.2973 51.7704 45.3525 51.3791 44.656 50.6825C43.9594 49.986 43.5681 49.0412 43.5681 48.0561C43.5681 47.071 43.9594 46.1263 44.656 45.4297C45.3525 44.7331 46.2973 44.3418 47.2824 44.3418C48.2675 44.3418 49.2122 44.7331 49.9088 45.4297C50.6053 46.1263 50.9967 47.071 50.9967 48.0561ZM36.1395 48.0561C36.1395 49.0412 35.7482 49.986 35.0516 50.6825C34.3551 51.3791 33.4103 51.7704 32.4252 51.7704C31.4401 51.7704 30.4954 51.3791 29.7988 50.6825C29.1023 49.986 28.7109 49.0412 28.7109 48.0561C28.7109 47.071 29.1023 46.1263 29.7988 45.4297C30.4954 44.7331 31.4401 44.3418 32.4252 44.3418C33.4103 44.3418 34.3551 44.7331 35.0516 45.4297C35.7482 46.1263 36.1395 47.071 36.1395 48.0561Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M43.5714 48.0573H36.1429M25 28H39.8571C41.9579 28 43.0083 28 43.6606 28.6537C44.3143 29.3045 44.3143 30.3549 44.3143 32.4572V45.0858M45.0571 31.7143H47.7329C48.9661 31.7143 49.5826 31.7143 50.0937 32.004C50.6048 32.2923 50.9213 32.8212 51.5557 33.879L54.0799 38.0836C54.3949 38.6096 54.5523 38.874 54.6341 39.1652C54.7143 39.4579 54.7143 39.764 54.7143 40.3776V44.343C54.7143 45.7321 54.7143 46.426 54.4157 46.943C54.2201 47.2817 53.9388 47.5631 53.6 47.7586C53.083 48.0573 52.3891 48.0573 51 48.0573M25 41.3715V44.343C25 45.7321 25 46.426 25.2986 46.943C25.4942 47.2817 25.7755 47.5631 26.1143 47.7586C26.6313 48.0573 27.3251 48.0573 28.7143 48.0573M25 32.4572H33.9143M25 36.9143H30.9429" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="how_to_request--item_title-wrapper">
                    <span>Получение заказа</span>
                </div>
                <div class="how_to_request--item_description-wrapper">
                    <p>Изготавливаем ваш&nbsp;заказ и&nbsp;доставляем удобным для&nbsp;вас&nbsp;способом</p>

                    <a href="/dostavka/" class="how_to_request--item_description--link">Подробнее о способах доставки</a>
                </div>
            </div>
            <div class="how_to_request--item">
                <div class="how_to_request--item_image-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80" fill="none">
                        <circle cx="40" cy="40" r="40" fill="#FF5B36"/>
                        <mask id="mask0_1809_854" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="19" y="23" width="43" height="35">
                            <path d="M61.1046 23H19V58H61.1046V23Z" fill="white"/>
                        </mask>
                        <g mask="url(#mask0_1809_854)">
                            <path d="M60.8812 26.8565L57.255 23.2294C57.1298 23.1061 56.9657 23.0299 56.7935 23.0118L56.8052 23L56.6919 23.0027C56.4942 23.0073 56.3074 23.0861 56.166 23.2249L53.2543 26.1366C53.1573 26.2336 53.0892 26.3524 53.0548 26.482L46.5015 33.0354C46.1995 33.3346 46.1977 33.8234 46.5015 34.1299C46.6511 34.2813 46.8488 34.3566 47.0473 34.3566C47.2459 34.3566 47.4436 34.2813 47.596 34.1299L53.8745 27.8513L56.3945 30.3712L43.7322 43.0336L41.1895 40.4909L43.9045 37.776C44.2018 37.4759 44.2018 36.988 43.9045 36.687C43.7585 36.541 43.5644 36.4603 43.3577 36.4603C43.1509 36.4603 42.9568 36.541 42.8108 36.687L42.4454 37.047L39.5337 39.9586C39.3995 40.0929 39.3215 40.2669 39.3098 40.4538L37.2623 45.928L34.5319 48.6583L32.1018 43.7971C32.0093 43.6121 31.8506 43.4743 31.6547 43.4089C31.4588 43.3437 31.2494 43.3582 31.0644 43.4507C30.8812 43.5422 30.7434 43.6991 30.6772 43.8941L27.6622 52.8839C26.8623 51.2844 26.1424 49.6249 25.5222 47.9483C27.1507 44.4091 28.3595 41.1873 29.1157 38.3709C29.9908 35.1309 30.2202 32.6617 29.8167 30.8219C29.5782 29.7329 29.113 28.8859 28.4338 28.3029C27.7211 27.6881 26.7318 27.3634 25.5747 27.3634H25.3245L25.3617 27.377C24.3995 27.4242 23.5463 27.7597 22.8825 28.3555C22.2233 28.9449 21.7318 29.7873 21.4217 30.86C20.883 32.708 20.8468 35.1491 21.3101 38.3237C21.7327 41.1774 22.5796 44.4001 23.8255 47.9021C22.4064 50.9434 20.8196 53.9494 19.1103 56.8366C18.8926 57.2029 19.0133 57.6781 19.3796 57.8966C19.5002 57.9674 19.6335 58 19.765 58C20.0279 58 20.2855 57.8667 20.4296 57.6273C21.9739 55.0593 23.3985 52.2066 24.6172 49.722L24.8139 50.2216C25.5339 52.0488 26.2784 53.9376 27.1063 55.4284C27.2078 55.6088 27.3729 55.7385 27.5715 55.7938C27.77 55.85 27.9795 55.8246 28.1591 55.7231C28.3259 55.6297 28.451 55.4782 28.5127 55.2969L31.555 46.1701L33.6297 50.3032C33.821 50.685 34.2871 50.8391 34.668 50.6487C34.7414 50.6116 34.8085 50.5635 34.8675 50.5046L38.4238 46.9481L44.0405 44.8417C44.2218 44.7737 44.3678 44.6422 44.4558 44.4708L57.5334 31.3932L57.9043 31.7641L52.634 37.0343C52.3321 37.3353 52.3321 37.8259 52.634 38.1287C52.7837 38.2775 52.9813 38.3518 53.1781 38.3518C53.3749 38.3518 53.5725 38.2775 53.7222 38.1287L59.5465 32.3045C59.8438 32.0044 59.8438 31.5165 59.5465 31.2164L58.5789 30.2488L60.8822 27.9456C61.1795 27.6455 61.1795 27.1576 60.8822 26.8565H60.8812ZM59.2572 27.4106L57.4427 29.2251L54.9001 26.6824L56.7155 24.8671L59.2581 27.4097L59.2572 27.4106ZM40.4188 41.9391L42.3176 43.8379L39.2762 44.9813L40.4197 41.9391H40.4188ZM23.9081 29.5071C24.3596 29.1045 24.8956 28.9168 25.5947 28.9168H25.64V28.9086C26.418 28.9158 26.9884 29.0981 27.4327 29.4826C27.858 29.8498 28.1463 30.3976 28.3141 31.1583C28.6487 32.6926 28.4084 35.1137 27.6386 37.9773C26.9802 40.4229 25.8141 43.2403 24.7487 45.6904C23.7539 42.5947 23.1464 40.1781 22.8399 38.0925C22.4237 35.2669 22.4454 32.854 22.9015 31.2989C23.1355 30.4964 23.4746 29.8934 23.9072 29.5071H23.9081Z" fill="white"/>
                            <path d="M45.1515 36.17H45.1551C45.5785 36.1682 45.924 35.8227 45.9258 35.3992C45.9277 34.973 45.5822 34.6249 45.1551 34.623H45.1523C44.7271 34.623 44.3807 34.9676 44.3789 35.3992C44.3789 35.606 44.4605 35.8 44.6074 35.9452C44.7534 36.0902 44.9465 36.1691 45.1515 36.1691V36.17Z" fill="white"/>
                        </g>
                    </svg>
                </div>
                <div class="how_to_request--item_title-wrapper">
                    <span>Подписание акта приёма заказа</span>
                </div>
                <div class="how_to_request--item_description-wrapper">
                    <p>Проверяете ваш&nbsp;заказ и&nbsp;подписываете акт&nbsp;приёма заказа</p>
                </div>
            </div>
        </div>
    </div>

    <?$APPLICATION->IncludeComponent(
        "bitrix:form.result.new",
        "onlineservice-feedback-form-type-2",
        Array(
            "IMAGE" => 2,
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