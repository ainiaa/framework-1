<?php
/**
 * Test: Nella\Templating\TemplateFilesFormatter
 *
 * This file is part of the Nella Framework (http://nellafw.org).
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * For the full copyright and license information, please view the file LICENSE.txt that was distributed with this source code.
 *
 * @testcase
 */

namespace Nella\Tests\Templating;

use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

class TemplateFilesFormatterTest extends \Tester\TestCase
{
	/** @var \Nella\Templating\TemplateFilesFormatter */
	private $formatter;

	public function setUp()
	{
		parent::setUp();
		$this->formatter = new \Nella\Templating\TemplateFilesFormatter;
		$this->formatter->useModuleSuffix = FALSE;
		$this->formatter->addDir(__DIR__)->addDir(__DIR__ . '/Nella');
	}

	public function testInstance()
	{
		Assert::true($this->formatter instanceof \Nella\Templating\ITemplateFilesFormatter);
	}

	public function dataFormatLayoutFiles()
	{
		return array(
			array(
				"Foo", "bar",
				array(
					__DIR__ . "/templates/Foo/@bar.latte",
					__DIR__ . "/templates/Foo.@bar.latte",
					__DIR__ . "/templates/@bar.latte",
					__DIR__ . "/Nella/templates/Foo/@bar.latte",
					__DIR__ . "/Nella/templates/Foo.@bar.latte",
					__DIR__ . "/Nella/templates/@bar.latte",
				),
			),
			array(
				"Foo:Bar", "layout",
				array(
					__DIR__ . "/Foo/templates/Bar/@layout.latte",
					__DIR__ . "/Foo/templates/Bar.@layout.latte",
					__DIR__ . "/Foo/templates/@layout.latte",
					__DIR__ . "/templates/Foo/Bar/@layout.latte",
					__DIR__ . "/templates/Foo/Bar.@layout.latte",
					__DIR__ . "/templates/Foo/@layout.latte",
					__DIR__ . "/templates/@layout.latte",
					__DIR__ . "/Nella/Foo/templates/Bar/@layout.latte",
					__DIR__ . "/Nella/Foo/templates/Bar.@layout.latte",
					__DIR__ . "/Nella/Foo/templates/@layout.latte",
					__DIR__ . "/Nella/templates/Foo/Bar/@layout.latte",
					__DIR__ . "/Nella/templates/Foo/Bar.@layout.latte",
					__DIR__ . "/Nella/templates/Foo/@layout.latte",
					__DIR__ . "/Nella/templates/@layout.latte",
				),
			),
		);
	}

	/**
	 * @dataProvider dataFormatLayoutFiles
	 */
	public function testFormatLayoutTemplateFiles($name, $layout, $expected)
	{
		Assert::equal($expected, $this->formatter->formatLayoutTemplateFiles($name, $layout));
	}

	public function dataFormatLayoutFilesModule()
	{
		return array(
			array(
				"Foo", "bar",
				array(
					__DIR__ . "/templates/Foo/@bar.latte",
					__DIR__ . "/templates/Foo.@bar.latte",
					__DIR__ . "/templates/@bar.latte",
					__DIR__ . "/Nella/templates/Foo/@bar.latte",
					__DIR__ . "/Nella/templates/Foo.@bar.latte",
					__DIR__ . "/Nella/templates/@bar.latte",
				),
			),
			array(
				"Foo:Bar", "layout",
				array(
					__DIR__ . "/FooModule/templates/Bar/@layout.latte",
					__DIR__ . "/FooModule/templates/Bar.@layout.latte",
					__DIR__ . "/FooModule/templates/@layout.latte",
					__DIR__ . "/templates/FooModule/Bar/@layout.latte",
					__DIR__ . "/templates/FooModule/Bar.@layout.latte",
					__DIR__ . "/templates/FooModule/@layout.latte",
					__DIR__ . "/templates/@layout.latte",
					__DIR__ . "/Nella/FooModule/templates/Bar/@layout.latte",
					__DIR__ . "/Nella/FooModule/templates/Bar.@layout.latte",
					__DIR__ . "/Nella/FooModule/templates/@layout.latte",
					__DIR__ . "/Nella/templates/FooModule/Bar/@layout.latte",
					__DIR__ . "/Nella/templates/FooModule/Bar.@layout.latte",
					__DIR__ . "/Nella/templates/FooModule/@layout.latte",
					__DIR__ . "/Nella/templates/@layout.latte",
				),
			),
		);
	}

	/**
	 * @dataProvider dataFormatLayoutFilesModule
	 */
	public function testFormatLayoutTemplateFilesModule($name, $layout, $expected)
	{
		$this->formatter->useModuleSuffix = TRUE;
		Assert::equal($expected, $this->formatter->formatLayoutTemplateFiles($name, $layout));
	}

	public function dataFormatFiles()
	{
		return array(
			array(
				"Foo", "bar",
				array(
					__DIR__ . "/templates/Foo/bar.latte",
					__DIR__ . "/templates/Foo.bar.latte",
					__DIR__ . "/templates/Foo/@global.latte",
					__DIR__ . "/templates/@global.latte",
					__DIR__ . "/Nella/templates/Foo/bar.latte",
					__DIR__ . "/Nella/templates/Foo.bar.latte",
					__DIR__ . "/Nella/templates/Foo/@global.latte",
					__DIR__ . "/Nella/templates/@global.latte",
				),
			),
			array(
				"Foo:Bar", "baz",
				array(
					__DIR__ . "/Foo/templates/Bar/baz.latte",
					__DIR__ . "/Foo/templates/Bar.baz.latte",
					__DIR__ . "/Foo/templates/Bar/@global.latte",
					__DIR__ . "/Foo/templates/@global.latte",
					__DIR__ . "/templates/Foo/Bar/baz.latte",
					__DIR__ . "/templates/Foo/Bar.baz.latte",
					__DIR__ . "/templates/Foo/Bar/@global.latte",
					__DIR__ . "/templates/Foo/@global.latte",
					__DIR__ . "/templates/@global.latte",
					__DIR__ . "/Nella/Foo/templates/Bar/baz.latte",
					__DIR__ . "/Nella/Foo/templates/Bar.baz.latte",
					__DIR__ . "/Nella/Foo/templates/Bar/@global.latte",
					__DIR__ . "/Nella/Foo/templates/@global.latte",
					__DIR__ . "/Nella/templates/Foo/Bar/baz.latte",
					__DIR__ . "/Nella/templates/Foo/Bar.baz.latte",
					__DIR__ . "/Nella/templates/Foo/Bar/@global.latte",
					__DIR__ . "/Nella/templates/Foo/@global.latte",
					__DIR__ . "/Nella/templates/@global.latte",
				),
			),
		);
	}

	/**
	 * @dataProvider dataFormatFiles
	 */
	public function testFormatTemplateFiles($name, $layout, $expected)
	{
		Assert::equal($expected, $this->formatter->formatTemplateFiles($name, $layout));
	}

	public function dataFormatFilesModule()
	{
		return array(
			array(
				"Foo", "bar",
				array(
					__DIR__ . "/templates/Foo/bar.latte",
					__DIR__ . "/templates/Foo.bar.latte",
					__DIR__ . "/templates/Foo/@global.latte",
					__DIR__ . "/templates/@global.latte",
					__DIR__ . "/Nella/templates/Foo/bar.latte",
					__DIR__ . "/Nella/templates/Foo.bar.latte",
					__DIR__ . "/Nella/templates/Foo/@global.latte",
					__DIR__ . "/Nella/templates/@global.latte",
				),
			),
			array(
				"Foo:Bar", "baz",
				array(
					__DIR__ . "/FooModule/templates/Bar/baz.latte",
					__DIR__ . "/FooModule/templates/Bar.baz.latte",
					__DIR__ . "/FooModule/templates/Bar/@global.latte",
					__DIR__ . "/FooModule/templates/@global.latte",
					__DIR__ . "/templates/FooModule/Bar/baz.latte",
					__DIR__ . "/templates/FooModule/Bar.baz.latte",
					__DIR__ . "/templates/FooModule/Bar/@global.latte",
					__DIR__ . "/templates/FooModule/@global.latte",
					__DIR__ . "/templates/@global.latte",
					__DIR__ . "/Nella/FooModule/templates/Bar/baz.latte",
					__DIR__ . "/Nella/FooModule/templates/Bar.baz.latte",
					__DIR__ . "/Nella/FooModule/templates/Bar/@global.latte",
					__DIR__ . "/Nella/FooModule/templates/@global.latte",
					__DIR__ . "/Nella/templates/FooModule/Bar/baz.latte",
					__DIR__ . "/Nella/templates/FooModule/Bar.baz.latte",
					__DIR__ . "/Nella/templates/FooModule/Bar/@global.latte",
					__DIR__ . "/Nella/templates/FooModule/@global.latte",
					__DIR__ . "/Nella/templates/@global.latte",
				),
			),
		);
	}

	/**
	 * @dataProvider dataFormatFilesModule
	 */
	public function testFormatTemplateFilesModule($name, $layout, $expected)
	{
		$this->formatter->useModuleSuffix = TRUE;
		Assert::equal($expected, $this->formatter->formatTemplateFiles($name, $layout));
	}

	public function dataComponentFormatTemplateFiles()
	{
		return array(
			array(
				'App\Foo', "bar",
				array(
					__DIR__ . "/templates/Foo/bar.latte",
					__DIR__ . "/templates/Foo.bar.latte",
					__DIR__ . "/templates/Foo/@global.latte",
					__DIR__ . "/Foo/bar.latte",
					__DIR__ . "/Foo.bar.latte",
					__DIR__ . "/Foo/@global.latte",
					__DIR__ . "/@global.latte",
					__DIR__ . "/Nella/templates/Foo/bar.latte",
					__DIR__ . "/Nella/templates/Foo.bar.latte",
					__DIR__ . "/Nella/templates/Foo/@global.latte",
					__DIR__ . "/Nella/Foo/bar.latte",
					__DIR__ . "/Nella/Foo.bar.latte",
					__DIR__ . "/Nella/Foo/@global.latte",
					__DIR__ . "/Nella/@global.latte",
				),
			),
			array(
				'App\Foo\Bar', "baz",
				array(
					__DIR__ . "/Foo/templates/Bar/baz.latte",
					__DIR__ . "/Foo/templates/Bar.baz.latte",
					__DIR__ . "/Foo/templates/Bar/@global.latte",
					__DIR__ . "/Foo/Bar/baz.latte",
					__DIR__ . "/Foo/Bar.baz.latte",
					__DIR__ . "/Foo/Bar/@global.latte",
					__DIR__ . "/Foo/@global.latte",
					__DIR__ . "/Nella/Foo/templates/Bar/baz.latte",
					__DIR__ . "/Nella/Foo/templates/Bar.baz.latte",
					__DIR__ . "/Nella/Foo/templates/Bar/@global.latte",
					__DIR__ . "/Nella/Foo/Bar/baz.latte",
					__DIR__ . "/Nella/Foo/Bar.baz.latte",
					__DIR__ . "/Nella/Foo/Bar/@global.latte",
					__DIR__ . "/Nella/Foo/@global.latte",
				),
			),
		);
	}

	/**
	 * @dataProvider dataComponentFormatTemplateFiles
	 */
	public function testComponentFormatTemplateFiles($class, $view, $expected)
	{
		Assert::equal($expected, $this->formatter->formatComponentTemplateFiles($class, $view));
	}
}

id(new TemplateFilesFormatterTest)->run(isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : NULL);
