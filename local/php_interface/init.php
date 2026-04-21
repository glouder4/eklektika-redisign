<?php
if (headers_sent() === false) {
    header_remove('X-Frame-Options');
}

// В самом начале, после подключения пролога
if (function_exists('header_remove')) {
    // Разрешаем показывать Server Timing
    header_remove('Server-Timing');
}

// Добавляем измерение времени для разных этапов
$GLOBALS['BX_TIMINGS'] = [];

// Перехватываем время начала запроса
$GLOBALS['BX_TIMINGS']['start'] = microtime(true);

// Регистрируем завершение работы ядра Bitrix
AddEventHandler('main', 'OnEpilog', 'OnEpilogHandler');
function OnEpilogHandler()
{
    $timings = $GLOBALS['BX_TIMINGS'];
    $total = microtime(true) - $timings['start'];

    // Безопасное получение времени SQL запросов
    global $DB;
    $sql_time = 0;

    if (is_object($DB)) {
        // Пробуем разные варианты названия метода
        if (method_exists($DB, 'getQueryTime')) {
            $sql_time = $DB->getQueryTime();
        } elseif (method_exists($DB, 'GetQueryTime')) {
            $sql_time = $DB->GetQueryTime();
        } elseif (method_exists($DB, 'getSqlQueryTime')) {
            $sql_time = $DB->getSqlQueryTime();
        } elseif (property_exists($DB, 'sql_time')) {
            $sql_time = $DB->sql_time;
        } elseif (defined('BX_SQL_TIME')) {
            $sql_time = BX_SQL_TIME;
        }
    }

    // Формируем заголовок Server-Timing
    $header = sprintf(
        'total;dur=%f, sql;dur=%f, php;dur=%f',
        $total * 1000,
        $sql_time * 1000,
        ($total - $sql_time) * 1000
    );

    header("Server-Timing: $header");
}

define('BX_SALT', 'site_yomerch');
ini_set('session.cookie_domain', 'yomerch.ru');
\Bitrix\Main\Config\Option::set('main', 'cookie_domain', 'yomerch.ru');
\Bitrix\Main\Config\Option::set('main', 'use_domain_without_dot_for_cookie', 'Y');

session_set_cookie_params([
    'path' => '/',
    'domain' => 'yomerch.ru',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);

//header("X-Frame-Options: bitrix.yomerch.ru");
//header('Content-Security-Policy: frame-ancestors https://bitrix.yomerch.ru', true);

use intec\eklectika\advertising_agent\Company;
CModule::IncludeModule("intec.eklectika");

$b24ConfigPath = __DIR__ . '/b24_integration_config.php';
$b24IntegrationConfig = [
    'base_url' => '',
    'rest_webhook_main' => '',
    'rest_webhook_kit' => '',
];

if (file_exists($b24ConfigPath)) {
    $loadedB24Config = require $b24ConfigPath;

    if (is_array($loadedB24Config)) {
        $b24IntegrationConfig = array_merge($b24IntegrationConfig, $loadedB24Config);
    }
}

define('URL_B24', (string) $b24IntegrationConfig['base_url']);
define('B24_REST_WEBHOOK_MAIN', (string) $b24IntegrationConfig['rest_webhook_main']);
define('B24_REST_WEBHOOK_KIT', (string) $b24IntegrationConfig['rest_webhook_kit']);
define("EXLUDED_ORDER_KEYS",["KO","UD","KP",'SD']);
define("EXLUDED_RESERVE_KEYS",["RO", "RC", "R"]);
define("EXLUDED_SAMPLE_KEYS",["OB","SS", "SO", "SC","OG"]);

require_once __DIR__.'/../classes/requires.php'; // Подключение кастомных обработчиков

if (class_exists(\OnlineService\Site\CatalogPriceFloor::class)) {
    \OnlineService\Site\CatalogPriceFloor::bootstrap();
}

function pre($o) {

    $bt = debug_backtrace();
    $bt = $bt[0];
    $dRoot = $_SERVER["DOCUMENT_ROOT"];
    $dRoot = str_replace("/", "\\", $dRoot);
    $bt["file"] = str_replace($dRoot, "", $bt["file"]);
    $dRoot = str_replace("\\", "/", $dRoot);
    $bt["file"] = str_replace($dRoot, "", $bt["file"]);
    ?>
    <div style='font-size:9pt; color:#000; background:#fff; border:1px dashed #000;text-align: left!important;'>
        <div style='padding:3px 5px; background:#99CCFF; font-weight:bold;'>File: <?= $bt["file"] ?> [<?= $bt["line"] ?>]</div>
        <pre style='padding:5px;'><? print_r($o) ?></pre>
    </div>
    <?
}
/*
use Bitrix\Main\EventManager;

// Получаем все обработчики события
$eventManager = EventManager::getInstance();
$handlers = $eventManager->findEventHandlers("main", "OnAfterUserUpdate");

echo "<pre>";
foreach ($handlers as $handler) {
    echo "Module: " . $handler['MODULE_ID'] . "\n";
    echo "Class: " . $handler['TO_CLASS'] . "\n";
    echo "Method: " . $handler['TO_METHOD'] . "\n";
    echo "File: " . $handler['TO_PATH'] . "\n";
    echo "Sort: " . $handler['SORT'] . "\n";
    echo "------------------------\n";
}
echo "</pre>";
die(); */
/* Закомментированный черновик updateSections — при включении использовать
 * \OnlineService\Catalog\Import1c\PostImportHandler::IBLOCK_ID_1C и ::actionSection().
 *
 * AddEventHandler("catalog", "OnSuccessCatalogImport1C", "updateSections");
 * function updateSections()
 * {
 *     ...
 *     'IBLOCK_ID' => \OnlineService\Catalog\Import1c\PostImportHandler::IBLOCK_ID_1C
 *     ...
 *     $dopSectionId[] = \OnlineService\Catalog\Import1c\PostImportHandler::actionSection($item, $idSectionElement);
 * }
 */


/* AddEventHandler("sproduction.integration", "OnAfterOrderUpdate", "updateOnBeforeOrder1");
function updateOnBeforeOrder1($order_id)
{
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/updates-logs/log-update-order-1.txt', print_r($order_id, true));
	return $order_id;
} */

/* AddEventHandler("sproduction.integration", "OnBeforeDealUpdate", "checkBeforeDealUpdate");
function checkBeforeDealUpdate($deal_new_fields,$order_data,$deal_info){
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/updates-logs/log-before-deal-update-order1.txt', print_r($deal_new_fields, true));
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/updates-logs/log-before-deal-update-order2.txt', print_r($order_data, true));
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/updates-logs/log-before-deal-update-order3.txt', print_r($deal_info, true));
}*/

function getApplication($dl, $ord)
{
	if (
		!class_exists(\OnlineService\Orders\Applications\DealApplicationsService::class)
		&& !\Bitrix\Main\Loader::includeModule('eklektika.orders.applications')
	) {
		return;
	}

	\OnlineService\Orders\Applications\DealApplicationsService::getApplication((int) $dl, (int) $ord);
}


function addApplication($dl, $ord)
{
	if (
		!class_exists(\OnlineService\Orders\Applications\DealApplicationsService::class)
		&& !\Bitrix\Main\Loader::includeModule('eklektika.orders.applications')
	) {
		return;
	}

	\OnlineService\Orders\Applications\DealApplicationsService::addApplication((int) $dl, (int) $ord);
}

function isAgent() {
	global $USER;
	$arGroups = CUser::GetUserGroup($USER->GetID());
	foreach ($arGroups as $groupId) {
		if ($groupId == 12) {
			return true;
		}
	}
	return false;
}

function isAuthorized() {
	global $USER;
	return $USER->IsAuthorized();
}

