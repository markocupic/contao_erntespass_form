<?php
/**
 * Created by PhpStorm.
 * User: Marko
 * Date: 11.11.2015
 * Time: 20:34
 */

namespace MCupic;


class ErntespassForm
{

    // e-mail-address
    public static $adminEmail;

    // Alias
    public static $pageAlias1;
    public static $pageAlias2;
    public static $pageAlias3;

    // numeric ids
    public static $pageId1;
    public static $pageId2;
    public static $pageId3;

    // current page alias
    public static $currentPageAlias;

    // Pathes & Directories
    public static $rechnungsVerzeichnis;
    public static $agb;
    public static $widerrufsbelehrung;

    /**
     * Die letze Bestätigungsseite kann nur aufgerufen werden, wenn alle Formulare vorher richtig durchlaufen wurden
     */
    public function initializeSystem()
    {
        self::$pageId1 = \PageModel::findByAlias(self::$pageAlias1)->id;
        self::$pageId2 = \PageModel::findByAlias(self::$pageAlias2)->id;
        self::$pageId3 = \PageModel::findByAlias(self::$pageAlias3)->id;


        // Get current page-alias
        $pageId = \FrontendIndex::getPageIdFromUrl();

        // Show page2 only if form1 was processed correctly
        if ($pageId == self::$pageAlias2) {
            if ($_SESSION['MY_FORM_DATA']['FORMS_PROCESSED']['GARTEN_BUCHEN-BESTELLFORMULAR'] != 'true') {
                self::redirectToPage(self::$pageAlias1);
            }
        }

        // Show page3 only if form2 was processed correctly
        self::$currentPageAlias = $pageId;
        if ($pageId == self::$pageAlias3) {
            if ($_SESSION['MY_FORM_DATA']['FORMS_PROCESSED']['GARTEN_BUCHEN-ZUSAMMENFASSUNG'] != 'true') {
                self::redirectToPage(self::$pageAlias1);
            } else {
                // Mehrfachversand unterbinden
                unset($_SESSION['FORM_DATA']);
                unset($_SESSION['MY_FORM_DATA']);
            }
        }

    }

    /**
     * @param $arrSubmitted
     * @param $arrData
     * @param $arrFiles
     * @param $arrLabels
     * @param $self
     */
    public function processFormData($arrSubmitted, $arrData, $arrFiles, $arrLabels, $self)
    {

        // Formular 1 ist korrekt ausgefüllt, und es darf zu Formular 2 gesprungen werden
        if (self::$currentPageAlias == self::$pageAlias1) {
            $_SESSION['MY_FORM_DATA']['FORMS_PROCESSED']['GARTEN_BUCHEN-BESTELLFORMULAR'] = 'true';
        }

        // PDF generieren
        // email senden
        if (self::$currentPageAlias == self::$pageAlias2) {


            $filename = null;
            $rechnungsnummer = null;
            for ($i = 1; $i < 999; $i++) {

                $rechnungsnummer = \Date::parse('Ymd') . str_pad($i, 3, '0', STR_PAD_LEFT);
                new \Folder('files/rechnungen');
                $filename = TL_ROOT . '/' . self::$rechnungsVerzeichnis . '/' . $rechnungsnummer . '.pdf';
                if (!file_exists($filename)) {
                    break;
                }
            }

            // Call PDF Controller for customer email
            $objpdf = new PDFController();
            $objpdf->printSheet($_SESSION['FORM_DATA'], $rechnungsnummer, $filename, false);

            // Call PDF Controller for admin email
            $objpdf = new PDFController();
            $filenameAdmin = str_replace('.pdf', '_admin.pdf', $filename);
            $objpdf->printSheet($_SESSION['FORM_DATA'], $rechnungsnummer, $filenameAdmin, true);
            sleep(2);

            // Send email to customer
            $email = new \Email();
            $email->subject = 'Ihre Bestellung bei erntespass.de';
            $objFile = new \File('system/modules/erntespass_form/templates/email_bestaetigung.html5');
            $body = $objFile->getContent();
            $body = str_replace('##ANREDE##', $_SESSION['FORM_DATA']['anrede'], $body);
            $body = str_replace('##NACHNAME##', $_SESSION['FORM_DATA']['nachname'], $body);
            $email->text = utf8_encode($body);
            $email->attachFile($filename);
            $email->attachFile(self::$agb);
            $email->attachFile(self::$widerrufsbelehrung);
            $email->sendTo($_SESSION['FORM_DATA']['email']);
            sleep(2);

            // Send email to administrator
            $email = new \Email();
            $email->subject = 'Neue Bestellung bei erntespass.de';
            $objFile = new \File('system/modules/erntespass_form/templates/email_bestaetigung_admin.html5');
            $bodyAdmin = $objFile->getContent();
            $email->text = utf8_encode($bodyAdmin . $body);
            $email->attachFile($filename);
            $email->attachFile($filenameAdmin);


            // Send email to Jeanette Lagall
            $email->sendTo(self::$adminEmail);

            // Clear session
            unset($_SESSION['MY_FORM_DATA']);
            $_SESSION['MY_FORM_DATA']['FORMS_PROCESSED']['GARTEN_BUCHEN-ZUSAMMENFASSUNG'] = 'true';
        }
    }


    /**
     * Inserttags aus der session auslesen
     * @param $strTag
     * @return bool
     */
    public function replaceInsertTags($strTag)
    {
        /*
        if ($strTag == 'form::feld1' && $_SESSION['FORM_DATA']['feld1'] != '') {
            return $_SESSION['FORM_DATA']['feld1'];
        }

        if ($strTag == 'form::feld2' && $_SESSION['FORM_DATA']['feld2'] != '') {
            return $_SESSION['FORM_DATA']['feld2'];
        }
        */

        return false;
    }

    /**
     * Do not check input on fields inside a non-enabled FormCondition group
     * @param $objWidget
     * @param $formId
     * @param $arrForm
     * @return mixed
     */
    public function loadFormField($objWidget, $formId, $arrForm)
    {
        if (self::$currentPageAlias == self::$pageAlias1) {

            // Load labels that are predefined in config.php
            if ($_SESSION['MY_FORM_DATA']['arrLabelReady'] != 'true') {
                foreach ($GLOBALS['ERNTESPASS']['labels'] as $k => $v) {
                    $_SESSION['MY_FORM_DATA']['arrLabelReady'] = 'true';
                    $_SESSION['MY_FORM_DATA']['arrLabels'][$k] = $v;
                }
            }

            // Fill Label Array if it isn't already predefined
            if ($_SESSION['MY_FORM_DATA']['arrLabels'][$objWidget->name] == '') {
                $_SESSION['MY_FORM_DATA']['arrLabels'][$objWidget->name] = $objWidget->label;
            }

            // Set Correct Label for option fields
            if (is_array($objWidget->options)) {
                foreach ($objWidget->options as $arrOption) {
                    if ($arrOption['label'] != '') {
                        $_SESSION['MY_FORM_DATA']['arrLabels'][$arrOption['value']] = $arrOption['label'];
                    }
                }
            }

        }

        if (self::$currentPageAlias == self::$pageAlias2) {
            if ($_SESSION['FORM_DATA']['anfang_geschenkadresse'] == '') {
                if (strpos($objWidget->name, '_geschenkadresse') !== false) {
                    $objWidget->mandatory = false;
                    $objWidget->rgxp = '';
                    $objWidget->value = '';
                }
            }
        }
        return $objWidget;
    }

    /**
     * @param $arrFields
     * @param $formId
     * @param $self
     * @return mixed
     */
    public function compileFormFields($arrFields, $formId, $self)
    {

        // Direktes Aufrufen von formular 2, wenn formular 1 nicht erfolgreich validiert wurde, sollte nicht m�glich sein.
        if ($formId == 'auto_garten-buchen-zusammenfassung') {
            if ($_SESSION['MY_FORM_DATA']['FORMS_PROCESSED']['GARTEN_BUCHEN-BESTELLFORMULAR'] != 'true') {
                self::redirectToPage(self::$pageAlias1);
            }
        }
        return $arrFields;
    }


    /**
     * @param string $strAlias
     */
    public function redirectToPage($strAlias = '')
    {
        if ($strAlias == '') {
            $strAlias = self::$pageAlias1;
        }
        // Weiterleitung zur Fehlerseite oder Formularseite
        $objPage = \PageModel::findPublishedByIdOrAlias($strAlias);
        if ($objPage !== null) {
            $arrPage = $objPage->row();
            // $additionalQueryString = '/additionalquerystring/vars';
            $additionalQueryString = '';
            $strUrl = \Controller::generateFrontendUrl($arrPage, $additionalQueryString);
            \Controller::redirect($strUrl);
        } else {
            die('Auf der Seite ist ein Fehler aufgetreten');
        }
    }


    /**
     * �ndert die Seite auf die weitergeleitet werden soll, wenn das Formular verarbeitet wurde.
     * @param $strAlias
     * @param $objForm
     */
    public static function modifyJumpToPage($strAlias, $objForm)
    {
        $objPage = \PageModel::findByAlias($strAlias);
        if ($objPage !== null) {
            $objModel = \FormModel::findByPk($objForm->id);
            $objModel->jumpTo = $objPage->id;
        } else {
            die('Auf der Seite ist ein Fehler aufgetreten');
        }
    }

}