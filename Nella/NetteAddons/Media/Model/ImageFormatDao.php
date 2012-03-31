<?php
/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2012 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nella-project.org
 */

namespace Nella\NetteAddons\Media\Model;

/**
 * Image format dao
 *
 * @author	Patrik Votoček
 */
class ImageFormatDao extends \Nette\Object implements IImageFormatDao
{
	/** @var array */
	private $formats;

	/**
	 * @param array
	 */
	public function __construct(array $formats = array())
	{
		$this->formats = array();
		foreach ($formats as $slug => $format) {
			$this->addFormat(array_merge($format, array('slug' => $slug)));
		}
	}

	/**
	 * @param array
	 * @return ImageFormatDao
	 */
	public function addFormat(array $format)
	{
		if (!isset($format['slug'])) {
			throw new \Nette\InvalidArgumentException('Missing slug definition');
		} elseif (!isset($format['width'])) {
			throw new \Nette\InvalidArgumentException('Missing width definition');
		} elseif (!isset($format['height'])) {
			throw new \Nette\InvalidArgumentException('Missing height definition');
		}

		$def = array(
			'width' => $format['width'],
			'height' => $format['height'],
			'flags' => isset($format['flags']) ? $format['flags'] : 4,
			'crop' => isset($format['crop']) ? $format['crop'] : FALSE,
			'watermark' => NULL,
		);

		if (isset($format['watermark'])) {
			$def['watermark'] = $foramt['watermark'];
			$def['watermarkOpacity'] = 0;
			$def['watermarkPosition'] = \Nella\NetteAddons\Media\IImageFormat::POSITION_CENTER;
		}
		if (isset($format['watermarkOpacity'])) {
			$def['watermarkOpacity'] = $foramt['watermarkOpacity'];
		}
		if (isset($format['watermarkPosition'])) {
			$def['watermarkPosition'] = $foramt['watermarkPosition'];
		}

		$this->formats[$format['slug']] = $def;
		return $this;
	}

	/**
	 * @param string
	 * @return \Nella\NetteAddons\Media\ImageFormat|NULL
	 */
	public function findOneByFullSlug($slug)
	{
		if (array_key_exists($slug, $this->formats)) {
			$def = $this->formats[$slug];
			$format= new ImageFormat($slug, $def['width'], $def['height'], $def['crop'], $def['flags']);
			if (isset($def['watermark'])) {
				$image = new Image($def['watermark']);
				$format->setWatermark($image, $def['watermarkPosition'], $def['watermarkOpacity']);
			}
			return $format;
		}

		return NULL;
	}
}
