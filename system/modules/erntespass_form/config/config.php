<?php

if(TL_MODE == 'FE'){

    $GLOBALS['TL_CSS'][] = 'system/modules/erntespass_form/assets/fe_erntespass_form.css';


    // Do yout settings here:

    // Fehlende labels
    $GLOBALS['ERNTESPASS']['labels']['standort'] = 'Standort:';
    $GLOBALS['ERNTESPASS']['labels']['gartengroesse'] = 'Gartengr&ouml;sse:';
    $GLOBALS['ERNTESPASS']['labels']['anfang_geschenkadresse'] = 'Der Garten ist ein Geschenk:';
    $GLOBALS['ERNTESPASS']['labels']['agb'] = 'AGB:';
    $GLOBALS['ERNTESPASS']['labels']['widerrufsbelehrung'] = 'Widerrufsbelehrung:';
    $GLOBALS['ERNTESPASS']['labels']['newsletter'] = 'Newsletter abonnieren:';


    // PDF Platzhalter
    $GLOBALS['ERNTESPASS']['##GROSSER_GEMUESEGARTEN##'] = "Erntespass 80 (Gem&uuml;segarten gross ca. 85m&sup2;)";
    $GLOBALS['ERNTESPASS']['##KLEINER_GEMUESEGARTEN##'] = "Erntespass 40 (Gem&uuml;segarten klein ca. 45m&sup2;)";
    $GLOBALS['ERNTESPASS']['##NUR_SCHUTZNETZ##'] = "Kein Garten, nur Pflanzenschutznetz";

    // Brutto-Preis
    $GLOBALS['ERNTESPASS']['##PREIS_GROSSER_GEMUESEGARTEN##'] = 369;
    $GLOBALS['ERNTESPASS']['##PREIS_KLEINER_GEMUESEGARTEN##'] = 199;
    $GLOBALS['ERNTESPASS']['##PREIS_SCHUTZNETZ##'] = 18;
    $GLOBALS['ERNTESPASS']['##MWSTSATZ##'] = "0";

    $GLOBALS['ERNTESPASS']['##ERNTESAISON##'] = "2016";
    $GLOBALS['ERNTESPASS']['##SCHUTZNETZ##'] = "Kulturschutznetz (5m x 2,30m) incl. 10 Netzhaltern";

    MCupic\ErntespassForm::$adminEmail = "info@needful-web.de";
    //MCupic\ErntespassForm::$adminEmail = "m.cupic@gmx.ch";
    //MCupic\ErntespassForm::$adminEmail = "info@erntespass.de";

    MCupic\ErntespassForm::$pageAlias1 = "garten-buchen-bestellformular";
    MCupic\ErntespassForm::$pageAlias2 = "garten-buchen-zusammenfassung";
    MCupic\ErntespassForm::$pageAlias3 = "garten-buchen-bestaetigung";

    MCupic\ErntespassForm::$rechnungsVerzeichnis = 'files/erntespass/rechnungen';
    MCupic\ErntespassForm::$agb = 'files/erntespass/pdf/AGB_Erntespass.pdf';
    MCupic\ErntespassForm::$widerrufsbelehrung = 'files/erntespass/pdf/Widerrufsbelehrung_Erntespass.pdf';
    /** End settings */



    // Referer in session setzen, email Versand, pdf generieren
    $GLOBALS['TL_HOOKS']['processFormData'][] = array('MCupic\ErntespassForm', 'processFormData');

    // Inserttags mit Feldwerten aus der session ersetzen
    $GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('MCupic\ErntespassForm', 'replaceInsertTags');

    // Do not check input on fields inside a non-enabled FormCondition group
    $GLOBALS['TL_HOOKS']['loadFormField'][] = array('MCupic\ErntespassForm', 'loadFormField');

    // Weiterleitung bei unerlaubtem Zugriff auf Seite
    $GLOBALS['TL_HOOKS']['compileFormFields'][] = array('MCupic\ErntespassForm', 'compileFormFields');

    $GLOBALS['TL_HOOKS']['initializeSystem'][] = array('MCupic\ErntespassForm', 'initializeSystem');

    //$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/erntespass_form/assets/fe_erntespass_form.js';

}



