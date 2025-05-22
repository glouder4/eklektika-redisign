<div class="kp-offers__contacts">
	<div class="intec-grid intec-grid-i-10">
		<div class="intec-grid-item">
			<div> 
				<label>
					<div>Название компании:</div>
					<input class="intec-ui intec-ui-control-input" type="text" v-model="offer.contacts.nameCompany">
				</label>
			</div>
			<div>
				<label>
					<div>ФИО менеджера:</div>
					<input class="intec-ui intec-ui-control-input" type="text" v-model="offer.contacts.fioManager">
				</label>
			</div>
			<div>
				<label>
					<div>Телефон менеджера:</div>
					<input class="intec-ui intec-ui-control-input" type="text" v-model="offer.contacts.phoneManager">
				</label>
			</div>
			<div>
				<label>
					<div>E-mail менеджера:</div>
					<input class="intec-ui intec-ui-control-input" type="text" v-model="offer.contacts.emailManager">
				</label>
			</div>
			
		</div>
		<div class="intec-grid-item">
			<div>
				<label>
					<div>Дата:</div>
					<input class="intec-ui intec-ui-control-input" type="text" v-model="offer.contacts.date">
				</label>
			</div>
			<div>
				<label>
					<div>Наименование организации клиента:</div>
					<input class="intec-ui intec-ui-control-input" type="text" v-model="offer.contacts.organisationClient">
				</label>
			</div>
			<div>
				<label>
					<div>Имя клиента:</div>
					<input class="intec-ui intec-ui-control-input" type="text" v-model="offer.contacts.nameClient">
				</label>
			</div>
			<div>
				<div>Логотип компании:</div>
				<label style="display: flex;align-items: center; justify-content: flex-start;">
					
					<img :src="offer.contacts.logo" class="kp-offers__logo" v-show="offer.contacts.logo">
					<label :for="'upload_logo'+indexOffer" class="kp-offers__td-image-label">
						
						<span class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent">
						<i class="fal fa-paperclip"></i>&nbsp;выберите файл</span>
						<input type="file" :id="'upload_logo' + indexOffer ">
						<button 
							type="button" 
							@click="uploadLogo(indexOffer)" 
							class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent"
						>
						<i class="fas fa-cloud-upload-alt"></i> &nbsp; загрузить логотип
						</button>
					</label>
					
				</label>
			</div>
		</div>
	</div>
</div>