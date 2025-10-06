<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\IO\File;
use Bitrix\Main\FileTable;


?>
<div class="catalog-element-documents-item-container">
    <div class="intec-grid intec-grid-wrap intec-grid-a-v-stretch">
		<ul>
        <?php foreach ($arResult['PROPERTIES']['PROD_LIST_DOCS']['VALUE'] as $fileId) {
            // Получаем информацию о файле (используем FileTable для совместимости)
            $arFile = FileTable::getById($fileId)->fetch();
            if ($arFile) {
                $originalName = $arFile['ORIGINAL_NAME'] ?: basename($arFile['FILE_NAME']);
                
                // Извлекаем имя без расширения и расширение для отображения
                $customName = pathinfo($originalName, PATHINFO_FILENAME);
                $fileExtension = pathinfo($originalName, PATHINFO_EXTENSION);
                
                // Ссылка на handler-скрипт с file_id
                $downloadUrl = '/download_handler.php?file_id=' . $fileId; // Путь к вашему скрипту
        ?>
                <li><a href="<?= htmlspecialchars($downloadUrl) ?>" class="prod_list_docs_item" download>
                    Скачать <?= htmlspecialchars($customName) ?> в формате <?= htmlspecialchars($fileExtension) ?>
					</a></li>
        <?php 
            }
        } 
        ?>
		</ul>
    </div>
</div>
<style>
	.prod_list_docs_item {
		    display: inline-block;
    font-size: 14px;
    line-height: 21px;
    text-decoration: none;
    color: #000;
    overflow: hidden;
    cursor: pointer;
    -webkit-transition-duration: 0.4s;
    -moz-transition-duration: 0.4s;
    -ms-transition-duration: 0.4s;
    -o-transition-duration: 0.4s;
    transition-duration: 0.4s;
    -webkit-transition-property: color;
    -moz-transition-property: color;
    -ms-transition-property: color;
    -o-transition-property: color;
    transition-property: color;
		width:100%;
}
	.prod_list_docs_item:hover {
		color: #352ca6 !important;
		}
.prod_list_docs_item:focus {
		color: #352ca6 !important;
		}
.prod_list_docs_item:active {
		color: #352ca6 !important;
		}
</style>