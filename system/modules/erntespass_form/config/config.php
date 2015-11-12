<?php

if(TL_MODE == 'FE'){

    // Do yout settings here:
    $GLOBALS['ERNTESPASS']['##GROSSER_GEMUESEGARTEN##'] = "Erntespass 80 (Gem&uuml;segarten gross ca. 85m&sup2;)";
    $GLOBALS['ERNTESPASS']['##KLEINER_GEMUESEGARTEN##'] = "Erntespass 40 (Gem&uuml;segarten klein ca. 45m&sup2;)";

    // Brutto-Preis
    $GLOBALS['ERNTESPASS']['##PREIS_GROSSER_GEMUESEGARTEN##'] = 369;
    $GLOBALS['ERNTESPASS']['##PREIS_KLEINER_GEMUESEGARTEN##'] = 199;
    $GLOBALS['ERNTESPASS']['##PREIS_SCHUTZNETZ##'] = 18;
    $GLOBALS['ERNTESPASS']['##MWSTSATZ##'] = "19";

    $GLOBALS['ERNTESPASS']['##ERNTESAISON##'] = "2016";
    $GLOBALS['ERNTESPASS']['##SCHUTZNETZ##'] = "Kulturschutznetz (5m x 2,30m) incl. 10 Netzhaltern";

    MCupic\ErntespassForm::$adminEmail = "info@needful-web.de";
    //MCupic\ErntespassForm::$adminEmail = "m.cupic@gmx.ch";

    MCupic\ErntespassForm::$pageAlias1 = "garten-buchen-bestellformular";
    MCupic\ErntespassForm::$pageAlias2 = "garten-buchen-zusammenfassung";
    MCupic\ErntespassForm::$pageAlias3 = "garten-buchen-bestaetigung";

    MCupic\ErntespassForm::$rechnungsVerzeichnis = 'files/rechnungen';
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
}



