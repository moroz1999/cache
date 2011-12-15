<?php

/**
* Copyright (C) 2011 FluxBB (http://fluxbb.org)
* License: LGPL - GNU Lesser General Public License (http://www.gnu.org/licenses/lgpl.html)
*/

if (!defined('PHPCACHE_ROOT'))
	define('PHPCACHE_ROOT', dirname(__FILE__).'/');

require PHPCACHE_ROOT.'Filter.php';

abstract class Flux_Cache extends Flux_Cache_FilterUser
{
	const NOT_FOUND = 'Flux_Cache::NOT_FOUND';
	const DEFAULT_SERIALIZER = 'Serialize';

	public static final function load($type, $args = array(), $serializer_type = false, $serializer_args = array())
	{
		if (!class_exists('Flux_Cache_'.$type))
		{
			if (!file_exists(PHPCACHE_ROOT.'Cache/'.$type.'.php'))
				throw new Exception('Cache type "'.$type.'" does not exist.');

			require PHPCACHE_ROOT.'Cache/'.$type.'.php';
		}

		if ($serializer_type === false)
		{
			$serializer_type = self::DEFAULT_SERIALIZER;
			$serializer_args = array();
		}

		// Instantiate the cache
		$type = 'Flux_Cache_'.$type;
		$cache = new $type($args);

		// If we have a prefix defined, set it
		if (isset($args['prefix']))
			$cache->prefix = $args['prefix'];

		// Add a serialize filter by default as not all caches can handle storing PHP objects
		$cache->addFilter($serializer_type, $serializer_args);

		return $cache;
	}

	public $inserts = 0;
	public $hits = 0;
	public $misses = 0;
	protected $prefix = '';

	public function set($key, $data, $ttl = 0)
	{
		$data = $this->encode($data);
		$this->_set($this->prefix.$key, $data, $ttl);
		$this->inserts++;
	}

	protected abstract function _set($key, $data, $ttl);

	public function get($key)
	{
		$data = $this->_get($this->prefix.$key);
		if ($data === self::NOT_FOUND)
		{
			$this->misses++;
			return self::NOT_FOUND;
		}

		$data = $this->decode($data);

		$this->hits++;
		return $data;
	}

	protected abstract function _get($key);

	public function delete($key)
	{
		$this->_delete($this->prefix.$key);
	}

	protected abstract function _delete($key);

	public abstract function clear();
}
