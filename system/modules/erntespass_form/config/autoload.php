<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'MCupic',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'MCupic\ErntespassForm' => 'system/modules/erntespass_form/classes/ErntespassForm.php',
	'MCupic\PDFController'  => 'system/modules/erntespass_form/classes/PDFController.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'pdf_3'                    => 'system/modules/erntespass_form/templates',
	'pdf_2'                    => 'system/modules/erntespass_form/templates',
	'pdf_5'                    => 'system/modules/erntespass_form/templates',
	'email_bestaetigung_admin' => 'system/modules/erntespass_form/templates',
	'fe_warenkorb'             => 'system/modules/erntespass_form/templates',
	'email_bestaetigung'       => 'system/modules/erntespass_form/templates',
	'pdf_4'                    => 'system/modules/erntespass_form/templates',
	'pdf_1'                    => 'system/modules/erntespass_form/templates',
));
