<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
    use OnlineService\Site\UserGroups;

    if( $_REQUEST['ACTION'] == "UPDATE_GROUP" ){
        $group = new UserGroups($_REQUEST);
        echo $group->getGroupId();
    }