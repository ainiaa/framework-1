<?php
/**
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information,
 * please view the file LICENSE.txt that was distributed with this source code.
 */

namespace Nella\Latte\Macros;

use Nette\Latte\MacroNode,
	Nette\Latte\PhpWriter;

/**
 * Media macros
 *
 * /--code latte
 * {* phref - presenter link - plink n:macro *}
 * <a n:phref=":Homepage:default">Link</a>
 * \--
 *
 * @author	Patrik Votoček
 */
class UIMacros extends \Nette\Latte\Macros\MacroSet
{
	/**
	 * @param \Nette\Latte\Engine
	 * @return \Nette\Latte\Macros\MacroSet
	 */
	public static function factory(\Nette\Latte\Engine $engine)
	{
		return static::install($engine->getCompiler());
	}

	/**
	 * @param \Nette\Latte\Compiler
	 */
	public static function install(\Nette\Latte\Compiler $compiler)
	{
		$me = new static($compiler);

		// n:phref
		$me->addMacro('phref', NULL, NULL, function (MacroNode $node, PhpWriter $writer) use ($me) {
			return ' ?> href="<?php ' . $me->macroPresenterLink($node, $writer) . ' ?>"<?php ';
		});
	}

	/**
	 * n:phref="destination [,] [params]"
	 */
	public function macroPresenterLink(MacroNode $node, PhpWriter $writer)
	{
		return $writer->write('echo %escape($_presenter->link(%node.word, %node.array?))');
	}
}

