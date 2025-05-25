<div class="kp-offers">
	<div v-if="offers.length">
		<div class="" v-for="(offer, indexOffer) in offers"  :key="indexOffer">
			<div class="kp-offers__item" :class="offer.visible?'kp-offers__item--active':''">
				<div class="kp-offers__item-title">
					<div class="kp-offers__item-name">{{offer.name}}</div>
					<?php include(__DIR__.'/offer-buttons.php');?>
				</div>
				<div class="kp-products" v-show="offer.visible">
					<?php include(__DIR__.'/products.php');?>
					<?php include(__DIR__.'/contacts.php');?>
					<?php include(__DIR__.'/offer-buttons.php');?>
				</div>
			</div>
		</div>
	</div>
	<div v-else>
		<div class="kp-offers__body">
			У вас нет коммерческих предложений
		</div>
	</div>
	<hr>
	<div class="kp-offers__buttons">
		<button 
			class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent"
			@click="addOffer">
			добавить коммерческое предложение
		</button>		
	</div>
	<hr>
	<div>
		<div>Выполнить импорт с файла excel:</div>
		<label class="kp-offers__excel-label">
			<span class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent">
				<i class="fal fa-paperclip"></i>&nbsp;выберите файл
			</span>
			<input type="file" name="file" id="excelFile">
		</label>
		<button 
			class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent"
			@click="importOffer">
			<i class="fas fa-cloud-upload-alt"></i>&nbsp;
			Импортировать
		</button>
	</div>
</div>