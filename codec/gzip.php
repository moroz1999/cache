<?php

class Codec_GZip
{
	private $level;

	public function __construct($config)
	{
		$this->level = $config['level'];
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
