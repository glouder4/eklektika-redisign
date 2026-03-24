<?php
namespace intec\template;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use CMain;
use intec\Core;

class Marking
{
    private static function normalizeHostInfo($hostInfo)
    {
        $parts = parse_url($hostInfo);

        if (is_array($parts) && !empty($parts['scheme']) && !empty($parts['host'])) {
            $scheme = $parts['scheme'];
            $host = $parts['host'];
            $port = $parts['port'] ?? null;

            if ($port !== null) {
                $port = (int)$port;

                // Убираем порты по умолчанию, чтобы ссылки в OGP не менялись от :443/:80.
                if (($scheme === 'https' && $port === 443) || ($scheme === 'http' && $port === 80)) {
                    $port = null;
                }
            }

            return $scheme . '://' . $host . ($port ? ':' . $port : '');
        }

        $normalized = preg_replace('/:(80|443)(?=\/|$)/', '', $hostInfo);

        return $normalized ?: $hostInfo;
    }

    public static function openGraph()
    {
        global $APPLICATION;

        /**
         * @global CMain $APPLICATION
         */

        if (!$APPLICATION->GetPageProperty('og:type'))
            $APPLICATION->SetPageProperty('og:type', 'website');

        if (!$APPLICATION->GetPageProperty('og:title'))
            $APPLICATION->SetPageProperty('og:title', $APPLICATION->GetTitle());

        if (!$APPLICATION->GetPageProperty('og:description'))
            if (!$APPLICATION->GetPageProperty('description'))
                $APPLICATION->SetPageProperty('og:description', $APPLICATION->GetTitle());
            else
                $APPLICATION->SetPageProperty('og:description', $APPLICATION->GetPageProperty('description'));

        if (!$APPLICATION->GetPageProperty('og:image'))
            $APPLICATION->SetPageProperty('og:image', self::normalizeHostInfo(Core::$app->request->getHostInfo()).SITE_DIR.'include/logotype.png');

        if (!$APPLICATION->GetPageProperty('og:url'))
            $APPLICATION->SetPageProperty('og:url', self::normalizeHostInfo(Core::$app->request->getHostInfo()).$APPLICATION->GetCurUri());
    }
}