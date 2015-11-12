<?php

/**
 * Contao Open Source CMS
 * Copyright (c) 2005-2014 Leo Feyer
 * @package BUF (Beurteilen und Fördern)
 * @author Marko Cupic m.cupic@gmx.ch, 2014
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace MCupic;


/**
 * Class PDFController
 * @package MCupic
 */
class PDFController extends \System
{

    /**
     * @var $objMainController
     */
    protected $arrFields;

    /**
     * @var
     */
    protected $rechnungsnummer;

    /**
     * @var
     */
    protected $filename;

    /**
     *
     */
    public function __construct()
    {
        // register fpdf classes
        \ClassLoader::addClasses(array(
            'TCPDF'   => 'vendor/tecnickcom/tcpdf/tcpdf.php',
            'MYTCPDF' => 'system/modules/erntespass_form/plugins/mytcpdf.php',
        ));

        return parent::__construct();
    }

    /**
     * @param $arrFields
     * @param $rechnungsnummer
     * @param $filename
     * @param bool|false $sheetForAdmin
     */
    public function printSheet($arrFields, $rechnungsnummer, $filename, $sheetForAdmin = false)
    {

        $arrKeys = array(
            "anrede",
            "vorname",
            "nachname",
            "strasse",
            "postleitzahl",
            "ort",
            "telefon",
            "email",
            "anfang_geschenkadresse",
            "anrede_geschenkadresse",
            "vorname_geschenkadresse",
            "nachname_geschenkadresse",
            "strasse_geschenkadresse",
            "postleitzahl_geschenkadresse",
            "ort_geschenkadresse",
            "telefon_geschenkadresse",
            "e-mail_geschenkadresse",
            "datum_kontakt_erlaubt_geschenkadresse",
            "gartengroesse",
            "gartenname",
            "anzahl_schutznetze",
            "anmerkung",
            "zahlungsart",
            "agb",
            "widerrufsbelehrung",
        );

        $a = array();
        foreach ($arrKeys as $k) {
            if($arrFields[$k] != '')
            {
                $a[$k] = htmlentities($arrFields[$k]);
            }

        }
        $this->arrFields = $a;

        $this->rechnungsnummer = $rechnungsnummer;
        $this->filename = $filename;


        // create new PDF document
        $pdf = new \MYTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Jeanette Lagall - Erntespass - Brahmsstr. 3 - 56179 Vallendar');
        $pdf->SetTitle('Bestellbestätigung Erntespass (Jeanette Lagall)');
        $pdf->SetSubject('Ihre Bestellbestätigung (http:/erntespass.de)');
        $pdf->SetKeywords('Bestellbestätigung Erntespass Jeanette Lagall Vallendar');

        // set default header data
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 006', PDF_HEADER_STRING);

        // set header and footer fonts
        //$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

        // remove header bottom line
        $pdf->setHeaderData('', 0, '', '', array(0, 0, 0), array(255, 255, 255));

        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


        // set font
        $pdf->SetFont('helvetica', '', 9);

        // add a page
        $pdf->AddPage();


        $pdf->Image(TL_ROOT . '/system/modules/erntespass_form/assets/logo.jpg', 125, 13, 60, 25, 'JPG',
            'http://erntespass.de', '', true, 150, '', false, false, 0, false, false, false);

        // create some HTML content
        $objFile = new \File('system/modules/erntespass_form/templates/pdf_1.html5');
        $html = $objFile->getContent();
        $html = $this->replaceTags($html);
        $pdf->writeHTML(utf8_encode($html), true, false, true, false, '');


        // create main table
        $objFile = new \File('system/modules/erntespass_form/templates/pdf_2.html5');
        $html = $objFile->getContent();
        $html = $this->replaceTags($html);
        $pdf->writeHTML(utf8_encode($html), true, false, true, false, '');


        // create some HTML content
        $objFile = new \File('system/modules/erntespass_form/templates/pdf_3.html5');
        $html = $objFile->getContent();
        $html = $this->replaceTags($html);
        $pdf->writeHTML(utf8_encode($html), true, false, true, false, '');

        if ($sheetForAdmin) {
            // add a page
            $pdf->AddPage();


            $pdf->Image(TL_ROOT . '/system/modules/erntespass_form/assets/logo.jpg', 125, 13, 60, 25, 'JPG',
                'http://erntespass.de', '', true, 150, '', false, false, 0, false, false, false);

            $html = '<h1>Alle Daten auf einen Blick</h1>';
            $html .= '<table>';
            $objFile = new \File('system/modules/erntespass_form/templates/pdf_5.html5');
            $htmlRow = $objFile->getContent();

            foreach ($this->arrFields as $k => $v) {
                $label = $_SESSION['MY_FORM_DATA']['arrLabels'][$k] != '' ? $_SESSION['MY_FORM_DATA']['arrLabels'][$k] : $k;
                if(strpos($k, '_geschenkadresse') !== false){
                    if($this->arrFields['anfang_geschenkadresse'] == '')
                    {
                        continue;
                    }else{
                        if($k == 'anfang_geschenkadresse')
                        {
                            $label = 'Der Garten ist ein Geschenk:';
                            $v = 'Ja';
                        }
                    }
                }
                if($k == 'widerrufsbelehrung' && $v != ''){
                    $label = 'Widerrufsbelehrung:';
                    $v = 'Widerrufsbelehrung wurde vom Kunden eingesehen.';
                }
                if($k == 'gartengroesse' && $v != ''){
                    $label = 'Gartengrösse:';
                }


                if($k == 'agb' && $v != ''){
                    $label = 'AGB:';
                    $v = "Die AGB's wurden vom Kunden akzeptiert.";
                }

                $strRow = str_replace('##KEY##', htmlentities(html_entity_decode($label)), $htmlRow);

                $v = $v == '' ? '----' : $v;
                $strRow = str_replace('##VALUE##', $v, $strRow);
                $html .= $strRow;
            }
            $pdf->writeHTML(utf8_encode($html), true, false, true, false, '');
        }


        // reset pointer to the last page
        $pdf->lastPage();


        //Close and output PDF document
        $pdf->Output($filename, 'F');

    }

    /**
     * @param $string
     * @return mixed
     */
    public function replaceTags($string)
    {
        //##PRODUKT##
        if ($this->arrFields['gartengroesse'] == 'gross') {
            $string = str_replace('##PRODUKT##', '##GROSSER_GEMUESEGARTEN##', $string);
            $preisGarten = $GLOBALS['ERNTESPASS']['##PREIS_GROSSER_GEMUESEGARTEN##'];

        } else {
            $string = str_replace('##PRODUKT##', '##KLEINER_GEMUESEGARTEN##', $string);
            $preisGarten = $GLOBALS['ERNTESPASS']['##PREIS_KLEINER_GEMUESEGARTEN##'];
        }


        $nettoTotal = $this->floatRound(floatval($preisGarten * 100 / (100 + floatval($GLOBALS['ERNTESPASS']['##MWSTSATZ##']))));
        $string = str_replace('##PREISPRODUKT-NETTO##', $nettoTotal, $string);

        $string = str_replace('##NETTOBETRAG##', $nettoTotal, $string);
        $mwstSatz = floatval($GLOBALS['ERNTESPASS']['##MWSTSATZ##']);
        $mwst = $this->floatRound($nettoTotal / 100 * $mwstSatz);
        $bruttoTotal = $this->floatRound($preisGarten);
        $string = str_replace('##MWSTBETRAG##', $mwst, $string);
        $string = str_replace('##GESAMTBETRAG##', $bruttoTotal, $string);

        $textGeschenkFuer = '';
        //die(print_r($this->arrFields,true));

        if ($this->arrFields['anfang_geschenkadresse'] == '1') {

            $textGeschenkFuer .= '<br><br>Der Garten ist ein Geschenk f&uuml;r: <br>';
            $textGeschenkFuer .= $this->arrFields['vorname_geschenkadresse'] . ' ' . $this->arrFields['nachname_geschenkadresse'] . '<br>';
            $textGeschenkFuer .= $this->arrFields['strasse_geschenkadresse'] . '<br>';
            $textGeschenkFuer .= $this->arrFields['postleitzahl_geschenkadresse'] . ' ' . $this->arrFields['ort_geschenkadresse'] . '<br>';
            $textGeschenkFuer .= 'Datum Kontaktaufnahme: ' . $this->arrFields['datum_kontakt_erlaubt_geschenkadresse'] . '<br>';
        }
        $string = str_replace('##TEXT-GESCHENK-FUER##', utf8_encode($textGeschenkFuer), $string);

        // Schutznetze
        $string = str_replace('##SCHUTZNETZ##', $GLOBALS['ERNTESPASS']['##SCHUTZNETZ##'], $string);
        $schutznetzPreisEinzel = $GLOBALS['ERNTESPASS']['##PREIS_SCHUTZNETZ##'] / (100 + $mwstSatz) * 100;
        $string = str_replace('##SCHUTZNETZ-PREIS-EINZEL##', $this->floatRound($schutznetzPreisEinzel), $string);
        $schutznetzAnzahl = $this->arrFields['anzahl_schutznetze'];
        $string = str_replace('##SCHUTZNETZ-ANZAHL##', $schutznetzAnzahl, $string);
        $schutznetzPreisGesamt = $schutznetzPreisEinzel * $schutznetzAnzahl;
        $string = str_replace('##SCHUTZNETZ-PREIS-GESAMT##', $this->floatRound($schutznetzPreisGesamt), $string);


        $string = str_replace('##DATE##', \Date::parse('d.m.Y'), $string);
        $string = str_replace('##RECHNUNGSNUMMER##', $this->rechnungsnummer, $string);
        $string = str_replace('##ORT##', $this->arrFields['ort'], $string);


        $string = str_replace('##KUNDENVORNAME##', $this->arrFields['vorname'], $string);
        $string = str_replace('##KUNDENNAME##', $this->arrFields['nachname'], $string);
        $string = str_replace('##KUNDENSTRASSE##', $this->arrFields['strasse'], $string);
        $string = str_replace('##KUNDENPLZ##', $this->arrFields['postleitzahl'], $string);
        $string = str_replace('##KUNDENORT##', $this->arrFields['ort'], $string);


        // Weitere Ersetzungen
        foreach ($GLOBALS['ERNTESPASS'] as $k => $v) {
            $string = str_replace($k, $v, $string);
        }
        return $string;
    }

    /**
     * @param $value
     * @return string
     */
    function floatRound($value)
    {
        return number_format(floatval(round($value, 2)), 2);
    }

}