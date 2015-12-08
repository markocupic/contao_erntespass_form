<?php
$arrFields = array(
    'anrede','vorname','nachname','strasse','postleitzahl','ort','telefon','email','standort',
    'anfang_geschenkadresse','anrede_geschenkadresse','vorname_geschenkadresse','nachname_geschenkadresse','strasse_geschenkadresse','postleitzahl_geschenkadresse','ort_geschenkadresse','telefon_geschenkadresse','e-mail_geschenkadresse','datum_kontakt_erlaubt_geschenkadresse',
    'anmerkung','zahlungsart',
    //'gartengroesse','gartenname',
    //'anzahl_schutznetze',
    //'agb','widerrufsbelehrung',
    //'newsletter',
);

$mwstSatz = $GLOBALS['ERNTESPASS']['##MWSTSATZ##'];

// Garten
$gartenBeschrieb = $_SESSION['FORM_DATA']['gartengroesse'] == 'klein' ? $GLOBALS['ERNTESPASS']['##KLEINER_GEMUESEGARTEN##'] : $GLOBALS['ERNTESPASS']['##GROSSER_GEMUESEGARTEN##'];
$gartenBeschrieb = $_SESSION['FORM_DATA']['gartengroesse'] == 'nur-netz' ? $GLOBALS['ERNTESPASS']['##NUR_SCHUTZNETZ##'] : $gartenBeschrieb;

$produktAnzahl = 1;
$produktAnzahl = $_SESSION['FORM_DATA']['gartengroesse'] == 'nur-netz' ? '0' : $produktAnzahl;

$gartenPreis = $_SESSION['FORM_DATA']['gartengroesse'] == 'klein' ? $GLOBALS['ERNTESPASS']['##PREIS_KLEINER_GEMUESEGARTEN##']  : $GLOBALS['ERNTESPASS']['##PREIS_GROSSER_GEMUESEGARTEN##'];
$gartenPreis = $_SESSION['FORM_DATA']['gartengroesse'] == 'nur-netz' ? 0 : $gartenPreis;
$gartenPreis = $gartenPreis/(100+$mwstSatz)*100;

// Schutznetze
$anzSchutznetze = $_SESSION['FORM_DATA']['anzahl_schutznetze'];
$NettoPreisSchutznetz = $GLOBALS['ERNTESPASS']['##PREIS_SCHUTZNETZ##']/(100 + $mwstSatz)*100;
$NettoPreisAlleSchutznetze = $GLOBALS['ERNTESPASS']['##PREIS_SCHUTZNETZ##']/(100 + $mwstSatz)*100*$anzSchutznetze;

$Nettobetrag = ($NettoPreisAlleSchutznetze + $gartenPreis);
$mwstBetrag = $Nettobetrag*$mwstSatz/100;
$Bruttobetrag = $Nettobetrag/100*(100+$mwstSatz);

function floatRound($value){
    return number_format(floatval(round($value, 2)),2);
}

?>


<div class="erntespass-zusammenfassung">
<?php //die(print_r($_SESSION['ERNTESPASS_FORM_DATA']['arrLabels'],true)); ?>
    <h1>Ihre Adressangaben</h1>
    <table class="warenkorb-table-contact">
        <?php foreach($arrFields as $k): ?>
        <?php if(strpos($k, 'geschenkadresse') !== false && $_SESSION['FORM_DATA']['anfang_geschenkadresse'] == '')continue; ?>
        <?php $label = $_SESSION['ERNTESPASS_FORM_DATA']['arrLabels'][$k]; ?>
        <?php $v = $_SESSION['FORM_DATA'][$k]; ?>
        <?php if($_SESSION['ERNTESPASS_FORM_DATA']['arrLabels'][$v] != ''){$v = $_SESSION['ERNTESPASS_FORM_DATA']['arrLabels'][$v];} ?>
        <?php if($k == 'anfang_geschenkadresse') {$v = 'Ja'; $label = 'Der Garten ist ein Geschenk:';}?>
        <?php if ($v == '') $v = 'Keine Angabe'; ?>
        <tr class="warenkorb-table-contact-row row-<?php echo $k; ?>">
            <td class="col_1"><?php echo $label; ?></td>
            <td class="col_2"><?php echo nl2br($v); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>


    <h1>Ihr Warenkorb</h1>
    <table class="warenkorb-table-items">
        <tr class="warenkorb-table-items-row-head">
            <th class="col_1">Produkt</th>
            <th class="col_2">Preis</th>
            <th class="col_3">Anzahl</th>
            <th class="col_4">Betrag</th>
        </tr>

        <tr class="warenkorb-table-items-row-garten">
            <td class="col_1"><?php echo $gartenBeschrieb; ?></td>
            <td class="col_2"><?php echo floatRound($gartenPreis); ?> &euro;</td>
            <td class="col_3"><?php echo $produktAnzahl; ?></td>
            <td class="col_4"><?php echo floatRound($gartenPreis); ?> &euro;</td>
        </tr>

        <tr class="warenkorb-table-items-row-schutznetz">
            <td class="col_1">Kulturschutznetz (5m x 2,30m) incl. 10 Netzhaltern</td>
            <td class="col_2"><?php echo floatRound($NettoPreisSchutznetz); ?> &euro;</td>
            <td class="col_3"><?php echo $anzSchutznetze; ?></td>
            <td class="col_4"><?php echo floatRound($NettoPreisAlleSchutznetze); ?> &euro;</td>
        </tr>

        <!--
        <tr class="warenkorb-table-items-row-nettobetrag">
            <td class="col_1">&nbsp;</td>
            <td class="col_2">Nettobetrag</td>
            <td class="col_3">&nbsp;</td>
            <td class="col_4"><?php echo floatRound($Nettobetrag); ?> &euro;</td>
        </tr>

        <tr class="warenkorb-table-items-row-mwst-satz">
            <td class="col_1">&nbsp;</td>
            <td class="col_2">MwSt.-Satz</td>
            <td class="col_3">&nbsp;</td>
            <td class="col_4"><?php echo $mwstSatz; ?>%</td>
        </tr>


        <tr class="warenkorb-table-items-row-mwst-betrag">
            <td class="col_1">&nbsp;</td>
            <td class="col_2">MwSt.-Betrag</td>
            <td class="col_3">&nbsp;</td>
            <td class="col_4"><?php echo floatRound($mwstBetrag); ?> &euro;</td>
        </tr>
        -->

        <tr class="warenkorb-table-items-row-mwst-bruttobetrag">
            <td class="col_1">&nbsp;</td>
            <td class="col_2">Bruttobetrag</td>
            <td class="col_3">&nbsp;</td>
            <td class="col_4"><?php echo floatRound($Bruttobetrag); ?> &euro;</td>
        </tr>

    </table>
    <div class="anmerkung-ust">
    <p>Alle angegebenen Preise sind Endpreise. Aufgrund des Kleinunternehmerstatus gem. &sect; 19 UStG wird keine Umsatzsteuer erhoben und daher auch nicht ausgewiesen.</p>
    </div>
    <a href="index.php/garten-buchen-bestellformular.html">Bestellung bearbeiten</a>
</div>

