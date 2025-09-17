<?php
define("NO_KEEP_STATISTIC", true);
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

global $USER;
global $APPLICATION;

function createB24Company($arFields){
    global $APPLICATION;

    if( !empty($_FILES['UF_REQ']) && !empty($_FILES['UF_REQ']['name']) ){
        $file = $_FILES['UF_REQ'];

        // Сохраняем в систему Битрикс
        $savedFileId = \CFile::SaveFile($file, 'os_requisites');

        if ($file['error'] === 0) {
            $fileName = $file['name'];
            $filePath = $file['tmp_name'];

            // Читаем содержимое файла
            $fileContent = file_get_contents($filePath);

            if ($fileContent !== false) {
                // Кодируем в base64
                $fileData = [
                    $fileName,
                    base64_encode($fileContent),
                ];

                // Передаём в поле Bitrix24
                $arFields['UF_CRM_1755643990423'] = [
                    'fileData' => $fileData
                ];
            }
        }
        else{
            // Вывести подробную ошибку
            $errorMessage = 'Ошибка загрузки файла реквизитов: ';
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $errorMessage .= 'Размер файла превышает максимально допустимый размер, указанный в php.ini.';
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $errorMessage .= 'Размер файла превышает максимально допустимый размер, указанный в форме.';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $errorMessage .= 'Файл был загружен только частично.';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $errorMessage .= 'Файл не был загружен.';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $errorMessage .= 'Отсутствует временная папка для загрузки файла.';
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $errorMessage .= 'Не удалось записать файл на диск.';
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $errorMessage .= 'Загрузка файла была остановлена расширением PHP.';
                    break;
                default:
                    $errorMessage .= 'Неизвестная ошибка (код: ' . $file['error'] . ').';
                    break;
            }
            $APPLICATION->ThrowException($errorMessage);
            return false;
        }
    }

    // если это компания или рекламынй агент
    if ($arFields['UF_TYPE'] == '5' || $arFields['UF_TYPE'] == '6') {
        // проверить заполненность ИНН и названия компании
        if (empty($arFields['UF_INN']) && empty($arFields['UF_NAME_COMPANY'])) {
            echo 'Поля "Название компании", "ИНН организации" обязательно для заполнения!';
            $APPLICATION->ThrowException('Поля "Название компании", "ИНН организации" обязательно для заполнения!');
            return false;
        } else {
            $dataRequisite = [
                'fields' => [],
                'params' => [],
                'select' => [
                    'ID',
                    'RQ_INN',
                    'ENTITY_ID'
                ],
                'filter' => [
                    'RQ_INN' => $arFields['UF_INN']
                ]
            ];
            // найти реквизит по ИНН
            $dataRequisite = sendRequestB24("crm.requisite.list", $dataRequisite,false);

            if (empty($dataRequisite)) {
                /*Создание компании*/
                $qrCompanyInfo = [
                    'fields' => [
                        'TITLE' => $arFields['UF_NAME_COMPANY'],
                        'WEB' => [[
                            'VALUE' => $arFields['UF_SITE'],
                            "VALUE_TYPE" => "WORK"
                        ]],
                        'UF_CRM_1618551330657' => $arFields['UF_CITY'],
                        'UF_CRM_1755643990423' => $arFields['UF_CRM_1755643990423'],
                        'UF_CRM_1758028816' => $arFields['head_company_id'],
                        'COMPANY_TYPE' => 'CUSTOMER',
                        'ASSIGNED_BY_ID' => 3036,
                    ]
                ];

                $companyId = sendRequestB24("crm.company.add", $qrCompanyInfo);

                if (!empty($companyId)) {
                    $qrCompany['id'] = $companyId;
                    $dataCompany = sendRequestB24("crm.company.get", $qrCompany);

                    /*Добавление реквизита к компании*/
                    $qrRequisite = [
                        'fields' => [
                            'ENTITY_ID' => $dataCompany['ID'],
                            'ENTITY_TYPE_ID' => '4',
                            'NAME' => 'Реквизит с формы сайта',
                            'PRESET_ID' => 1
                        ]
                    ];

                    $requisiteId = sendRequestB24("crm.requisite.add", $qrRequisite);

                    /*Обновление реквизитов у компании*/
                    $qrRequisites = array(
                        'id' => $requisiteId,
                        'fields' => [
                            'ENTITY_ID' => $dataCompany['ENTITY_ID'],
                            'ENTITY_TYPE_ID' => '4',
                            'RQ_INN' => $arFields['UF_INN'],
                            'RQ_COMPANY_FULL_NAME' => $arFields['UF_NAME_COMPANY']
                        ]
                    );
                    sendRequestB24("crm.requisite.update", $qrRequisites);

                    $companyElementParamss = [
                        'OS_COMPANY_INN' => $arFields['UF_INN'],
                        'OS_COMPANY_WEB_SITE' => $arFields['UF_SITE'],
                        'OS_COMPANY_NAME' => $arFields['UF_NAME_COMPANY'],
                        'OS_COMPANY_B24_ID' => $dataCompany['ID'],
                        'OS_COMPANY_CITY' => $arFields['UF_CITY'],
                        'OS_REQUSITES_FILE' => $arFields['UF_CRM_1755643990423']
                    ];

                    $company = new \OnlineService\Site\Company();
                    $company->createCompanyElement($companyElementParamss);

                    echo 'Компания успешно создана';

                    return true;
                }
            } else {
                echo 'Компания с указанным ИНН уже существует!';
                $APPLICATION->ThrowException('Компания с указанным ИНН уже существует!');
                return false;
            }
        }
    }

    return false;
}

$userId = $USER->GetID();
if( $USER->IsAuthorized() ){
    $arResult['HEAD_COMPANY_ID'] = false;

// Получаем компанию пользователя
    $rsCompany = CIBlockElement::GetList(
        [],
        [
            'IBLOCK_ID' => 57,
            'PROPERTY_OS_COMPANY_BOSS' => $USER->GetID(),
            'PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING' => 31520,
            'ACTIVE' => 'Y'
        ],
        false,
        false,
        ['ID', 'PROPERTY_OS_COMPANY_IS_HEAD_OF_HOLDING', 'PROPERTY_OS_HOLDING_OF','PROPERTY_OS_COMPANY_B24_ID','PROPERTY_OS_HEAD_COMPANY_B24_ID']
    );
    if( $headCompany = $rsCompany->GetNext() ){
        if( $_POST['head_company_id'] != $headCompany['PROPERTY_OS_HEAD_COMPANY_B24_ID_VALUE'] ){
            echo "Ошибка в идентификации руководителя!";
            $APPLICATION->ThrowException("Ошибка в идентификации руководителя!");
            return false;
        }

        createB24Company($_POST);
    }
    else{
        echo "Вы не являетесь руководителем компании";
        $APPLICATION->ThrowException("Вы не являетесь руководителем компании");
        return false;
    }
}
else{
    echo "Вам запрещено выполнять это действие.";
    $APPLICATION->ThrowException("Вам запрещено выполнять это действие.");
    return false;
}