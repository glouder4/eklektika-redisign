<?php
namespace OnlineService\Site;

class Manager{
    private int $iblock_id = 53;

    public function update($fields){
        $b24Id = $fields['ID'];
        
        echo "Полученные поля: ";
        pre($fields);
        echo "b24Id = " . $b24Id . " (тип: " . gettype($b24Id) . ")<br>";
        
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
                    $photoUrl = ltrim($updatableFields['PERSONAL_PHOTO'], '/');
                    $photoArray = \CFile::MakeFileArray(URL_B24.$photoUrl);
                    if ($photoArray) {
                        $el->Update($elementId, [
                            'PREVIEW_PICTURE' => $photoArray
                        ]);
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