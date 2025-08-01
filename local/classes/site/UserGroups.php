<?php
    namespace OnlineService\Site;

    class UserGroups{
        private array $request;
        private ?int $group_id = null;
        public function __construct($request)
        {
            $this->request = $request;

            $this->GroupAction();
        }

        private function GroupAction(){
            if( $this->request['ACTION'] == "UPDATE_GROUP" ){
                $this->group_id = $this->searchGroup();

                if( !$this->group_id ){
                    $this->group_id = $this->createGroup();
                }
                else
                    $this->updateGroup();
            }
        }

        public function getGroupId(){
            return $this->group_id;
        }

        private function searchGroup(){

            $rsGroups = \CGroup::GetList($by = "c_sort", $order = "asc", array(
                'STRING_ID' => "GROUP_".$this->request['ID']
            )); // выбираем группы

            return $rsGroups->Fetch()['ID'] ?? false;
        }

        private function createGroup(){
            $group = new \CGroup;
            $arFields = Array(
                "ACTIVE"       => $this->request['ACTIVE'],
                "C_SORT"       => $this->request['C_SORT'],
                "NAME"         => $this->request['NAME'],
                "DESCRIPTION"  => "",
                "USER_ID"      => array(),
                "STRING_ID"      => "GROUP_".$this->request['ID']
            );
            $NEW_GROUP_ID = $group->Add($arFields);
            if (strlen($group->LAST_ERROR)>0) ShowError($group->LAST_ERROR);

            return $NEW_GROUP_ID;
        }

        private function updateGroup(){
            $group = new \CGroup;
            $arFields = Array(
                "ACTIVE"       => $this->request['ACTIVE'],
                "C_SORT"       => $this->request['C_SORT'],
                "NAME"         => $this->request['NAME'],
            );
            $group->Update($this->group_id, $arFields);
            if (strlen($group->LAST_ERROR)>0) ShowError($group->LAST_ERROR);
        }
    }