<?php
/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2011 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nellacms.com
 */

/**
 * @param mixed
 * @param string
 */
function barDump($var, $title = NULL)
{
	Nette\Debug::barDump($var, $title);
}