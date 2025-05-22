<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
function imgToBase64($path) {	
	$type = pathinfo($path, PATHINFO_EXTENSION);
	$data = file_get_contents($path);
	$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
	return $base64;
}?>
<html>
	<body>
		<div>
		<table style="width:100%">
			<tr>
				<td>
					<?php if ($arData["CONTACTS"]["logo"]) {?>
							<img width="120" style="max-height: 100px;" src="<?=imgToBase64($_SERVER["DOCUMENT_ROOT"].$arData["CONTACTS"]["logo"])?>">
					<?php }?>
				</td>
				<td><?=$arData["CONTACTS"]["nameCompany"]?></td>
				<td style="text-align:right;"><?=$arData["CONTACTS"]["date"]?></td>
			</tr>
		</table>
		<br>
		
		<center>
			<h1>Коммерческое<br>предложение</h1>
		</center>
		<br>
		<div style="text-align: center">
			Менеджер: <?=$arData["CONTACTS"]["fioManager"]?><br>
			<?=$arData["CONTACTS"]["phoneManager"]?> / <?=$arData["CONTACTS"]["emailManager"]?><br>
			Клиент: <?=$arData["CONTACTS"]["nameClient"]?>
		</div>
		<br><br>
		<table>
			<?php 
			$arCounters = [
				"quantity" => 0,
				"price" => 0,
				"priceDrawing" => 0
			];
			foreach ($arData["PRODUCTS"] as $arProduct) {?>
				<tr>
					<td style="vertical-align:top;text-align: center;";>
						<img style="margin-top: 10px;max-height:90px;max-width:90px;" src="<?=imgToBase64($_SERVER["DOCUMENT_ROOT"].$arProduct["image"])?>">
					</td>
					<td style="font-size:10px;">
						<b><?=$arProduct["name"]?></b>
						<br>
						<table style="width:100%;">
							<tr>
								<td style="width:250px;max-height:150px;vertical-align:top;">
									<b>Артикул:</b> <?=$arProduct["article"]?><br>
									<b>Цвет:</b> <?=$arProduct["color"]?><br>
									<b>Материал:</b> <?=$arProduct["material"]?><br>
									<b>Размер:</b> <?=$arProduct["size"]?>
								</td>
								<td style="font-size:9px;vertical-align:top;">
									<?=TruncateText(strip_tags($arProduct["description"]),150);?>
								</td>
							</tr>
						</table>
						<br>
						<table style="width:100%">
							<tr>
								<td>
									<center>
										<div>Количество</div>									
										<div style="border: 1px solid #ccc">
											<?=$arProduct["edition"]?>шт
										</div>
									</center>
								</td>
								<td>
									<center>
										<div>Цена товара</div>									
										<div style="border: 1px solid #ccc">
											<?=$arProduct["price"]?>р
										</div>
									</center>
								</td>
								<td>
									<center>
										<div>Стоимость товаров</div>									
										<div style="border: 1px solid #ccc">
											<?=$arProduct["price"]*$arProduct["edition"]?>р
										</div>
									</center>
								</td>
								<td>
									<center>
										<div>Стоимость нанесения</div>									
										<div style="border: 1px solid #ccc">
											<?
											$priceDrawing = 0;
											foreach ($arProduct["drawing"] as $arDrawing) {
												$priceDrawing += $arDrawing["price"]*$arProduct["edition"];
											}?>
											<?=$priceDrawing?>р
										</div>
									</center>
								</td>
								<td>
									<center>
										<div>Итого</div>									
										<div style="border: 1px solid #ccc">
											<?=$arProduct["price"]*$arProduct["edition"] + $priceDrawing?>р
										</div>
									</center>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<?php 
				$arCounters["quantity"] += $arProduct["edition"];
				$arCounters["price"] += $arProduct["price"]*$arProduct["edition"];
				$arCounters["priceDrawing"] += $priceDrawing;
				
				?>
			<?php }?>
		</table>
		<br>
		<table>
			<tr>				
				<td>
					Общий заказ
				</td>
				<td>
					<center>
						<div>Количество</div>					
						<div style="border:1px solid #ccc">
							<?=$arCounters["quantity"]?>шт
						</div>
					</center>
				</td>			
				<td>
					<center>
						<div>Стоимость товаров</div>					
						<div style="border:1px solid #ccc">
							<?=$arCounters["price"]?>р
						</div>
					</center>
				</td>
				<td>
					<center>
						<div>Стоимость нанесения</div>					
						<div style="border:1px solid #ccc">
							<?=$arCounters["priceDrawing"]?>р
						</div>
					</center>
				</td>
				<td>
					<center>
						<div>Итого</div>					
						<div style="border:1px solid #ccc">
							<?=$arCounters["priceDrawing"] + $arCounters["price"]?>р
						</div>
					</center>
				</td>
			</tr>
		</table>
		<br>
		<h1>Благодарим вас за сотрудничество!</h1>
	</body>
</html>