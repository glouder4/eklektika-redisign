<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

/**
 * @var array $arTickets
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent $component
 */

?>
<div class="sale-personal-section-claims">
    <div class="sale-personal-section-claims-header">
        <div class="sale-personal-section-claims-title">
            Компании
        </div>
    </div>
    <div class="sale-personal-section-claims-wrap">
        <div class="sale-personal-section-claims-items">
		   <?php 
		   global $USER; 
		   
		   // Получаем компанию пользователя
		   $rsCompany = CIBlockElement::GetList(
			   [],
			   [
				   'IBLOCK_ID' => 57,
				   'PROPERTY_OS_COMPANY_USERS' => $USER->GetID(),
				   'ACTIVE' => 'Y'
			   ],
			   false,
			   false,
			   ['ID', 'PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING', 'PROPERTY_OS_HOLDING_OF']
		   );
		   
		   $userCompany = $rsCompany->GetNext();
		   $companyIds = [];
		   
		   if ($userCompany) {
			   // Проверяем, является ли компания головной холдинга
			   if (!empty($userCompany['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE']) && 
				   ($userCompany['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE'] === 'Y' || 
					$userCompany['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE'] === 'Да')) {
				   
				   // Сценарий 1: Головная компания - получаем все компании холдинга
				   $rsHoldingCompanies = CIBlockElement::GetList(
					   [],
					   [
						   'IBLOCK_ID' => 57,
						   'PROPERTY_OS_HOLDING_OF' => $userCompany['ID'],
						   'ACTIVE' => 'Y'
					   ],
					   false,
					   false,
					   ['ID']
				   );
				   
				   while ($holdingCompany = $rsHoldingCompanies->GetNext()) {
					   $companyIds[] = $holdingCompany['ID'];
				   }
				   
				   // Добавляем саму головную компанию
				   $companyIds[] = $userCompany['ID'];
				   
			   } else if (!empty($userCompany['PROPERTY_OS_HOLDING_OF_VALUE'])) {
				   
				   // Сценарий 2: Обычная компания - получаем все компании того же холдинга
				   $holdingId = $userCompany['PROPERTY_OS_HOLDING_OF_VALUE'];
				   
				   // Получаем все компании этого холдинга
				   $rsHoldingCompanies = CIBlockElement::GetList(
					   [],
					   [
						   'IBLOCK_ID' => 57,
						   'PROPERTY_OS_HOLDING_OF' => $holdingId,
						   'ACTIVE' => 'Y'
					   ],
					   false,
					   false,
					   ['ID']
				   );
				   
				   while ($holdingCompany = $rsHoldingCompanies->GetNext()) {
					   $companyIds[] = $holdingCompany['ID'];
				   }
				   
				   // Добавляем головную компанию холдинга
				   $companyIds[] = $holdingId;
				   
			   } else {
				   // Если нет связей с холдингом - только своя компания
				   $companyIds[] = $userCompany['ID'];
			   }
		   }
		   
		   // Формируем фильтр для компонента
		   if (!empty($companyIds)) {
			   $GLOBALS["filterUser"] = ["ID" => $companyIds];
		   } else {
			   $GLOBALS["filterUser"] = ["ID" => 0]; // Нет доступа ни к каким компаниям
		   }

            ?>
           <?php $APPLICATION->IncludeComponent("bitrix:news.list", "companies", Array(
					"IBLOCK_TYPE" => "personal",
					"IBLOCK_ID" => "57",
					"NEWS_COUNT" => "",
					"SORT_BY1" => "ACTIVE_FROM",
					"SORT_ORDER1" => "DESC",
					"SORT_BY2" => "SORT",
					"SORT_ORDER2" => "ASC",
					"FILTER_NAME" => "filterUser",
					"FIELD_CODE" => array(
						0 => "",
						1 => "",
					),
					"PROPERTY_CODE" => array(
						0 => "NAME_COMPANY",
						1 => "INN",
						2 => "WEBSITE",
						3 => "SPHERE",
						4 => "ADDRESS",
						5 => "KPP",
					),
					"CHECK_DATES" => "Y",
					"DETAIL_URL" => "",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"AJAX_OPTION_ADDITIONAL" => "",	
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "36000000",
					"CACHE_GROUPS" => "Y",
					"PREVIEW_TRUNCATE_LEN" => "",
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"SET_TITLE" => "N",	
					"SET_BROWSER_TITLE" => "N",
					"SET_META_KEYWORDS" => "N",
					"SET_META_DESCRIPTION" => "Т",
					"SET_LAST_MODIFIED" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"INCLUDE_SUBSECTIONS" => "Y",
					"STRICT_SECTION_CHECK" => "N",
					"DISPLAY_DATE" => "Y",
					"DISPLAY_NAME" => "Y",
					"DISPLAY_PICTURE" => "Y",
					"DISPLAY_PREVIEW_TEXT" => "Y",
					"PAGER_TEMPLATE" => ".default",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "Y",	
					"PAGER_TITLE" => "Новости",	
					"PAGER_SHOW_ALWAYS" => "N",	
					"PAGER_DESC_NUMBERING" => "N",	
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	
					"PAGER_SHOW_ALL" => "N",	
					"PAGER_BASE_LINK_ENABLE" => "N",	
					"SET_STATUS_404" => "N",	
					"SHOW_404" => "N",	
					"MESSAGE_404" => "",
				),
				$component
			);?>
        </div>
    </div>
</div>