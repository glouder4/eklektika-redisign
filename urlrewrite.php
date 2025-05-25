<?php
$arUrlRewrite=array (
  2 => 
  array (
    'CONDITION' => '#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1',
    'ID' => NULL,
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/video([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1&videoconf',
    'ID' => NULL,
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^\\/?\\/mobileapp/jn\\/(.*)\\/.*#',
    'RULE' => 'componentName=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobileapp/jn.php',
    'SORT' => 100,
  ),
  6 => 
  array (
    'CONDITION' => '#^/bitrix/services/ymarket/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/bitrix/services/ymarket/index.php',
    'SORT' => 100,
  ),
  16 => 
  array (
    'CONDITION' => '#^/company/certificates/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/company/certificates/index.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^/online/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  0 => 
  array (
    'CONDITION' => '#^/stssync/calendar/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/calendar/index.php',
    'SORT' => 100,
  ),
  13 => 
  array (
    'CONDITION' => '#^/company/articles/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/company/articles/index.php',
    'SORT' => 100,
  ),
  48 => 
  array (
    'CONDITION' => '#^/personal/profile/#',
    'RULE' => '',
    'ID' => 'bitrix:sale.personal.section',
    'PATH' => '/personal/profile/index.php',
    'SORT' => 100,
  ),
  23 => 
  array (
    'CONDITION' => '#^/contacts/stores/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/contacts/stores/index.php',
    'SORT' => 100,
  ),
  40 => 
  array (
    'CONDITION' => '#^/company/staff/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/company/staff/index.php',
    'SORT' => 100,
  ),
  39 => 
  array (
    'CONDITION' => '#^/company/jobs/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/company/jobs/index.php',
    'SORT' => 100,
  ),
  12 => 
  array (
    'CONDITION' => '#^/company/news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/company/news/index.php',
    'SORT' => 100,
  ),
  22 => 
  array (
    'CONDITION' => '#^/help/client/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/help/client/index.php',
    'SORT' => 100,
  ),
  38 => 
  array (
    'CONDITION' => '#^/help/brands/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/help/brands/index.php',
    'SORT' => 100,
  ),
  8 => 
  array (
    'CONDITION' => '#^/collections/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/collections/index.php',
    'SORT' => 100,
  ),
  46 => 
  array (
    'CONDITION' => '#^/agent_news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/agent_news/index.php',
    'SORT' => 100,
  ),
  37 => 
  array (
    'CONDITION' => '#^/services/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/services/index.php',
    'SORT' => 100,
  ),
  36 => 
  array (
    'CONDITION' => '#^/projects/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/projects/index.php',
    'SORT' => 100,
  ),
  9 => 
  array (
    'CONDITION' => '#^/imagery/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/imagery/index.php',
    'SORT' => 100,
  ),
  47 => 
  array (
    'CONDITION' => '#^/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/catalog/index.php',
    'SORT' => 100,
  ),
  49 => 
  array (
    'CONDITION' => '#^/loyalty/#',
    'RULE' => NULL,
    'ID' => 'skyweb24:loyaltyprogram',
    'PATH' => '/loyalty/index.php',
    'SORT' => 100,
  ),
  25 => 
  array (
    'CONDITION' => '#^/shares/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/shares/index.php',
    'SORT' => 100,
  ),
  20 => 
  array (
    'CONDITION' => '#^/photo/#',
    'RULE' => '',
    'ID' => 'bitrix:photo',
    'PATH' => '/photo/index.php',
    'SORT' => 100,
  ),
  5 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 100,
  ),
  17 => 
  array (
    'CONDITION' => '#^/blog/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/blog/index.php',
    'SORT' => 100,
  ),
);
