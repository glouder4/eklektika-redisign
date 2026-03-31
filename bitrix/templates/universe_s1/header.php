<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\Core;
use intec\core\helpers\FileHelper;
use intec\constructor\Module as Constructor;
use intec\constructor\models\build\Template;

Loc::loadMessages(__FILE__);

require(__DIR__.'/parts/preload.php');

$request = Core::$app->request;
$page->execute(['state' => 'loading']);

/** @var Template $template */
$template = $build->getTemplate();

if (empty($template))
    return;

foreach ($template->getPropertiesValues() as $key => $value)
    $properties->set($key, $value);

unset($value);
unset($key);

if (!Constructor::isLite())
    $template->populateRelation('build', $build);

if (FileHelper::isFile($directory.'/parts/custom/initialize.php'))
    include($directory.'/parts/custom/initialize.php');

require($directory.'/parts/metrika.php');
require($directory.'/parts/assets.php');

if (FileHelper::isFile($directory.'/parts/custom/start.php'))
    include($directory.'/parts/custom/start.php');

$APPLICATION->AddBufferContent([
    'intec\\template\\Marking',
    'openGraph'
]);

$page->execute(['state' => 'loaded']);
$part = Constructor::isLite() ? 'lite' : 'base';

?><!DOCTYPE html>
<html lang="<?=LANGUAGE_ID?>">
    <head>
        <?php if (FileHelper::isFile($directory.'/parts/custom/header.start.php')) include($directory.'/parts/custom/header.start.php') ?>
        <title><?php $APPLICATION->ShowTitle() ?></title>
        <?php $APPLICATION->ShowHead() ?>
        <meta name="viewport" content="initial-scale=1.0, width=device-width">
        <meta name="cmsmagazine" content="79468b886bf88b23144291bf1d99aa1c" />
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <?php
            // Telegram/webpagebot чаще корректно читает Open Graph через атрибут `property`.
            // Поэтому выводим OG-теги вручную.
            $ogType = $APPLICATION->GetPageProperty('og:type') ?: 'website';

            // Bitrix может иметь разницу между ShowTitle()/GetTitle(), поэтому берем из page properties как приоритет.
            $titleProp = $APPLICATION->GetPageProperty('title') ?: $APPLICATION->GetTitle();
            $descriptionProp = $APPLICATION->GetPageProperty('description') ?: $titleProp;

            $ogTitle = $APPLICATION->GetPageProperty('og:title') ?: $titleProp;
            $ogDescription = $APPLICATION->GetPageProperty('og:description') ?: $descriptionProp;

            // Нормализация HostInfo: убираем :80/:443.
            $hostInfo = Core::$app->request->getHostInfo();
            $parts = parse_url($hostInfo);
            if (is_array($parts) && !empty($parts['scheme']) && !empty($parts['host'])) {
                $scheme = $parts['scheme'];
                $host = $parts['host'];
                $port = $parts['port'] ?? null;

                if ($port !== null) {
                    $port = (int)$port;
                    if (($scheme === 'https' && $port === 443) || ($scheme === 'http' && $port === 80)) {
                        $port = null;
                    }
                }

                $normalizedHostInfo = $scheme . '://' . $host . ($port ? ':' . $port : '');
            } else {
                $normalizedHostInfo = preg_replace('/:(80|443)(?=\/|$)/', '', $hostInfo);
                $normalizedHostInfo = $normalizedHostInfo ?: $hostInfo;
            }

            $ogUrl = $APPLICATION->GetPageProperty('og:url') ?: ($normalizedHostInfo . $APPLICATION->GetCurUri());
            $ogImage = $APPLICATION->GetPageProperty('og:image') ?: ($normalizedHostInfo . SITE_DIR . 'include/logotype.png');

            $e = static function ($value) {
                return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
            };
        ?>
        <meta property="og:type" content="<?= $e($ogType) ?>" />
        <meta property="og:title" content="<?= $e($ogTitle) ?>" />
        <meta property="og:description" content="<?= $e($ogDescription) ?>" />
        <meta property="og:image" content="<?= $e($ogImage) ?>" />
        <meta property="og:url" content="<?= $e($ogUrl) ?>" />
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="apple-touch-icon" href="/favicon.png">
        <?php if (!Constructor::isLite()) { ?>
            <style type="text/css"><?= $template->getCss() ?></style>
            <style type="text/css"><?= $template->getLess() ?></style>
            <script type="text/javascript"><?= $template->getJs() ?></script>
        <?php } ?>
        <?php if (FileHelper::isFile($directory.'/parts/custom/header.end.php')) include($directory.'/parts/custom/header.end.php') ?>
    </head>
    <body class="public intec-adaptive">
        <?php if (FileHelper::isFile($directory.'/parts/custom/body.start.php')) include($directory.'/parts/custom/body.start.php') ?>
        <?php $APPLICATION->IncludeComponent(
            'intec.universe:system',
            'basket.manager',
            array(
                'BASKET' => 'Y',
                'COMPARE' => 'Y',
                'COMPARE_NAME' => 'compare',
                'CACHE_TYPE' => 'N'
            ),
            false,
            array('HIDE_ICONS' => 'Y')
        ); ?>
        <?php if (
            $properties->get('base-settings-show') == 'all' ||
            $properties->get('base-settings-show') == 'admin' && $USER->IsAdmin()
        ) { ?>
            <?php $APPLICATION->IncludeComponent(
                'intec.universe:system.settings',
                '.default',
                array(
                    'MODE' => 'render',
                    'MENU_ROOT_TYPE' => 'top',
                    'MENU_CHILD_TYPE' => 'left'
                ),
                false,
                array(
                    'HIDE_ICONS' => 'N'
                )
            ); ?>
        <? } ?>
        <?php include($directory.'/parts/'.$part.'/header.php'); ?>