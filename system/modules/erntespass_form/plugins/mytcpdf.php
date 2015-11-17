<?php

/**
 * Created by PhpStorm.
 * User: Marko
 * Date: 05.11.2015
 * Time: 23:02
 */
class MYTCPDF extends \TCPDF
{

    //Page header

    public function Header()
    {
        // Logo
        $image_file = TL_ROOT . '/system/modules/erntespass_form/assets/logo.png';
        $this->Image($image_file, 125, 13, 60, '', '', 'http://erntespass.de', '', false, 300);
    }

    // Page footer
    public function Footer()
    {
        // create some HTML content
        $objFile = new \File('system/modules/erntespass_form/templates/pdf_4.html5');
        $html = $objFile->getContent();
        //$html = $this->replaceTags($html);

        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        $this->writeHTML(utf8_encode($html), true, false, true, false, '');

        //$this->Cell(0, 10, utf8_encode($html), 0, false, 'C', 0, '', 0, false, 'T', 'M');

        /**
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0,
            false, 'T', 'M');
         **/
    }
}