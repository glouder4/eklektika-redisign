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
use intec\eklectika\advertising_agent\Client;

$this->setFrameMode(true);
CModule::IncludeModule("iblock");
CModule::IncludeModule("intec.eklectika");
?>

<div class="intec-content intec-content-visible">
	<div class="intec-content-wrapper">
		<div class="saved-baskets">
							
			<?php if ($arResult["ITEMS"]) {?>
				<div class="intec-grid intec-grid-wrap intec-grid-i-10 intec-a-v-stretch">
					<?php foreach($arResult["ITEMS"] as $arItem) {
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
						?>
						<div class="intec-grid-item-2 intec-grid-item-768-1">
							<div class="saved-baskets-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">			
								<?php 
								$arData = json_decode($arItem["DISPLAY_PROPERTIES"]["DATA"]["~VALUE"]["TEXT"],true);	
								?>
								<table class="intec-ui-markup-table" style="width:100%;">
								<thead>
									<tr>
										<th>Название</th>
										<th>Цена</th>
										<th>Количество</th>
									</tr>
								</thead>
								<?foreach ($arData as $data) {?>
									<tr>
										<td><?=$data["NAME"]?></td>
										<td><?=FormatCurrency($data["PRICE"], 'RUB');?></td>
										<td><?=(int)$data["QUANTITY"]?></td>
									</tr>
								<?php }?>
								</table>
								<br>
								<button 
									class="intec-ui intec-ui-control-button intec-ui-scheme-current intec-ui-size-1" 
									data-role="restore-basket" 
									data-id="<?=$arItem["ID"]?>">
									Восстановить корзину
								</button>
								<button 
									class="intec-ui intec-ui-control-button intec-ui-scheme-current intec-ui-size-1" 
									data-role="delete-basket"  
									data-id="<?=$arItem["ID"]?>">
									Удалить корзину
								</button>
								<?php if (Client::isAgent()) {?>
									<button 
										class="intec-ui intec-ui-control-button intec-ui-scheme-current intec-ui-size-1" 
										data-role="create-kp"  
										data-id="<?=$arItem["ID"]?>">
										Создать КП
									</button>
								<?php }?>								
							</div>
						</div>
					<?php }?>
				</div>
			<?php } else {?>
				Очень жаль, у вас нет сохраннных корзин 😞
			<?}?>
		</div>
	</div>
	<script>
		$(function() {
			$(".saved-baskets [data-role='restore-basket']").on("click", function() {					
				let result = confirm("Хотите ли вы сохранить текущую корзину перед восстановлением?");		
				let id = $(this).attr("data-id");
				$.ajax({
				  method: "POST",
				  url: "<?=$templateFolder?>/ajax.php",
				  data: { id: id, action: "restore", saveOld: result}
				}).done(function() {
    					alert( "Корзина восстановлена!");
					location.reload();
  				});
				
			});
			$(".saved-baskets [data-role='delete-basket']").on("click", function() {
				let result = confirm("Вы уверены что хотите удалить эту корзину?");
				if (result) {
					let id = $(this).attr("data-id");
					$.ajax({
					  method: "POST",
					  url: "<?=$templateFolder?>/ajax.php",
					  data: { id: id, action: "delete"}
					}).done(function() {
	    					alert( "Удаление успешно");
						location.reload();
	  				});
				}
			});
			$(".saved-baskets [data-role='create-kp']").on("click", function() {
				let result = confirm("Вы уверены что хотите создать коммерческое предложение из этой корзины?");
				if (result) {
					let id = $(this).attr("data-id");
					$.ajax({
					  method: "POST",
					  url: "<?=$templateFolder?>/ajax.php",
					  data: { id: id, action: "create_kp"}
					}).done(function(data) {
						// alert( "Коммерческое предложение создано");
						// location.reload();
						window.location.href = data;
	  				});
				}
			});
		});
	</script>
</div>
