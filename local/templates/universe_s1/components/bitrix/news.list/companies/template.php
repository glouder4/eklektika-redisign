<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
CModule::IncludeModule("intec.eklectika");
?>
<?php if ($arResult["ITEMS"]) {?>
	<div class="companies-list">
		<?php foreach($arResult["ITEMS"] as $arItem) {
            //pre($arItem);
            ?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<div class="company-card" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<div class="company-card__header">
					<h3 class="company-card__title"><?echo $arItem["PROPERTIES"]["OS_COMPANY_NAME"]["VALUE"]?></h3>
					<?php if (intec\eklectika\advertising_agent\Client::isBossCompany($arItem['ID'])) {?>
						<a class="company-card__link" target="_blank" href="<?echo $arItem["DETAIL_PAGE_URL"]?>">
							подробнее
						</a>
					<?php }?>
				</div>
				
				<div class="company-card__content">
					<div class="company-card__info">
						<div class="company-card__field">
							<span class="company-card__label">ИНН:</span>
							<span class="company-card__value"><?=$arItem["PROPERTIES"]["OS_COMPANY_INN"]["VALUE"]?></span>
						</div>
						
						<?php if (!empty($arItem["PROPERTIES"]["OS_COMPANY_WEB_SITE"]["VALUE"])) { ?>
						<div class="company-card__field">
							<span class="company-card__label">Сайт:</span>
							<span class="company-card__value">
								<a href="<?=strpos($arItem["PROPERTIES"]["OS_COMPANY_WEB_SITE"]["VALUE"], 'http') === 0 ? $arItem["PROPERTIES"]["OS_COMPANY_WEB_SITE"]["VALUE"] : 'http://' . $arItem["PROPERTIES"]["OS_COMPANY_WEB_SITE"]["VALUE"]?>" target="_blank">
									<?=$arItem["PROPERTIES"]["OS_COMPANY_WEB_SITE"]["VALUE"]?>
								</a>
							</span>
						</div>
						<?php } ?>
						
						<div class="company-card__field">
							<span class="company-card__label">Сотрудников:</span>
							<span class="company-card__value company-card__employees">
								<?php
								$value = $arItem["PROPERTIES"]["OS_COMPANY_USERS"]["VALUE"] ?? null;
								echo (is_array($value) || $value instanceof Countable) ? count($value) : 0;
								?>
							</span>
						</div>
					</div>
				</div>

                <?php
                $user = \CUser::GetByID($USER->GetID())->Fetch();

                if( $user['UF_IS_DIRECTOR'] ){
                    ?>
                    <div class="company-card__footer">
                        <a class="company-card__btn" href="<?=$arItem['DETAIL_PAGE_URL']?>">Подробнее</a>
                    </div>
                    <?php
                }
                ?>
			</div>
		<?php }?>
	</div>
<?php }?>