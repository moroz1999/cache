<?php

/**
 * The Wincache cache stores data using the Windows Cache extension.
 * http://uk2.php.net/manual/en/book.wincache.php
 * 
 * Copyright (C) 2010 Jamie Furness (http://www.jamierf.co.uk)
 * License: http://www.gnu.org/licenses/gpl.html GPL version 3 or higher
 */

class Cache_WinCache extends Cache
{
	const EMULATE_TTL = false;

	/**
	* Initialise a new WinCache cache.
	*/
	public function __construct($config)
	{
		if (!extension_loaded('wincache'))
			throw new Exception('The WinCache cache requires the WinCache extension.');
	}

	protected function _set($key, $data, $ttl)
	{
		if (wincache_ucache_set($key, $data, $ttl) === false)
			throw new Exception('Unable to write wincache cache: '.$key);
	}

	protected function _get($key)
	{
		$success = false;

		$data = wincache_ucache_get($key, $success);
		if ($success === false)
			return self::NOT_FOUND;

		return $data;
	}

	public function delete($key)
	{
		wincache_ucache_delete($key);
	}

	public function clear()
	{
		wincache_ucache_clear();
	}
}
