<script>
	const KP = {
	   data() {
		  return {
			  offers: [],
		  };
		},
		methods: {		
			
			// добавить коммерческое предложение
			addOffer() {
				this.offers.push({
					name: "Коммерческое предложение",
					productsVisible: true,
					id: null,
					products: [
						
					],
					contacts: {
						nameCompany: "",
						fioManager: "",
						phoneManager: "",
						emailManager: "",
						date: "",
						organisationClient: "",
						nameClient: "",
						logo: ""
					}

				});
			},
			
			// добавить товар
			addProduct(offer)  {
				offer.products.push({
					name: "",
					description: "",
					article: "",
					color: "",
					material: "",
					size: "",
					image: null,
					drawing: [
						{name: '', price: null}
					]
				});
			},
			
			// сохранить коммерческое предложение
			saveOffer(indexOffer) {				
				var formData = new FormData();
				for ( key in this.offers[indexOffer] ) {
					if (key == "visible") 
						continue;
					if (key == "products" || key == "contacts") {
						formData.append(key, JSON.stringify(this.offers[indexOffer][key]));
						continue;
					}
					formData.append(key, this.offers[indexOffer][key]);
				}
				formData.append('action', 'save');
				fetch("<?=$templateFolder."/ajax.php"?>", {
					method: "POST",
					body: formData
				})
				.then(response => response.text())
				.then(response => {
					if (response != "error") {
						this.offers[indexOffer].id = response;
						alert("предложение сохранено");						
					} else {
						alert("не удалось сохранить");
					}
				})
			},
			
			// удалить коммерческое предложение
			deleteOffer(offer) {
				if (confirm("Вы уверены, что хотите удалить коммерческое предложение?")) {					
					if (offer.id) {
						var formData = new FormData();
						formData.append("action", 'delete_offer');
						formData.append("id", offer.id);
						
						fetch("<?=$templateFolder."/ajax.php"?>", {
							method: "POST",
							body: formData
						})
						.then(response => response.text())
						.then(response => {
							this.offers = this.offers.filter((o) => o != offer);
						});
					}
				}
			},
			
			// удалить товар
			deleteProduct(indexOffer, product, indexProduct) {
				if (confirm("Вы уверены, что хотите удалить товар?")) {
					var formData = new FormData();
					let idOffer = this.offers[indexOffer].id;
					if (idOffer) {
						formData.append("id_offer", idOffer);
						formData.append("index_product", indexProduct);
						formData.append("action", "delete_product");
						fetch("<?=$templateFolder."/ajax.php"?>", {
							method: "POST",
							body: formData
						});
						alert("товар удален");
					}
					this.offers[indexOffer].products = this.offers[indexOffer].products.filter((p) => p != product);
				}
			},
			
			// удалить нанесение
			deleteDrawing(indexOffer, indexProduct, drawing) {
				if (confirm("Вы уверены, что хотите удалить нанесение?")) {
					this.offers[indexOffer].products[indexProduct].drawing =this.offers[indexOffer].products[indexProduct].drawing.filter((d) => d != drawing);
				}
			},
			
			// получить форматированную цену нанесения
			getPriceDrawing(product) {
				let resultPrice = 0;
				if (product.drawing) {
					for(let i = 0; i < product.drawing.length; i++ ) {
						resultPrice += +product.drawing[i].price*product.edition;
					}
				}
				return resultPrice+" руб.";
			},
			
			// получить цену нанесения
			getPriceDrawingValue(product) {
				let resultPrice = 0;
				if (product.drawing) {
					for(let i = 0; i < product.drawing.length; i++ ) {
						resultPrice += +product.drawing[i].price*product.edition;
					}
				}
				return resultPrice;
			},
			
			// получить коммерческое предложение с excel файла
			importOffer() {
				var fileInput = document.getElementById("excelFile");
				var selectedFile = fileInput.files[0];

				var formData = new FormData();
				formData.append("file", selectedFile);
				formData.append("action", "import_from_file");

				
				fetch("<?=$templateFolder."/ajax.php"?>", {
					method: "POST",
					body: formData
				})
				.then(response => response.text())
				.then(response => {
					if (response != "error") {
						let json = JSON.parse(response);
						this.offers.push({
							name: "Коммерческое предложение",
							products: json.products,
							id: json.id,
							contacts: {
								nameCompany: "",
								fioManager: "",
								phoneManager: "",
								date: "",
								organisationClient: "",
								nameClient: "",
							},
							id: null,
							visible: true
						});
						fileInput.value = '';
						alert("предложение добавлено");
					} else {
						alert("не удалось импортировать");
					}
				})
			},
			
			// добавить нанесение
			addDrawing(product) {
				product.drawing.push({
					name:"",
					price:""
				});
			},
			
			// загрузить изображение
			uploadImage(indexOffer, indexProduct) {
				if (!this.offers[indexOffer].id) {
					alert("перед загрукой изображения нужно сохранить коммерческое предожение");
				} else {
					var fileInput = document.getElementById("upload_image" + indexOffer + indexProduct);
					var selectedFile = fileInput.files[0];
					
					var formData = new FormData();
					let offer = this.offers[indexOffer];
					formData.append("file", selectedFile);
					if (this.offers[indexOffer].products[indexProduct].image) {
						formData.append("action", "replace_image");
					} else {
						formData.append("action", "upload_image");
					}
					formData.append("idOffer", offer.id);
					formData.append("indexProduct", indexProduct);
					fetch("<?=$templateFolder."/ajax.php"?>", {
						method: "POST",
						body: formData
					})
					.then(response => response.text())
					.then(response => {
						if (response != "error") {							
							this.offers[indexOffer].contacts.logo = response;
							alert("логотип обновлен");
						} else {
							alert("не удалось загрузить");
						}
					});					
				}
			},
			
			uploadLogo(indexOffer) {
				if (!this.offers[indexOffer].id) {
					alert("перед загрукой изображения нужно сохранить коммерческое предожение");
				} else {
					var fileInput = document.getElementById("upload_logo" + indexOffer);
					var selectedFile = fileInput.files[0];
					
					var formData = new FormData();
					let offer = this.offers[indexOffer];
					formData.append("file", selectedFile);
					if (this.offers[indexOffer].contacts.logo) {
						formData.append("action", "replace_logo");
					} else {
						formData.append("action", "upload_logo");
					}
					formData.append("idOffer", offer.id);
					fetch("<?=$templateFolder."/ajax.php"?>", {
						method: "POST",
						body: formData
					})
					.then(response => response.text())
					.then(response => {
						if (response != "error") {							
							this.offers[indexOffer].contacts.logo = response;
							alert("логнотип добавлен");
						} else {
							alert("не удалось загрузить");
						}
					});					
				}
			},		
			
			// выгрузить в pdf
			savePdf(indexOffer) {
				var formData = new FormData();
				for ( key in this.offers[indexOffer] ) {
					if (key == "visible") 
						continue;
					if (key == "products" || key == "contacts") {
						formData.append(key, JSON.stringify(this.offers[indexOffer][key]));
						continue;
					}
					formData.append(key, this.offers[indexOffer][key]);
				}
				formData.append('action', 'save');
				fetch("<?=$templateFolder."/ajax.php"?>", {
					method: "POST",
					body: formData
				})
				.then(response => response.text())
				.then(response => {
					if (response != "error") {
						document.location.href = "<?=$templateFolder?>/ajax.php?action=create_pdf&id=" + this.offers[indexOffer].id;				
					} else {
						alert("не удалось создать документ");
					}
				})
				
			},
			
			// заполнить товар по ссылке
			fillByLink(indexOffer, indexProduct) {
				let url = $("[name='link"+indexOffer + "" + indexProduct + "']").val();
				var formData = new FormData();
				formData.append("url", url);
				formData.append("action", "parse_url");
				fetch("<?=$templateFolder."/ajax.php"?>", {
					method: "POST",
					body: formData
				})
				.then(response => response.text())
				.then(response => {
					if (response != "error") {
						this.offers[indexOffer].products[indexProduct] = JSON.parse(response);
						alert("Товар заполнен информацией");
					} else {
						alert("не удалось загрузить");
					}
				});		
			}
		},
		
		mounted() {
			// заполнить коммерческие предложения с инфоблока, если существуют
			<?php 
			if ($arResult["ITEMS"]) {
				foreach ($arResult["ITEMS"] as $arItem) {
					$idOffer = false;
					if (isset($_GET["id"])) {
						$idOffer = $_GET["id"];
					}
					$arContacts = json_decode($arItem["PROPERTIES"]["CONTACTS"]["~VALUE"]["TEXT"],true);
					?>					
					this.offers.push({
						name: "<?=$arItem["NAME"]?> <?=$arContacts["organisationClient"]?"для ".$arContacts["organisationClient"]: "";?> <?=date("d.m.Y", strtotime($arItem["TIMESTAMP_X"]));?>",
						productsVisible: true,
						id: <?=$arItem["ID"]?>,
						products: <?=$arItem["PROPERTIES"]["DATA"]["~VALUE"]["TEXT"]?>,
						contacts: <?=$arItem["PROPERTIES"]["CONTACTS"]["~VALUE"]["TEXT"]?>,
						visible: <?=$arItem["ID"] == $idOffer ? "true" : "false";?>
					});
				<?}					
			}?>
		}
	}
	const app = Vue.createApp(KP);
	app.mount("#app-kp");
</script>