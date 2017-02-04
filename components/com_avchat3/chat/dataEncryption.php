<?php
	class DataEncryption
	{
			var $key = NULL;
			var $iv = NULL;
			var $iv_size = NULL;
	 
			function DataEncryption()
			{
					$this->init("QEejmTwH4CgOlZJaDsjl7yIIoz2X82pr");
			}
	 
			function init($key = "")
			{
					$this->key = ($key != "") ? $key : "";
	 
					$this->algorithm = MCRYPT_RIJNDAEL_128;
					$this->mode = MCRYPT_MODE_CBC;
	 
					$this->iv = 'W2BMXm8120FSmcgm';
			}
	 
			function encrypt($data)
			{				 
				return base64_encode(mcrypt_encrypt($this->algorithm, $this->key, $data, $this->mode, $this->iv));
			}
	 
	}
?>