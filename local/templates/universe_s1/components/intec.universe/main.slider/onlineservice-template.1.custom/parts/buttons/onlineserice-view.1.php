<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?php return function ($sUrl, $bUrlBlank, $sText) { ?>
    <div class="fullscreen-slider--slide-data--action">
        <a href="<?= $sUrl ?>" class="fullscreen-slider--slide-data--action_btn"><?= $sText ?></a>
    </div>
<?php } ?>
