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
		   
		   // Получаем ВСЕ компании пользователя (как сотрудник или руководитель)
		   $rsCompanies = CIBlockElement::GetList(
			   [],
			   [
				   'IBLOCK_ID' => 57,
				   [
					   'LOGIC' => 'OR',
					   'PROPERTY_OS_COMPANY_USERS' => $USER->GetID(),
					   'PROPERTY_OS_COMPANY_BOSS' => $USER->GetID()
				   ]
			   ],
			   false,
			   false,
			   ['ID', 'PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING', 'PROPERTY_OS_HOLDING_OF']
		   );
		   
		   $userCompanies = [];
		   while ($company = $rsCompanies->GetNext()) {
			   $userCompanies[] = $company;
		   }
		   
		   // Группируем компании по холдингам
		   $holdingsData = [];
		   $processedHoldings = []; // Чтобы избежать дублирования холдингов
		   
		   foreach ($userCompanies as $userCompany) {
			   $holdingKey = null;
			   $headCompany = null;
			   $childCompanies = [];
			   
			   // Проверяем, является ли компания головной холдинга
			   if (!empty($userCompany['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE']) && 
				   ($userCompany['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE'] === 'Y' || 
					$userCompany['PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING_VALUE'] === 'Да')) {
				   
				   // Это головная компания холдинга
				   $holdingKey = 'head_' . $userCompany['ID'];
				   
				   // Проверяем, не обработали ли мы уже этот холдинг
				   if (in_array($holdingKey, $processedHoldings)) {
					   continue;
				   }
				   
				   $headCompany = $userCompany;
				   
				   // Получаем все дочерние компании этого холдинга
				   $rsHoldingCompanies = CIBlockElement::GetList(
					   [],
					   [
						   'IBLOCK_ID' => 57,
						   'PROPERTY_OS_HOLDING_OF' => $userCompany['ID']
					   ],
					   false,
					   false,
					   ['ID']
				   );
				   
				   while ($holdingCompany = $rsHoldingCompanies->GetNext()) {
					   $childCompanies[] = $holdingCompany['ID'];
				   }
				   
			   } else if (!empty($userCompany['PROPERTY_OS_HOLDING_OF_VALUE'])) {
				   
				   // Это дочерняя компания холдинга
				   $holdingId = $userCompany['PROPERTY_OS_HOLDING_OF_VALUE'];
				   $holdingKey = 'head_' . $holdingId;
				   
				   // Проверяем, не обработали ли мы уже этот холдинг
				   if (in_array($holdingKey, $processedHoldings)) {
					   continue;
				   }
				   
				   // Получаем головную компанию
				   $rsHeadCompany = CIBlockElement::GetById($holdingId);
				   if ($headCompanyData = $rsHeadCompany->GetNext()) {
					   $headCompany = $headCompanyData;
				   }
				   
				   // Получаем все дочерние компании этого холдинга
				   $rsHoldingCompanies = CIBlockElement::GetList(
					   [],
					   [
						   'IBLOCK_ID' => 57,
						   'PROPERTY_OS_HOLDING_OF' => $holdingId
					   ],
					   false,
					   false,
					   ['ID']
				   );
				   
				   while ($holdingCompany = $rsHoldingCompanies->GetNext()) {
					   $childCompanies[] = $holdingCompany['ID'];
				   }
				   
			   } else {
				   // Компания без холдинга - отдельное дерево
				   $holdingKey = 'standalone_' . $userCompany['ID'];
				   
				   // Проверяем, не обработали ли мы уже эту компанию
				   if (in_array($holdingKey, $processedHoldings)) {
					   continue;
				   }
				   
				   $headCompany = $userCompany;
			   }
			   
			   // Добавляем холдинг в список
			   if ($headCompany) {
				   $holdingsData[] = [
					   'head_company' => $headCompany,
					   'child_companies' => $childCompanies
				   ];
				   $processedHoldings[] = $holdingKey;
			   }
		   }

            ?>
            
            <?php if (!empty($holdingsData)): ?>
            <?php foreach ($holdingsData as $holdingIndex => $companiesData): ?>
            <div class="companies-compact <?= $holdingIndex > 0 ? 'companies-compact--additional' : '' ?>">
                <!-- Головная компания -->
                <?php
                $headCompanyData = $companiesData['head_company'];
                $rsHeadCompany = CIBlockElement::GetById($headCompanyData['ID']);
                if ($headCompanyElement = $rsHeadCompany->GetNextElement()) {
                    $headCompanyProps = $headCompanyElement->GetProperties();
                    $headCompanyFields = $headCompanyElement->GetFields();
                    
                    $isMarketingAgent = $headCompanyProps['OS_IS_MARKETING_AGENT']['VALUE_XML_ID'] ?? '';
                    $isHeadOfHolding = $headCompanyProps['OS_COMPANY_IS_HEAD_OF_HOLDING']['VALUE_XML_ID'] ?? '';
                    $companyName = $headCompanyFields['NAME'];
                    $detailUrl = $headCompanyFields['DETAIL_PAGE_URL'];
                ?>
                <a href="<?=$detailUrl?>" class="company-item company-item--head">
                    <div class="company-item__content">
                        <div class="company-item__name"><?=$companyName?></div>
                        <div class="company-item__badges">
                            <span class="badge badge--<?=($isMarketingAgent == 'YES') ? 'active' : 'inactive'?>">
                                <?=($isMarketingAgent == 'YES') ? 'Активно' : 'На модерации'?>
                            </span>
                            <?if($isHeadOfHolding == 'Y'):?>
                            <span class="badge badge--head">Головная</span>
                            <?endif;?>
                        </div>
                    </div>
                </a>
                <?php } ?>
                
                <!-- Дочерние компании -->
                <?php if (!empty($companiesData['child_companies'])): ?>
                <?php foreach ($companiesData['child_companies'] as $childId): ?>
                <?php
                $rsChildCompany = CIBlockElement::GetById($childId);
                if ($childElement = $rsChildCompany->GetNextElement()) {
                    $childProps = $childElement->GetProperties();
                    $childFields = $childElement->GetFields();
                    
                    $isMarketingAgent = $childProps['OS_IS_MARKETING_AGENT']['VALUE_XML_ID'] ?? '';
                    $companyName = $childFields['NAME'];
                    $detailUrl = $childFields['DETAIL_PAGE_URL'];
                ?>
                        <?php
                            if($isMarketingAgent == 'YES'){ ?>
                                <a href="<?=$detailUrl?>" class="company-item company-item--child">
                                    <div class="company-item__content">
                                        <div class="company-item__name"><?=$companyName?></div>
                                        <div class="company-item__badges">
                                        <span class="badge badge--<?=($isMarketingAgent == 'YES') ? 'active' : 'inactive'?>">
                                            <?=($isMarketingAgent == 'YES') ? 'Активно' : 'На модерации'?>
                                        </span>
                                        </div>
                                    </div>
                                </a>
                            <?php
                            }
                            else{?>
                                <div class="company-item company-item--child">
                                    <div class="company-item__content">
                                        <div class="company-item__name"><?=$companyName?></div>
                                        <div class="company-item__badges">
                                        <span class="badge badge--<?=($isMarketingAgent == 'YES') ? 'active' : 'inactive'?>">
                                            <?=($isMarketingAgent == 'YES') ? 'Активно' : 'На модерации'?>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                <?php } ?>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="companies-empty">
                <p>Компании не найдены</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>