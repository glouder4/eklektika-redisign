<?php
$APPLICATION->SetTitle("ЙО!каталог расширяет каталог");
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
            <h1 class="page__title-wrapper-title" style="background: unset; -webkit-text-fill-color: #7B4FA3;">
                <?$APPLICATION->ShowTitle(false);?>
            </h1>
        </div>
    </div>
    <section class="content-section">
        <div class="container">
            <div class="yomerch-partners-invite">
                <div class="gradient-bg"></div>
                
                <div class="content-wrapper">
                    <h1>ЙО!каталог расширяет каталог</h1>
                    
                    <p class="lead">
                    ЙО!каталог расширяет каталог и рассматривает производителей, которые делают стильные и качественные вещи.
                    </p>

                    <p class="main-text">
                    Наша работа — корпоративный мерч, промопродукция и бизнес-подарки. Это все, что помогает брендам выглядеть круто и оставаться на виду.
                    </p>

                    <div class="cta-section">
                        <p>Если вам близок наш подход и вы хотите попасть в каталог, напишите нам на почту info@yomerch.ru. Мы изучим вашу продукцию и свяжемся для дальнейшего обсуждения.</p>
                        <a href="mailto:info@yomerch.ru" class="email-button">
                            info@yomerch.ru
                        </a>
                        </div>
                    </div>
                   
                    <?/*
                    <div class="examples">
                        <div class="example-grid">
                        <img src="https://cdn1.apparelnbags.com/wp-content/what-is-corporate-merchandise-blog-social-media.jpg" alt="Corporate merchandise collection" loading="lazy">
                        <img src="https://dpapparelshop.com/wp-content/uploads/2024/06/Group-2330.png" alt="Stylish custom branded apparel" loading="lazy">
                        <img src="https://www.vistaprint.com/hub/wp-content/uploads/sites/14/2025/05/PromotionalProductTrends2025.png" alt="Premium promotional products" loading="lazy">
                        <img src="https://images.squarespace-cdn.com/content/v1/5ed6e10fb011a123217ba702/39f7a818-95ff-433d-b061-5089818c00e4/PXL_20221201_074538974.jpg" alt="High-quality merch production" loading="lazy">
                        </div>
                    </div>
                    */?>
                </div>

                <style>
                .yomerch-partners-invite {
                position: relative;
                margin: 40px auto;
                padding: 60px 40px;
                color: #000;
                border-radius: 28px;
                overflow: hidden;
                font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                box-shadow: 0 20px 70px rgba(80, 0, 150, 0.35);
                background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
                }

                .gradient-bg {
                position: absolute;
                inset: 0;
                background: radial-gradient(circle at 20% 30%, rgba(147,51,234,0.28) 0%, transparent 50%),
                            radial-gradient(circle at 80% 70%, rgba(236,72,153,0.18) 0%, transparent 60%);
                pointer-events: none;
                }

                .content-wrapper {
                position: relative;
                z-index: 2;
                text-align: left;
                }

                h1 {
                font-size: 3.4rem;
                font-weight: 900;
                margin: 0 0 40px;
                letter-spacing: -1px;
                background: linear-gradient(90deg, #c084fc, #ec4899, #a78bfa);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                }

                .lead {
                font-size: 1.6rem;
                font-weight: 600;
                margin: 0 0 32px;
                line-height: 1.4;
                }

                .main-text {
                font-size: 1.32rem;
                line-height: 1.65;
                max-width: 780px;
                opacity: 0.95;
                }

                .cta-section {
                margin: 50px 0;
                }

                .cta-section > p {
                font-size: 1.38rem;
                margin: 0 0 24px;
                }

                .email-button {
                display: inline-block;
                font-size: 2rem;
                font-weight: 800;
                color: white;
                background: #7B4FA3; 
                padding: 18px 56px;
                border-radius: 999px;
                text-decoration: none;
                transition: all 0.3s ease;
                box-shadow: 0 10px 40px rgba(147,51,234,0.45);
                }

                .email-button:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 60px rgba(147,51,234,0.6);
                }

                .after {
                font-size: 1.32rem;
                margin: 0 !important;
                color: #c084fc;
                font-weight: 500;
                }

                .examples {
                margin-top: 60px;
                opacity: 0.92;
                }

                .example-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 20px;
                border-radius: 16px;
                overflow: hidden;
                }

                .example-grid img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                display: block;
                border-radius: 12px;
                transition: transform 0.35s ease;
                }

                .example-grid img:hover {
                transform: scale(1.06);
                }

                @media (max-width: 780px) {
                h1 { font-size: 2.6rem; }
                .lead { font-size: 1.4rem; }
                .email-button { font-size: 1.7rem; padding: 16px 40px; }
                }
                </style>
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