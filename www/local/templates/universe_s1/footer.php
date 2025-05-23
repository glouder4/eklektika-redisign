<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\FileHelper;

global $APPLICATION;
global $USER;
global $directory;
global $properties;
global $template;
global $part;

if (empty($template))
    return;

?>
        <?php include($directory.'/onlineservice_addons/footer.php'); ?>
<div style="display: none!important;">
        <?php include($directory.'/parts/'.$part.'/footer.php'); ?>
</div>

        <?php if (FileHelper::isFile($directory.'/parts/custom/body.end.php')) include($directory.'/parts/custom/body.end.php') ?>
		<script>
		   (function(w,d,u){
				   var s=d.createElement('script');s.async=true;s.src=u+'?'+(Date.now()/60000|0);
				   var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
		   })(window,document,'https://testb24.yoliba.ru/upload/crm/site_button/loader_6_22nko2.js');
		</script>
    </body>
</html>
<?php if (FileHelper::isFile($directory.'/parts/custom/end.php')) include($directory.'/parts/custom/end.php') ?>