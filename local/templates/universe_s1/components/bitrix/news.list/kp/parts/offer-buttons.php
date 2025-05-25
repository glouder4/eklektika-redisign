<div class="kp-offers__buttons">
	<button 
		class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent"
		v-show="!offer.visible" 
		@click="offer.visible=true">
		<i class="fal fa-eye"></i>&nbsp;
		открыть
	</button>
	<button 
		class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent"
		v-show="offer.visible" 
		@click="offer.visible=false">
		<i class="fal fa-times"></i>&nbsp;
		закрыть
	</button>
	<button 
		class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent"
		@click="saveOffer(indexOffer)">
		<i class="fal fa-save"></i>&nbsp;
		cохранить
	</button>
	<button 
		class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent"
		@click="savePdf(indexOffer)">
		<i class="fal fa-download"></i>&nbsp;
		скачать pdf
	</button>
	<button 
		class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent"
		@click="deleteOffer(offer)">
		<i class="fal fa-trash-alt"></i>&nbsp;
		удалить
	</button>
</div>