<?php
/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2011 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nella-project.org
 */

namespace Nella\NetteAddons\Media\Model;

/**
 * Media image format entity
 *
 * @author	Patrik Votoček
 */
class ImageFormat extends \Nette\Object implements \Nella\NetteAddons\Media\IImageFormat
{
	/** @var string */
	private $slug;
	/** @var int */
	private $width;
	/** @var int */
	private $height;
	/** @var bool */
	private $crop;
	/** @var int */
	private $flags;
	/** @var \Nella\NetteAddons\Media\IImage|NULL */
	private $watermark;
	/** @var int */
	private $watermarkPosition;
	/** @var int */
	private $watermarkOpacity;

	/**
	 * @param string
	 * @param int
	 * @param int
	 * @param bool
	 * @param int
	 */
	public function __construct($slug, $width, $height, $crop = FALSE, $flags = 0)
	{
		$this->slug=  $slug;
		$this->width = $width;
		$this->height = $height;
		$this->crop = $crop;
		$this->flags = $flags;

		$this->watermark = $this->watermarkOpacity = $this->watermarkPosition = NULL;
	}

	/**
	 * @param \Nella\NetteAddons\Media\IImage
	 * @param int
	 * @param int
	 */
	public function setWatermark(\Nella\NetteAddons\Media\IImage $image, $position = self::POSITION_CENTER, $opacity = 0)
	{
		$this->watermark = $image;
		$this->watermarkOpacity = $opacity;
		$this->watermarkPosition = $position;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * @return string
	 */
	public function getFullSlug()
	{
		return $this->getSlug();
	}

	/**
	 * @return int
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 * @return int
	 */
	public function getHeight()
	{
		return $this->height;
	}

	/**
	 * @return int
	 */
	public function getFlags()
	{
		return $this->flags;
	}

	/**
	 * @return bool
	 */
	public function isCrop()
	{
		return $this->crop;
	}

	/**
	 * @return \Nella\NetteAddons\Media\IImage|NULL
	 */
	public function getWatermark()
	{
		return $this->watermark;
	}

	/**
	 * @return int
	 */
	public function getWatermarkPosition()
	{
		return $this->watermarkPosition;
	}

	/**
	 * @return int
	 */
	public function getWatermarkOpacity()
	{
		return $this->watermarkOpacity;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->getFullSlug();
	}
}