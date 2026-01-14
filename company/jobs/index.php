<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Вакансии компании  Yo!merch");
?>
<div class="intec-page intec-page-404 intec-content">
    <div class="intec-content-wrapper">
        <div class="container">
           <div class="team-invite-card">
                <div class="intro">
                    <h1>Пока только старт,<br>но курс уже задан</h1>
                    <p class="lead">
                    Скорость, амбиции и целое море планов — вот наше текущее состояние.
                    </p>
                </div>

                <div class="vibe">
                    <p>
                    Наша команда — это место, где «коллежки» не просто слово, а настоящий стиль общения.<br>
                    Здесь можно хаханьки-хаханьки над мемами, а уже через минуту выручать друг друга по проекту<br>
                    и строить вместе что-то действительно крутое.
                    </p>
                </div>

                <div class="call-to-action">
                    <p class="big-text">Кажется, для полной картины не хватает именно тебя.</p>
                    <p class="join-us">Давай знакомиться! 🔥</p>
                </div>

                <div class="vacancies">
                    <div class="vacancy">
                    <h2>Аккаунт-менеджер</h2>
                    <p class="subtitle">
                        Ищешь классного активного веденца с опытом в B2B? Или сам такой? — велком!
                    </p>

                    <div class="duties">
                        <h3>Что делать:</h3>
                        <ul>
                        <li>Консультация и помощь в выборе товаров</li>
                        <li>Полное ведение заказов: от расчёта до контроля отгрузки</li>
                        <li>Информирование об акциях и новинках</li>
                        <li>Работа с CRM и документооборотом</li>
                        </ul>
                    </div>

                    <div class="offers">
                        <h3>Что предлагаем:</h3>
                        <ul>
                        <li>Официальное оформление + белая з/п по ТК РФ</li>
                        <li>Стильный новый офис на Бутырской</li>
                        <li>Обучение, наставничество и реальный рост</li>
                        </ul>
                    </div>

                    <p class="send-cv">
                        Резюме с пометкой <strong>"Кандидат на вакансию аккаунт-менеджера"</strong><br>
                        присылай на почту HR — очень внимательно всё смотрим и обязательно свяжемся при мэтче ✌️
                    </p>
                    </div>

                    <div class="vacancy">
                    <h2>Менеджер по развитию (B2B)</h2>
                    <p class="subtitle">
                        Делаешь презентации, от которых клиент говорит «хочу!» и умеешь строить долгосрочные отношения?
                    </p>

                    <div class="duties">
                        <h3>Что делать:</h3>
                        <ul>
                        <li>Увеличение прибыли от существующей базы</li>
                        <li>Развитие и углубление партнёрских отношений</li>
                        <li>Аналитика продаж и поиск точек роста</li>
                        <li>Эмпатия, коммуникабельность, вовлечённость (must have)</li>
                        </ul>
                    </div>

                    <div class="offers">
                        <h3>Что предлагаем:</h3>
                        <ul>
                        <li>Официальное оформление + белая з/п по ТК РФ</li>
                        <li>Стильный новый офис на Бутырской</li>
                        <li>Обучение, наставничество и реальный рост</li>
                        </ul>
                    </div>

                    <p class="send-cv">
                        Резюме с пометкой <strong>"Кандидат на вакансию менеджер по развитию"</strong><br>
                        присылай на почту HR — очень внимательно всё смотрим и обязательно свяжемся при мэтче ✌️
                    </p>
                    </div>
                </div>
            </div>

            <style>
            .team-invite-card {
                padding: 20px;
                margin: 0 auto;
                font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                line-height: 1.45;
                color: #111;
                background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
                border-radius: 24px;
                box-shadow: 0 20px 60px -15px rgba(0,0,80,0.12);
            }

            .intro h1 {
                font-size: 3.1rem;
                font-weight: 800;
                margin: 0 0 20px;
                letter-spacing: -1px;
                background: linear-gradient(90deg, #6366f1, #8b5cf6);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }

            .lead {
                font-size: 1.38rem;
                color: #444;
                margin: 0 0 50px;
            }

            .vibe {
                font-size: 1.22rem;
                color: #222;
                background: rgba(255,255,255,0.75);
                padding: 28px 32px;
                border-radius: 16px;
                margin: 0 0 50px;
                border-left: 5px solid #6366f1;
            }

            .big-text {
                font-size: 2.1rem;
                font-weight: 700;
                margin: 0;
                color: #1e1b4b;
            }

            .join-us {
                font-size: 1.8rem;
                font-weight: 800;
                color: #7c3aed;
                margin: 8px 0 60px;
            }

            .vacancies {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 40px;
            }

            .vacancy {
                background: white;
                padding: 40px;
                border-radius: 16px;
                box-shadow: 0 10px 30px -10px rgba(0,0,0,0.08);
                border: 1px solid #f0f0ff;
                transition: all 0.25s ease;
            }

            .vacancy:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 50px -15px rgba(99,102,241,0.25);
            }

            .vacancy h2 {
                font-size: 2.1rem;
                margin: 0 0 12px;
                color: #4f46e5;
            }

            .subtitle {
                font-size: 1.18rem;
                color: #555;
                margin: 0 0 28px;
            }

            .duties h3,
            .offers h3 {
                font-size: 1.3rem;
                color: #4338ca;
                margin: 0 0 16px;
            }

            ul {
                padding-left: 22px;
                margin: 0 0 32px;
            }

            li {
                margin-bottom: 12px;
                font-size: 1.05rem;
                color: #333;
            }

            .send-cv {
                font-size: 1.1rem;
                color: #444;
                margin-top: 40px;
                padding-top: 28px;
                border-top: 1px dashed #d1d5ff;
            }

            @media (max-width: 900px) {
                .vacancies {
                    grid-template-columns: 1fr;
                }
            }
            </style>
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
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php") ?>