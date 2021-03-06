<?php
/**
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information,
 * please view the file LICENSE.txt that was distributed with this source code.
 */

namespace Nella\Templating;

use Nette\Application\UI\Control;

/**
 * Template files paths formatter
 *
 * @author	Patrik Votoček
 */
class TemplateFilesFormatter extends \Nette\Object implements ITemplateFilesFormatter
{
	const MODULE_SUFFIX = 'Module';

	/** @var bool */
	public $useModuleSuffix = TRUE;
	/** @var \SplPriorityQueue */
	private $dirs;
	/** @var IFilesFormatterLogger|NULL */
	private $logger;

	public function __construct()
	{
		$this->dirs = new \SplPriorityQueue;
		$this->logger = NULL;
	}

	/**
	 * @param string
	 * @param int
	 * @return TemplateFactoryTest
	 */
	public function addDir($dir, $priority = 5)
	{
		$this->dirs->insert($dir, $priority);
		return $this;
	}

	/**
	 * @param IFilesFormatterLogger
	 * @return TemplateFilesFormatter
	 */
	public function setLogger(IFilesFormatterLogger $logger)
	{
		$this->logger = $logger;
		return $this;
	}

	/**
	 * Formats layout template file names
	 *
	 * @param string	presenter name
	 * @param string	layout name
	 * @return array
	 */
	public function formatLayoutTemplateFiles($name, $layout = 'layout')
	{
		$path = str_replace(':', '/', substr($name, 0, strrpos($name, ':')));
		$subPath = substr($name, strrpos($name, ':') !== FALSE ? strrpos($name, ':') + 1 : 0);
		if ($path) {
			$path .= '/';
		}

		if ($this->useModuleSuffix && $path) {
			$path = str_replace('/', self::MODULE_SUFFIX . '/', $path);
		}

		$generator = function ($dir) use ($name, $path, $subPath, $layout) {
			$files = array();
			// classic modules templates
			if (strpos($name, ':') !== FALSE) {
				$files[] = $dir . '/' .$path . "templates/$subPath/@$layout.latte";
				$files[] = $dir . '/' .$path . "templates/$subPath.@$layout.latte";
				$files[] = $dir . '/' .$path . "templates/@$layout.latte";
			}
			// classic templates
			$files[] = $dir . '/templates/' .$path . "$subPath/@$layout.latte";
			$files[] = $dir . '/templates/' .$path . "$subPath.@$layout.latte";
			$files[] = $dir . '/templates/' .$path . "@$layout.latte";

			$file = $dir . "/templates/@$layout.latte";
			if (!in_array($file, $files)) {
				$files[] = $file;
			}

			return $files;
		};

		$files = array();
		$dirs = clone $this->dirs;
		foreach ($dirs as $dir) {
			$files = array_merge($files, $generator($dir));
		}

		if ($this->logger) {
			$this->logger->logFiles($name, $layout, $files);
		}

		return $files;
	}

	/**
	 * Formats view template file names
	 *
	 * @param string	presenter name
	 * @param string	view name
	 * @return array
	 */
	public function formatTemplateFiles($name, $view)
	{
		$path = str_replace(':', '/', substr($name, 0, strrpos($name, ':')));
		$subPath = substr($name, strrpos($name, ':') !== FALSE ? strrpos($name, ':') + 1 : 0);
		if ($path) {
			$path .= '/';
		}

		if ($this->useModuleSuffix && $path) {
			$path = str_replace('/', self::MODULE_SUFFIX . '/', $path);
		}

		$generator = function ($dir) use ($name, $path, $subPath, $view) {
			$files = array();
			// classic modules templates
			if (strpos($name, ':') !== FALSE) {
				$files[] = $dir . '/' .$path . "templates/$subPath/$view.latte";
				$files[] = $dir . '/' .$path . "templates/$subPath.$view.latte";
				$files[] = $dir . '/' .$path . "templates/$subPath/@global.latte";
				$files[] = $dir . '/' .$path . 'templates/@global.latte';

			}
			// classic templates
			$files[] = $dir . '/templates/' .$path . "$subPath/$view.latte";
			$files[] = $dir . '/templates/' .$path . "$subPath.$view.latte";
			$files[] = $dir . '/templates/' .$path . "$subPath/@global.latte";
			$files[] = $dir . '/templates/' .$path . '@global.latte';

			$file = $dir . '/templates/@global.latte';
			if (!in_array($file, $files)) {
				$files[] = $file;
			}

			return $files;
		};

		$files = array();
		$dirs = clone $this->dirs;
		foreach ($dirs as $dir) {
			$files = array_merge($files, $generator($dir));
		}

		if ($this->logger) {
			$this->logger->logFiles($name, $view, $files);
		}

		return $files;
	}

	/**
	 * Formats layout template file names
	 *
	 * @param string	control name
	 * @param string	view name
	 * @return array
	 */
	public function formatComponentTemplateFiles($class, $view)
	{
		if (\Nette\Utils\Strings::endsWith($class, 'Control')) {
			$class = substr($class, 0, -7);
		}
		$name = substr($class, strpos($class, '\\')+1);
		$path = str_replace('\\', '/', substr($name, 0, strrpos($name, '\\')));
		$subPath = substr($name, strrpos($name, '\\') !== FALSE ? strrpos($name, '\\') + 1 : 0);
		if ($path) {
			$path .= '/';
		}

		$generator = function ($dir) use ($name, $path, $subPath, $view) {
			$files = array();

			if ($view) {
				$files[] = $dir . '/' .$path . "templates/$subPath/$view.latte";
				$files[] = $dir . '/' .$path . "templates/$subPath.$view.latte";
			} else {
				$files[] = $dir . '/' .$path . "templates/$subPath.latte";
			}
			$files[] = $dir . '/' .$path . "templates/$subPath/@global.latte";

			if ($view) {
				$files[] = $dir . '/' .$path . "$subPath/$view.latte";
				$files[] = $dir . '/' .$path . "$subPath.$view.latte";
			} else {
				$files[] = $dir . '/' .$path . "$subPath.latte";
			}
			$files[] = $dir . '/' .$path . "$subPath/@global.latte";
			$files[] = $dir . '/' .$path . '@global.latte';

			return $files;
		};

		$files = array();
		$dirs = clone $this->dirs;
		foreach ($dirs as $dir) {
			$files = array_merge($files, $generator($dir));
		}

		if ($this->logger) {
			$this->logger->logFiles($name, $view, $files);
		}

		return $files;
	}
}

