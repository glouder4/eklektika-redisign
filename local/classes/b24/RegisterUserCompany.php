<?php
namespace OnlineService\B24;
use intec\eklectika\advertising_agent\Company;
use OnlineService\B24\User;
use OnlineService\B24\Request;
class RegisterUserCompany extends Request{
    public function __construct()
    {
    }

    private function isUserRegistered($arFields){
        // найти пользователя в б24 по EMAIL
        $b24User = new \OnlineService\B24\User();

        if( isset($arFields['PERSONAL_PHONE']) && !isset($arFields['PHONE']) )
            $arFields['PHONE'] = $arFields['PERSONAL_PHONE'];

        $arResult = $b24User->getContactID($arFields,true);

        // если такой пользователь есть, то вывести предупреждение
        if (!empty($arResult) && isset($arResult['ID']) && $arResult['ID'] > 0) {
            return $arResult;
        }

        return false;
    }

    private function createB24Company($arFields){
        global $APPLICATION;

        // данные для контакта
        $dataContact = [
            'fields' => [
                'NAME' => $arFields['NAME'],
                'SECOND_NAME' => $arFields['SECOND_NAME'],
                'LAST_NAME' => $arFields['LAST_NAME'],
                'POST' => $arFields['WORK_POSITION'],
                'OPENED' => 'Y',
                'ASSIGNED_BY_ID' => 1,
                'PHONE' => [[
                    "VALUE" => $arFields['PERSONAL_PHONE'],
                    "VALUE_TYPE" => "WORK"
                ]],
                'EMAIL' => [ [
                    "VALUE" => $arFields['EMAIL'],
                    "VALUE_TYPE" => "WORK"
                ]]
            ],
            'params' => []
        ];

        // если это компания или рекламынй агент
        if ($arFields['UF_TYPE'] == '5' || $arFields['UF_TYPE'] == '6') {
            // проверить заполненность ИНН и названия компании
            if (empty($arFields['UF_INN']) && empty($arFields['UF_NAME_COMPANY'])) {
                $APPLICATION->ThrowException('Вы регистрируйтесь как рекламный агент или юридическое лицо. Поля "Название компании", "ИНН организации" обязательно для заполнения!');
                return false;
            } else {
                // если это рекламный агент
                if ($arFields['UF_ADVERSTERING_AGENT'] == 'on') {
                    $dataContact['fields']['UF_CRM_1701839165901'] = "Пользователь зарегистрировался как рекламный агент";
                }
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
                $dataRequisite = sendRequestB24("crm.requisite.list", $dataRequisite);

                if (!empty($dataRequisite)) {
                    $dataContact['fields']['COMPANY_ID'] = $dataRequisite[0]['ENTITY_ID'];
                    $companyId = $dataRequisite[0]['ENTITY_ID'];
                } else {
                    /*Создание компании*/
                    $qrCompanyInfo = [
                        'fields' => [
                            'TITLE' => $arFields['UF_NAME_COMPANY'],
                            'PHONE' => [[
                                'VALUE' => $arFields['PERSONAL_PHONE'],
                                'VALUE_TYPE' => "WORK"
                            ]],
                            'EMAIL' => [[
                                'VALUE' => $arFields['EMAIL'],
                                'VALUE_TYPE' => "WORK"
                            ]],
                            'WEB' => [[
                                'VALUE' => $arFields['UF_SITE'],
                                "VALUE_TYPE" => "WORK"
                            ]],
                            'UF_CRM_1669208000616' => $arFields['UF_SPERE'],
                            'UF_CRM_1669208295583' => $arFields['UF_JUR_ADDRESS'],
                            'UF_CRM_1618551330657' => $arFields['UF_CITY'],
                            'COMPANY_TYPE' => 'CUSTOMER'
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
                                'RQ_KPP' => $arFields['UF_KPP'],
                                'RQ_COMPANY_FULL_NAME' => $arFields['UF_NAME_COMPANY']
                            ]
                        );
                        sendRequestB24("crm.requisite.update", $qrRequisites);
                    }
                }

                // Ждем синхронизации данных с повторными попытками
                $maxAttempts = 5;
                $attempt = 0;
                $companySite = null;

                while ($attempt < $maxAttempts && $companySite === null) {
                    $companySite = Company::findByIdB24($dataCompany['ID']);
                    if ($companySite === null) {
                        $attempt++;
                        if ($attempt < $maxAttempts) {
                            sleep(2); // Ждем 2 секунды между попытками
                        }
                    }
                }
                $dataCompanyCreate = [
                    "NAME_COMPANY" => $arFields['UF_NAME_COMPANY'], // название компании
                    "INN" => $arFields['UF_INN'], // ИНН
                    "KPP" => $arFields['UF_KPP'], // КПП
                    "WEBSITE" => $arFields['UF_SITE'], // сайт
                    "SPHERE" => $arFields['UF_SPERE'], // сфера деятельности
                    "ADDRESS" => $arFields['UF_JUR_ADDRESS'], // адрес
                    "ID_B24" => $dataCompany['ID'],
                    "PHONE" => $arFields['PERSONAL_PHONE'],
                    "EMAIL" => $arFields['EMAIL'],
                    'UF_CRM_1618551330657' => "Город",
                ];

                if (!$companySite) {
                    Company::add($dataCompanyCreate);
                }

                /*if ($companySite) {
                    Company::update($companySite["ID"], $dataCompanyCreate);
                } else {
                    Company::add($dataCompanyCreate);
                }*/

            }
        }

        $contactId = sendRequestB24("crm.contact.add", $dataContact);

        if (!empty($companyId) && !empty($contactId)) {
            // добавить контакт в компанию
            $qrCompanyAddContact = [
                'fields' => ['COMPANY_ID' => $companyId],
                'id' => $contactId
            ];
            sendRequestB24("crm.contact.company.add", $qrCompanyAddContact);
        }
    }

    public function OnBeforeUserRegisterHandler(&$arFields) {
        global $APPLICATION;

        $arFields['ACTIVE'] = 'N';

        $response = $this->isUserRegistered($arFields);

        if( !$response ){
            if ($arFields['PASSWORD'] == $arFields['CONFIRM_PASSWORD']) {
                $createResult = $this->createB24Company($arFields);
                if ($createResult === false) {
                    // Если createB24Company вернул false, значит была ошибка
                    // Исключение уже было выброшено в createB24Company
                    return false;
                }
            }

            $arFields['UF_ADVERSTERING_AGENT'] = "";
            return $arFields;
        }
        else{
            // Определяем какое поле использовать для сообщения об ошибке
            if (isset($response['PHONE']) && !empty($response['PHONE'])) {
                $field = $arFields['PERSONAL_PHONE'];
            } else {
                $field = $arFields['EMAIL'];
            }

            $APPLICATION->ThrowException('Такой пользователь уже существует в системе. Вы можете авторизоваться или восстановить пароль для ' . $field,'already_registered');

            return false;
        }
    }

    public function OnAfterUserRegisterHandler(&$arFields) {
        // если регистрация успешна то
        /*if($arFields["USER_ID"]>0)
        {
            pre($arFields);

            $b24User = new \OnlineService\B24\User();
            $contactId = $b24User->getContactID($arFields['USER_ID']);


            pre($contactId);
            die();
        }*/
    }
    
    private function deleteStaffB24($arUser, $companyId, $idCompanySite) {
        $qrList = [
            'fields' => [],
            'params' => [],
            'select' => [],
            'filter' => ["EMAIL" => $arUser["EMAIL"]]
        ];
        $arResult = $this->sendB24Request("crm.contact.list", $qrList);

        if ($arResult['ID']) {
            // убрать рекламную агентность		
            sendRequestB24("crm.contact.update", [
                "id" => $arResult['ID'],
                "fields" => [
                    'UF_CRM_1698752707853' => ''
                ]
            ]);
            intec\eklectika\advertising_agent\Client::eraseStatusRA($arUser["ID"], $idCompanySite);

            // уволить его!		
            sendRequestB24("crm.contact.company.delete", [
                'id' => $arResult['ID'],
                'fields' => array('COMPANY_ID' => $companyId),
            ]);
            // прощай сотрудник, ты больше нам не нужен =(
        }
    }
}