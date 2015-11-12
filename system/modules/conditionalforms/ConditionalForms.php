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
 * @copyright  Andreas Schempp 2012
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


class ConditionalForms extends Frontend
{

	/**
	 * Do not check input on fields inside a non-enabled FormCondition group
	 *
	 * @param	Widget
	 * @param	int
	 * @param	array
	 * @return	Widget
	 * @link	http://www.contao.org/hooks.html#loadFormField
	 */
	public function loadFormField($objWidget, $formId, $arrForm)
	{
		if ($objWidget instanceof FormCondition)
		{
			// Activate field validation excemption
			if ($objWidget->conditionType == 'start')
			{
				$GLOBALS['FORM_CONDITION'][$formId] = ($_POST[$objWidget->name] == '');
			}

			// Deactivate field validation exception
			elseif ($objWidget->conditionType == 'stop')
			{
				unset($GLOBALS['FORM_CONDITION'][$formId]);
			}
		}
		else
		{
			// We have a mandatory field in conditional section, disable client side validation
			if (isset($GLOBALS['FORM_CONDITION'][$formId]) && $objWidget->mandatory)
			{
				$GLOBALS['TL_HOOKS']['parseTemplate']['conditionalforms'] = array('ConditionalForms', 'disableBrowserValidation');
			}

			// Disable field validation inside FormCondition
			if ($GLOBALS['FORM_CONDITION'][$formId] && $this->Input->post('FORM_SUBMIT') == $formId)
			{
				$objWidget->mandatory = false;
				$objWidget->rgxp = '';
			}
		}

		return $objWidget;
	}


	/**
	 * Disable client side validation
	 * @param	object
	 * @link	http://www.contao.org/hooks.html#loadFormField
	 */
	public function disableBrowserValidation($objTemplate)
	{
		if ($objTemplate->getName() == 'form')
	    {
	        $objTemplate->attributes = $objTemplate->attributes . ' novalidate';
	        unset($GLOBALS['TL_HOOKS']['parseTemplate']['conditionalforms']);
	    }
	}
}

