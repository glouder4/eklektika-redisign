<table class="kp-offers__products-table">
	<thead class="kp-offers__products-thead">
		<tr>
			<th class="kp-offers__products-th">
				Фото товара
			</th>
			<th class="kp-offers__products-th">
				Характеристики
			</th>
			<th class="kp-offers__products-th">
				Нанесение
			</th>
			<th class="kp-offers__products-th">
				<div style="display: flex;align-items:center">
					<div>Стоимость</div>
					<button 
						style="margin-left:auto" 
						class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3" 
						title="свернуть товары"
						@click="offer.productsVisible = false"
						v-if="offer.productsVisible">
						<i class="fal fa-chevron-up"></i>
					</button>
					<button 
						style="margin-left:auto" 
						class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3" 
						title="свернуть товары"
						@click="offer.productsVisible = true"
						v-if="!offer.productsVisible"
					>
						<i class="fal fa-chevron-down"></i>
					</button>
				</div>
			</th>
		</tr>
	</thead>
	<tbody v-if="offer.productsVisible">
		<tr :key="indexProduct" v-for="(product, indexProduct) in offer.products">
			<td class="kp-offers__td-image kp-offers__products-td">
				<img :src="product.image" class="kp-offers__image" v-show="product.image">
				<label :for="'upload_image'+indexOffer+indexProduct" class="kp-offers__td-image-label">
					
					<span class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent"><i class="fal fa-paperclip"></i>&nbsp;выберите файл</span>
					<input type="file" :id="'upload_image' + indexOffer + indexProduct">
				</label>
				<button 
					type="button" 
					@click="uploadImage(indexOffer,indexProduct)" 
					class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent"
				><i class="fas fa-cloud-upload-alt"></i> &nbsp; залить фото</button>
				<hr>
				<div>
					<div>Заполнить товар по ссылке:</div>
					<input class="intec-ui intec-ui-control-input" type="text" :name="'link' + indexOffer + '' + indexProduct" placeholder="Вставьте ссылку">
					<button 
						title="Получить товар по ссылке" 
						class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent" 
						@click="fillByLink(indexOffer, indexProduct)" 
					>
						<i class="fas fa-link"></i>&nbsp;
					</button>
				</div>
				
			</td>
			<td class="kp-offers__inputs kp-offers__products-td">
				<div class="intec-grid intec-grid-i-10">
					<div class="intec-grid-item">
						<label>
							<div>Название:</div>
							<textarea
								class="intec-ui intec-ui-control-input"
								v-model="product.name"
							></textarea>
						</label>
						<label>
							<div>Описание:</div>
							<textarea
								class="intec-ui intec-ui-control-input"
								v-model="product.description"
							></textarea>
						</label>
						
					</div>
					<div class="intec-grid-item">
						<label>
							<div>Артикул:</div>
							<input type="text"
								class="intec-ui intec-ui-control-input"
								v-model="product.article">
						</label>
						<label>
							<div>Цвет:</div>
							<input type="text"
								class="intec-ui intec-ui-control-input"
								v-model="product.color">
						</label>
						<label>
							<div>Материал:</div>
							<input type="text"
								class="intec-ui intec-ui-control-input"
								v-model="product.material">
						</label>
						<label>
							<div>Размер:</div>
							<input type="text"
								class="intec-ui intec-ui-control-input"
								v-model="product.size">
						</label>
						<label>
							<div>Тираж:</div>
							<input type="text"
								class="intec-ui intec-ui-control-input"
								v-model="product.edition">
						</label>
						<label>
							<div>Цена за шт. руб:</div>
							<input type="text"
								class="intec-ui intec-ui-control-input"
								v-model="product.price">
						</label>
					</div>
				</div>				
			</td>
			<td class="kp-offers__products-td kp-offers__products-td--drawing">
				<?php include(__DIR__.'/drawing.php');?>				
			</td>
			<td class="kp-offers__products-td">
				<div>
					<b>Стоимость:</b> <span>{{product.edition*product.price}}</span> руб.
				</div>
				<div>
					<b>Нанесение:</b> {{getPriceDrawing(product)}}
				</div>
				<div>
					<b>Итого:</b> <span>{{product.edition*product.price + getPriceDrawingValue(product)}}</span> руб.
				</div>
				<hr>
				<button 
					class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent" 
					@click="deleteProduct(indexOffer, product, indexProduct)" 
				>
					<i class="fal fa-trash-alt"></i> &nbsp;
					удалить товар
				</button>
			</td>
		</tr>
		<tr>
			<td colspan="5" class="kp-offers__products-td">				
				<button class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent" @click="addProduct(offer)">добавить товар</button>
			</td>
		</tr>
	</tbody>
</table>