<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
    use OnlineService\Site\UserGroups;
    use OnlineService\B24\User;

    if( $_REQUEST['ACTION'] == "UPDATE_GROUP" ){
        $group = new UserGroups($_REQUEST);
        echo $group->getGroupId();
    }

    if( $_REQUEST['ACTION'] == "UPDATE_CONTACT" ){
        $user = new User($_REQUEST);
        echo $user->update($_REQUEST);
    }