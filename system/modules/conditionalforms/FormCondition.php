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


class FormCondition extends Widget
{

	/**
	 * Submit user input
	 * @var boolean
	 */
	protected $blnSubmitInput = true;

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'form_condition';


	/**
	 * Add specific attributes
	 * @param string
	 * @param mixed
	 */
	public function __set($strKey, $varValue)
	{
		switch ($strKey)
		{
			case 'value':
				$this->varValue = $varValue ? true : false;
				break;

			case 'options':
				break;

			default:
				parent::__set($strKey, $varValue);
				break;
		}
	}


	/**
	 * Do not check stop fields.
	 *
	 * @param	mixed $varInput
	 * @return	mixed
	 */
	protected function validator($varInput)
	{
		if ($this->conditionType == 'stop')
		{
			$this->mandatory = false;
			$this->blnSubmitInput = false;
		}

		return parent::validator($varInput);
	}


	/**
	 * Generate the widget
	 *
	 * @param	void
	 * @return	string
	 */
	public function generate()
	{
		return sprintf('<input type="hidden" name="%s" value="" /><input type="checkbox" name="%s" id="opt_%s" class="checkbox" value="1" onclick="if(this.checked) { document.id(\'condition_%s\').style.display=\'block\'; } else { document.id(\'condition_%s\').style.display=\'none\'; }"%s%s /> <label for="opt_%s">%s</label>',
						$this->strName,
						$this->strName,
						$this->strId,
						$this->strName,
						$this->strName,
						($this->varValue ? ' checked="checked"' : ''),
						$this->getAttributes(),
						$this->strId,
						$this->label);
	}

}

