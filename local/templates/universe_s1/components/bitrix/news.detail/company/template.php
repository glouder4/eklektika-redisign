<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
use intec\eklectika\advertising_agent\Client;
use intec\eklectika\advertising_agent\Company;
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
<div class="intec-content">
	<div class="intec-content-wrapper">	
		<div class="company-detail intec-grid intec-grid-wrap intec-grid-i-18">
			<div class="company-detail__requisites intec-grid-item-2">
				<div class="cabinet-card">
					<div class="cabinet-card__header">
						Реквизиты
					</div>
					<div class="cabinet-card__body">
						Название компании: <?=$arResult["PROPERTIES"]["NAME_COMPANY"]["VALUE"];?><br>						
						ИНН: <?=$arResult["PROPERTIES"]["INN"]["VALUE"];?><br>
						КПП: <?=$arResult["PROPERTIES"]["KPP"]["VALUE"];?><br>
						Юр. адрес: <?=$arResult["PROPERTIES"]["ADDRESS"]["VALUE"];?><br>
						Сайт: <?=$arResult["PROPERTIES"]["WEBSITE"]["VALUE"];?><br>
					</div>
				</div>
			</div>
			<div class="company-detail__staffs intec-grid-item-2">
				<div class="cabinet-card">
					<div class="cabinet-card__header">
						Сотрудники
					</div>
					<div class="cabinet-card__body">
						<?php foreach ($arResult["PROPERTIES"]["STAFFS"]["VALUE"] as $idUser) {?>
							<div class="company-detail__staff">
								<?php $userInfo = intec\eklectika\advertising_agent\Client::getInfo($idUser);?>
								<?php $isBoss = Client::isBossCompany($arResult["ID"], $idUser);?>
								<div class="company-detail__staff-row">									
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M18.364 5.63604C21.8787 9.15076 21.8787 14.8492 18.364 18.3639C14.8493 21.8787 9.1508 21.8787 5.6361 18.3639C2.12138 14.8492 2.12138 9.15074 5.6361 5.63604C9.15082 2.12132 14.8493 2.12132 18.364 5.63604" fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
										<path d="M17.3074 19.257C16.9234 17.417 14.7054 16 12.0004 16C9.29542 16 7.07742 17.417 6.69342 19.257" fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
										<path d="M14.1213 7.87868C15.2929 9.05025 15.2929 10.9497 14.1213 12.1213C12.9497 13.2929 11.0502 13.2929 9.87868 12.1213C8.70711 10.9497 8.70711 9.05025 9.87868 7.87868C11.0502 6.70711 12.9497 6.70711 14.1213 7.87868Z" fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"></path>
									</svg>
									<?=$userInfo["LAST_NAME"]?>  <?=$userInfo["NAME"]?> 
									<?php if ($isBoss) {?>
										<span class="company-detail__staff-boss">руководитель</span>
									<?php }?>
									<?php if (!$isBoss) {?>
										<button 
											class="company-detail__staff-remove intec-ui intec-ui-control-button intec-ui-scheme-gray" 
											data-company-id="<?=$arResult["ID"];?>"
											data-user-id="<?=$userInfo["ID"];?>"
										>уволить</button>
									<?php }?>
								</div>
								<div class="company-detail__staff-contacts">
									<?php if ($userInfo["PERSONAL_PHONE"]) {?>
										Телефон: <?=$userInfo["PERSONAL_PHONE"]?><br>
									<?php }?>
									Email: <?=$userInfo["EMAIL"]?><br>
									<?php $countOrders = Client::getCountOrders($idUser, $arResult["ID"]);?>
									Количество заказов: <b><?=$countOrders;?> </b>
									<?php if ($countOrders) {?>
										<a href="orders.php?company_id=<?=$arResult["ID"]?>&user_id=<?=$userInfo["ID"]?>&company_inn=<?=$arResult["PROPERTIES"]["INN"]["VALUE"];?>" target="_blank">
											посмотреть заказы
										</a>
									<?php }?>
								
								</div>								
							</div>
						<?php }?>
					</div>	
					<script>
						$(".company-detail__staff-remove").click(function() {
							let companyId = $(this).attr("data-company-id");
							let userId = $(this).attr("data-user-id");
							if (confirm("Вам действительно больше не нужен данный сотрудник?")) {
								$.ajax({
									method: "POST",
									url: "ajax.php",
									data: { 
										action: "remove_staff", 
										company_id: companyId,
										user_id: userId,
									}
								})
								.done(function( msg ) {
									if (msg == "ok") {
										alert("сотрудник уволен");
										location.reload()
									}
								});
							}
						});
					</script>
				</div>
			</div>		
		</div>
		<br>
		<?php 
		$arOrders = Company::getOrders($arResult["ID"]);
		if ($arOrders) {?>								
			<?$APPLICATION->IncludeComponent(
				"intec:sale.personal.order.list",
				"template.1",
				Array(
					"STATUS_COLOR_N" => "green",
					"STATUS_COLOR_P" => "yellow",
					"STATUS_COLOR_F" => "gray",
					"STATUS_COLOR_PSEUDO_CANCELLED" => "red",
					"PATH_TO_DETAIL" => "order.php?order_id=#ID#&company_id=".$arResult["ID"],								
					"PATH_TO_PAYMENT" => "payment.php",
					"ORDERS_PER_PAGE" => 20,
					//"ID" => $ID,
					//"SET_TITLE" => "Y",
					"SAVE_IN_SESSION" => "Y",
					"NAV_TEMPLATE" => "",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "3600",
					"CACHE_GROUPS" => "Y",
					"HISTORIC_STATUSES" => "F",
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					'USE_FILTER' => 'N',
					'USE_SEARCH' => 'N',
					'SHOW_ONLY_CURRENT_ORDERS' => 'Y',
					'COMPANY_INN' => $arResult["PROPERTIES"]["INN"]["VALUE"],
					"TITLE" => "Заказы компании"
				)
			);?>
		<?php }?>
	</div>
</div>