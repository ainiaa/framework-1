<?php
/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2011 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nella-project.org
 */

namespace Nella\NetteAddons\Media\Callbacks;

use Nette\Image,
	Nella\NetteAddons\Media\IImage, 
	Nella\NetteAddons\Media\IImageFormat;

/**
 * Image presenter callback (convert request to response)
 *
 * @author	Patrik Votoček
 */
class ImagePresenterCallback extends \Nette\Object implements \Nella\NetteAddons\Media\IImagePresenterCallback
{
	/** @var \Nella\NetteAddons\Media\IStorage */
	private $storage;
	/** @var \Nella\NetteAddons\Media\IImageCacheStorage */
	private $cacheStorage;
	
	/**
	 * @param \Nella\NetteAddons\Media\IStorage
	 * @param \Nella\NetteAddons\Media\IImageCacheStorage
	 */
	public function __construct(\Nella\NetteAddons\Media\IStorage $storage, \Nella\NetteAddons\Media\IImageCacheStorage $cacheStorage)
	{
		$this->storage = $storage;
		$this->cacheStorage = $cacheStorage;
	}
	
	/**
	 * @param \Nella\NetteAddons\Media\IImage
	 * @param \Nella\NetteAddons\Media\IImageFormat
	 * @param string
	 * @return \Nette\Application\Responses\FileResponse
	 */
	public function __invoke(IImage $image, IImageFormat $format, $type)
	{
		$path = $this->storage->load($image);
		if (!$path) {
			throw new \Nette\Application\BadRequestException('Image not found', 404);
		}
		
		$img = $this->load($image, $format, $type);
		if ($img instanceof Image) {
			$img->send();
			throw new \Nette\Application\AbortException;
		}
		
		return new \Nette\Application\Responses\FileResponse($img, pathinfo($img, PATHINFO_BASENAME), $this->typeToContentType($type));
	}
	
	/**
	 * @param IImage
	 * @param IImageFormat
	 * @param string
	 */
	protected function load(IImage $image, IImageFormat $format, $type)
	{
		$img = $this->cacheStorage->load($image, $format, $type);
		if (!$img) {
			$this->cacheStorage->save($image, $format, $type, $this->process($image, $format));
			$img = $this->cacheStorage->load($image, $format, $type);
		}
		
		return $img;
	}
	
	/**
	 * @param IImage
	 * @param IImageFormat
	 * @return \Nette\Image
	 */
	final protected function process(IImage $image, IImageFormat $format)
	{
		$img = Image::fromFile($this->storage->load($image));
		$img->resize($format->getWidth(), $format->getHeight(), $format->getFlags());
		if ($format->isCrop()) {
			$img->crop('50%', '50%', $format->getWidth(), $format->getHeight());
		}
		
		if ($format->getWatermark() && $wmimg = $this->storage->load($format->getWatermark())) {
			$watermark = Image::fromFile($wmimg);
			
			switch ($format->getWatermarkPosition()) {
				case IImageFormat::POSITION_BOTTOM_LEFT:
					$left = 0;
					$top = $img->height - $watermark->height;
					break;
				case IImageFormat::POSITION_BOTTOM_RIGHT:
					$left = $img->width - $watermark->width;
					$top = $img->height - $watermark->height;
					break;
				case IImageFormat::POSITION_CENTER;
					$top = ($img->height / 2) - ($watermark->height / 2);
					$left = ($img->width / 2) - ($watermark->width / 2);
					break;
				case IImageFormat::POSITION_TOP_RIGHT:
					$top = 0;
					$left = $img->width - $watermark->width;
					break;
				case IImageFormat::POSITION_TOP_LEFT:
				default:
					$left = $top = 0;
					break;
			}
			if ($left < 0) {
				$left = 0;
			}
			if ($top < 0) {
				$top = 0;
			}
			
			$img->place($watermark, $left, $top, $format->getWatermarkOpacity());
		}
		
		return $img;
	}
	
	/**
	 * @param string
	 * @return string
	 */
	final protected function typeToContentType($type)
	{
		switch($type) {
			case 'gif':
				return 'image/gif';
				break;
			case 'png':
				return 'image/png';
				break;
			default:
				return 'image/jpeg';
				break;
		}
	}
}