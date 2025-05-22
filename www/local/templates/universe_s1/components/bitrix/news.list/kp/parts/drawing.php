<table class="kp-offers__drawing">
	<thead>
		<tr>
			<td>Вид нанесения</td>
			<td>Цена</td>
			<td>Итого</td>
			<td></td>
		</tr>
	</thead>
	<tbody>
		<tr :key="indexDrawing" v-for="(drawing, indexDrawing) in product.drawing">
			<td>
				<input type="text" class="intec-ui intec-ui-control-input kp-offers__drawing-name" v-model="drawing.name">
			</td>
			<td>
				<input type="text" class="intec-ui intec-ui-control-input kp-offers__drawing-price" v-model="drawing.price">
			</td>
			<td style="vertical-align:middle">
				{{drawing.price * product.edition}}
			</td>
			<td>
				<button 
					v-if="product.drawing.length > 1"
					@click="deleteDrawing(indexOffer, indexProduct, drawing)"
					class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent"
				><i class="fal fa-trash-alt"></i></button>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<button 
					@click="addDrawing(product)"
					class="kp-offers__button intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-mod-transparent"
				>+ нанесение</button>
			</td>
		</tr>
	</tbody>
</table>