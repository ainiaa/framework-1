<?php
/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nella-project.org
 */

namespace Nella\Application\Routers;

use Nette\Application\Request;

/**
 * Console router
 *
 * For use Symfony Console
 *
 * @author	Patrik Votoček
 */
class CliRouter extends \Nette\Object implements \Nette\Application\IRouter
{
	/** @var \Nette\Callback */
	private $callback;

	/**
	 * @param \Symfony\Component\Console\Application
	 */
	public function __construct(\Symfony\Component\Console\Application $console)
	{
		$this->callback = callback(function() use($console) {
			$console->run();
		});
	}

	/**
	 * Maps command line arguments to a Request object
	 *
	 * @param  \Nette\Http\IRequest
	 * @return \Nette\Application\Request|NULL
	 */
	public function match(\Nette\Http\IRequest $httpRequest)
	{
		if (PHP_SAPI !== 'cli') {
			return NULL;
		}

		return new Request('Nette:Micro', 'CLI', array('callback' => $this->callback));
	}

	/**
	 * This router is only unidirectional
	 *
	 * @param  \Nette\Application\Request
	 * @param  \Nette\Http\Url
	 * @return NULL
	 */
	public function constructUrl(Request $appRequest, \Nette\Http\Url $refUrl)
	{
		return NULL;
	}
}
