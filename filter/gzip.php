<?php

/**
 * The GZip filter compresses data using GZip.
 * http://uk2.php.net/manual/en/book.zlib.php
 * 
 * Copyright (C) 2010 Jamie Furness (http://www.jamierf.co.uk)
 * License: http://www.gnu.org/licenses/gpl.html GPL version 3 or higher
 */

class Filter_GZip implements Filter
{
	const DEFAULT_LEVEL = 4;

	private $level;

	/**
	* Initialise a new GZip filter.
	* 
	* @param	level	The compression level to use, ranging from 0-9. Defaults to 4
	*/
	public function __construct($config)
	{
		if (!extension_loaded('zlib'))
			throw new Exception('The GZip filter requires the Zlib extension.');

		$this->level = isset($config['level']) ? $config['level'] : self::DEFAULT_LEVEL;
	}

	public function encode($data)
	{
		return gzdeflate($data, $this->level);
	}

	public function decode($data)
	{
		return gzinflate($data);
	}
}
