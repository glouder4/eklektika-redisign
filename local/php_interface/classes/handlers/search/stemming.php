<?php

namespace OnlineService\Classes\Handlers\Search;

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\DB\SqlQueryException;
Loader::includeModule("iblock");

class Stemming
{
    protected static $targetIblocks = [43,44];

    /**
     * Генерирует все варианты написания артикула для поиска
     * Включает сокращения и опечатки
     * 
     * @param string $articleValue Значение артикула
     * @return string Строка со всеми вариантами для индексации
     */
    protected static function generateArticleSearchVariants($articleValue)
    {
        if (empty($articleValue)) {
            return '';
        }

        $variants = [];
        
        // Варианты написания слова "артикул" (включая опечатки)
        $articleWords = [
            'Артикул',
            'артикул',
            'Арт',
            'арт',
            'Арт.',
            'арт.',
            // Опечатки и варианты
            'Артикл',
            'артикл',
            'Артикль',
            'артикль',
            'Артикуль',
            'артикуль',
            'Артик',
            'артик',
            'Артк',
            'артк',
            'Артик',
            'артик',
            'Артикл.',
            'артикл.',
        ];

        // Генерируем варианты с пробелом и без
        foreach ($articleWords as $word) {
            // С пробелом: "Артикул 1350007"
            $variants[] = $word . ' ' . $articleValue;
            // Без пробела: "Артикул1350007"
            $variants[] = $word . $articleValue;
            // С двоеточием: "Артикул: 1350007"
            $variants[] = $word . ': ' . $articleValue;
            // С двоеточием без пробела: "Артикул:1350007"
            $variants[] = $word . ':' . $articleValue;
        }

        // Также добавляем просто значение артикула
        $variants[] = $articleValue;

        return implode(' ', $variants);
    }

    public static function BeforeIndexHandler($arFields)
    {
        if (
            $arFields['MODULE_ID'] === 'iblock' &&
            $arFields['ITEM_ID'] &&
            in_array($arFields['PARAM2'], self::$targetIblocks)
        )
        {

            if( $arFields['PARAM2'] == 44 ) {
                $arFilter = array("IBLOCK_ID " => $arFields['PARAM2'], "ID" => $arFields['ITEM_ID']);
                $res = \CIBlockElement::GetList(array(), $arFilter); // с помощью метода CIBlockElement::GetList вытаскиваем все значения из нужного элемента
                if ($ob = $res->GetNextElement()) { // переходим к след элементу, если такой есть
                    $arProps = $ob->GetProperties(); // свойства элемента

                    if( isset($arProps['ARTIKUL_POSTAVSHCHIKA']) && !empty($arProps['ARTIKUL_POSTAVSHCHIKA']['VALUE']) ){
                        $val = $arProps['ARTIKUL_POSTAVSHCHIKA']['VALUE'];

                        // Генерируем все варианты написания артикула для поиска
                        $searchVariants = self::generateArticleSearchVariants($val);
                        
                        $arFields["BODY"] .= " ";
                        $arFields["BODY"] .= $searchVariants;
                    }
                }
            }


                /*$arFields["BODY"] .= " ";
                $arFields["BODY"] .= mb_substr($arFields["TITLE"],0,$i, 'UTF-8');*/
        }

        return $arFields; // вернём изменения
    }

    public static function beforeIndexUpdate($ID, $arFields)
    {

        return $arFields;
    }

    public static function OnAfterIndexAdd($ID, $arFields)
    {

        return $arFields;
    }

}