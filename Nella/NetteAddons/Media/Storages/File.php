<?php
/**
 * This file is part of the Nella Framework.
 *
 * Copyright (c) 2006, 2011 Patrik Votoček (http://patrik.votocek.cz)
 *
 * This source file is subject to the GNU Lesser General Public License. For more information please see http://nella-project.org
 */

namespace Nella\NetteAddons\Media\Storages;

use Nella\NetteAddons\Media\IFile;

/**
 * File storage
 *
 * @author	Patrik Votoček
 */
class File extends \Nette\Object implements \Nella\NetteAddons\Media\IStorage
{
	/** @var string */
	private $dir;
	
	/**
	 * @param string
	 */
	public function __construct($dir)
	{
		if (!file_exists($dir)) {
			if (!@mkdir($dir, 0777, TRUE)) {
				throw new \Nette\InvalidStateException("Creating directory '$dir' failed");
			}
		} elseif (!is_writable($dir)) {
			throw new \Nette\InvalidStateException("Directory '$dir' is not writable");
		}
		$this->dir = $dir;
	}
	
	/**
	 * @param IFile
	 * @return string full path
	 */
	public function load(IFile $file)
	{
		$path = $this->dir . '/' . $file->getPath();
		return file_exists($path) ? $path : NULL;
	}
	
	/**
	 * @param IFile
	 * @param string|\Nette\Http\FileUpload temp file full path ir file upload
	 * @param bool	remove source file?
	 */
	public function save(IFile $file, $from, $removeSource = FALSE)
	{
		$path = $this->dir . '/' . $file->getPath();
		if ($from instanceof \Nette\Http\FileUpload) {
			$from->move($path);
		} elseif (@copy($from, $path) && $removeSource) {
			@unlink($from);
		}
	}
	
	/**
	 * @param IFile
	 */
	public function remove(IFile $file)
	{
		$path = $this->load($file);
		if ($path) {
			@unlink($path);
		}
	}
}