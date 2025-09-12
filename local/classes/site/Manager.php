<?php
namespace OnlineService\Site;

class Manager{
    private int $iblock_id = 53;

    public function update($fields){
        $b24Id = $fields['ID'];
        
        if (empty($b24Id)) {
            echo "ОШИБКА: b24Id пустой! Нельзя искать элемент с пустым XML_ID<br>";
            return false;
        }

        $updatableFields = [
            'NAME' => $fields['NAME'].' '.$fields['LAST_NAME'],
            'PHONE' => $fields['PHONE'],
            'EMAIL' => $fields['EMAIL'],
            'WORK_POSITION' => $fields['POSITION'],
            'PERSONAL_PHOTO' => $fields['PERSONAL_PHOTO']
        ];
        
        // Подключаем модуль инфоблоков
        if (!\CModule::IncludeModule('iblock')) {
            return false;
        }
        
        // Ищем элемент по внешнему коду (XML_ID)
        $arFilter = [
            'IBLOCK_ID' => $this->iblock_id,
            'XML_ID' => $b24Id
        ];
        
        $rsElement = \CIBlockElement::GetList(
            ['SORT' => 'ASC'],
            $arFilter,
            false,
            false,
            ['ID', 'NAME', 'XML_ID', 'IBLOCK_ID']
        );
        
        if ($arElement = $rsElement->GetNext()) {
            // Дополнительная проверка IBLOCK_ID
            if ($arElement['IBLOCK_ID'] != $this->iblock_id) {
                echo "ОШИБКА: Найден элемент из другого инфоблока! Ожидался: {$this->iblock_id}, получен: {$arElement['IBLOCK_ID']}";
                return false;
            } 
            
            // Элемент найден, обновляем его
            $elementId = $arElement['ID'];
            
            // Обновляем основные поля элемента
            $el = new \CIBlockElement;
            $updateResult = $el->Update($elementId, [
                'NAME' => $updatableFields['NAME']
            ]);
            
            if ($updateResult) {
                // Обновляем свойства элемента
                \CIBlockElement::SetPropertyValues(
                    $elementId,
                    $this->iblock_id,
                    $updatableFields['PHONE'],
                    'PHONE'
                );
                
                \CIBlockElement::SetPropertyValues(
                    $elementId,
                    $this->iblock_id,
                    $updatableFields['EMAIL'],
                    'EMAIL'
                );
                
                \CIBlockElement::SetPropertyValues( 
                    $elementId,
                    $this->iblock_id,
                    $updatableFields['WORK_POSITION'],
                    'WORK_POSITION'
                );
                
                // Обновляем фото анонса (PREVIEW_PICTURE)
                if (!empty($updatableFields['PERSONAL_PHOTO'])) {
                    try {
                        $photoUrl = ltrim($updatableFields['PERSONAL_PHOTO'], '/');
                        
                        // Разбиваем путь по "/" и кодируем только имя файла (последний элемент)
                        $pathParts = explode('/', $photoUrl);
                        $fileName = array_pop($pathParts); // Получаем имя файла
                        $encodedFileName = rawurlencode($fileName); // Кодируем имя файла
                        $pathParts[] = $encodedFileName; // Возвращаем закодированное имя файла
                        $encodedPhotoUrl = URL_B24 . implode('/', $pathParts);
                        
                        // Загружаем файл с внешнего ресурса URL_B24 через HTTP-клиент
                        $httpClient = new \Bitrix\Main\Web\HttpClient();
                        $httpClient->setTimeout(30);
                        
                        // Создаем временный файл с оригинальным расширением
                        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
                        $tempFile = tempnam(sys_get_temp_dir(), 'photo_') . '.' . $fileExtension;
                        $downloadResult = $httpClient->download($encodedPhotoUrl, $tempFile);
                        
                        if ($downloadResult && file_exists($tempFile)) {
                            // Создаем массив файла из скачанного файла
                            $photoArray = \CFile::MakeFileArray($tempFile);
                            
                            if ($photoArray && !$photoArray['error']) {
                                $updatePhotoResult = $el->Update($elementId, [
                                    'PREVIEW_PICTURE' => $photoArray
                                ]);
                                
                                if (!$updatePhotoResult) {
                                    echo "ОШИБКА: Не удалось обновить фото элемента ID: {$elementId}. Ошибка: " . $el->LAST_ERROR . "<br>";
                                }
                            } else {
                                echo "ОШИБКА: Не удалось создать массив файла для фото. URL: {$encodedPhotoUrl}<br>";
                                if ($photoArray && $photoArray['error']) {
                                    echo "Код ошибки файла: " . $photoArray['error'] . "<br>";
                                }
                            }
                            
                            // Удаляем временный файл
                            unlink($tempFile);
                        } else {
                            echo "ОШИБКА: Не удалось скачать файл с URL: {$encodedPhotoUrl}<br>";
                            echo "HTTP код ответа: " . $httpClient->getStatus() . "<br>";
                            if (file_exists($tempFile)) {
                                unlink($tempFile);
                            }
                        }
                    } catch (Exception $e) {
                        echo "ОШИБКА при обработке фото: " . $e->getMessage() . "<br>";
                    }
                }
                return true; // Успешно обновлено
            }
            return false; // Ошибка обновления
        }
        
        // Элемент не найден
        return false;
    }
}