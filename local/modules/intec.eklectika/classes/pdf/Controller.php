<?php
namespace intec\eklectika\pdf;
use Dompdf\Dompdf;


class Controller {

    public static function getPdf($html) {
        require_once __DIR__."/vendor/autoload.php";
	$font = 'DejaVu Serif';;
        $dompdf = new Dompdf(['defaultFont' => $font]);
        $dompdf->loadHtml($html);
	$dompdf->setPaper('A4', '');
	$dompdf->render();
	$dompdf->stream();
    }
}
?>