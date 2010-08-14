<?php

class Cache_File extends Cache
{
	const SUFFIX = '.cache';

	private $dir;

	public function __construct($config)
	{
		$this->dir = $config['dir'];
		if ((!is_dir($this->dir) && !mkdir($this->dir, 0777, true)) || !is_writable($this->dir))
			throw new Exception('Unable to write to cache dir: '.$this->dir);
	}

	private function key($key)
	{
		return sha1($key);
	}

	protected function _set($key, $data)
	{
		if (@file_put_contents($this->dir.$this->key($key).self::SUFFIX, $data) === false)
			throw new Exception('Unable to write file cache: '.$key);
	}

	protected function _get($key)
	{
		$data = @file_get_contents($this->dir.$this->key($key).self::SUFFIX);
		if ($data === false)
			return self::NOT_FOUND;

		return $data;
	}

	public function delete($key)
	{
		@unlink($this->dir.$this->key($key).self::SUFFIX);
	}
}
