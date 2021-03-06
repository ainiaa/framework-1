<?php
/**
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information,
 * please view the file LICENSE.txt that was distributed with this source code.
 */

namespace Nella\Diagnostics\Config;

use Nette\Config\Configurator,
	Nette\Config\Compiler,
	Nette\Application\Application,
	Nette\Http\Response,
	Nella\Diagnostics\AccessLogger,
	Nette\Utils\PhpGenerator\ClassType;

/**
 * Diagnostics services
 *
 * @author	Patrik Votoček
 */
class Extension extends \Nette\Config\CompilerExtension
{
	const DEFAULT_EXTENSION_NAME = 'diagnostics';

	/** @var array */
	public $defaults = array(
		'loggerUrl' => 'http://localhost:50921/api/log.json',
		'accessLoggerUrl' => 'http://localhost:50921/api/access.json',
		'storage' => 'curl',
		'callbackPanel' => TRUE,
	);

	/**
	 * Processes configuration data
	 *
	 * @throws \Nette\InvalidStateException
	 */
	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		if (!isset($config['appId']) || !isset($config['appSecret'])) {
			return;
		}

		switch($config['storage']) {
			case 'curl':
				$storageClass = 'Nella\Diagnostics\LoggerStorages\Curl';
				break;
			default:
				$storageClass = 'Nella\Diagnostics\LoggerStorages\Http';
				break;
		}


		$builder->addDefinition($this->prefix('accessStorage'))
			->setClass($storageClass, array(
				$config['appId'], $config['appSecret'], $config['accessLoggerUrl']
			));
		$builder->addDefinition($this->prefix('accessLogger'))
			->setClass('Nella\Diagnostics\AccessLogger', array($this->prefix('@accessStorage')));
	}

	/**
	 * @param \Nette\Application\Application
	 * @param \Nette\Http\Response
	 * @param \Nella\Diagnostics\AccessLogger
	 */
	public static function setCallback(Application $application, Response $res, AccessLogger $logger)
	{
		$application->onShutdown[] = function (Application $application) use ($logger, $res) {
			$logger->log($res);
		};
	}

	/**
	 * @param \Nette\Utils\PhpGenerator\ClassType
	 */
	public function afterCompile(ClassType $class)
	{
		$config = $this->getConfig($this->defaults);
		$initialize = $class->methods['initialize'];

		if ($config['callbackPanel']) {
			$initialize->addBody('Nella\Diagnostics\CallbackPanel::register($this);');
		}

		if (!isset($config['appId']) || !isset($config['appSecret'])) {
			return;
		}

		$password = isset($config['password']) ? $config['password'] : FALSE;

		$initialize->addBody('\Nella\Diagnostics\Logger::register(?, ?, ?, ?);', array(
			$config['appId'], $config['appSecret'], $password, $config['loggerUrl']
		));

		$initialize->addBody(
			get_called_class().'::setCallback($this->getService(?), $this->getService(?), $this->getService(?));',
			array('application', 'httpResponse', $this->prefix('accessLogger'))
		);
	}

	/**
	 * Register extension to compiler.
	 *
	 * @param \Nette\Config\Configurator
	 * @param string
	 */
	public static function register(Configurator $configurator, $name = self::DEFAULT_EXTENSION_NAME)
	{
		$class = get_called_class();
		$configurator->onCompile[] = function (Configurator $configurator, Compiler $compiler) use ($class, $name) {
			$compiler->addExtension($name, new $class);
		};
	}
}

