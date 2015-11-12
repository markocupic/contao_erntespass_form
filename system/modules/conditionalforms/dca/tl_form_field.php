<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2009-2011
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id$
 */


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['__selector__'][] = 'conditionType';
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['condition'] = '{type_legend},type,name;{fconfig_legend},conditionType';
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['conditionstart'] = '{type_legend},type,name,label;{fconfig_legend},conditionType';
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['conditionstop'] = '{type_legend},type;{fconfig_legend},conditionType';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_form_field']['fields']['value']['eval']['decodeEntities'] = true;

$GLOBALS['TL_DCA']['tl_form_field']['fields']['conditionType'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_form_field']['conditionType'],
	'default'                 => 'start',
	'exclude'                 => true,
	'inputType'               => 'radio',
	'options'                 => array('start', 'stop'),
	'reference'               => &$GLOBALS['TL_LANG']['tl_form_field'],
	'eval'                    => array('submitOnChange'=>true)
);

