<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
    use OnlineService\Site\UserGroups;
    use OnlineService\B24\UserSync\ContactAjaxFacade;

    if( $_REQUEST['ACTION'] == "UPDATE_GROUP" ){
        $group = new UserGroups($_REQUEST);
        echo $group->getGroupId();
    }

    if( $_REQUEST['ACTION'] == "UPDATE_CONTACT" ){
        echo ContactAjaxFacade::updateContact($_REQUEST);
    }

    if( $_REQUEST['ACTION'] == "UPDATE_BATCH_USERS" ){
        echo ContactAjaxFacade::updateBatchUsers($_REQUEST);
    }
    if( $_REQUEST['ACTION'] == "DELETE_CONTACT" ){
        echo ContactAjaxFacade::deleteContact($_REQUEST);
    }

    if( $_REQUEST['ACTION'] == "DELETE_COMPANY" ){
        $company = new \OnlineService\Site\Company();
        echo $company->deleteCompanyElement($_REQUEST);
    }

    if( $_REQUEST['ACTION'] == "UPDATE_COMPANY" ){
        $company = new \OnlineService\Site\Company();
        echo $company->updateCompanyElement($_REQUEST);
    }

    if( $_REQUEST['ACTION'] == "UPDATE_MANAGER" ){
        $manager = new \OnlineService\Site\Manager();
        echo $manager->update($_REQUEST);
    }

    if( $_REQUEST['ACTION'] == "SYNC_COMPANY_CONTACTS" ){
        $company = new \OnlineService\Site\Company();
        echo $company->syncCompanyContacts($_REQUEST);
    }