<?php

	/*
		http://php.net/manual/en/function.function-exists.php
	*/
	if(!function_exists("read_input"))
	{
		/*
			http://php.net/manual/en/functions.user-defined.php
		*/
		function read_input()
		{
			/*
				http://php.net/manual/en/function.php-sapi-name.php
				http://php.net/manual/en/reserved.constants.php#reserved.constants.core

				--------------

				http://php.net/manual/en/wrappers.php.php
				http://php.net/manual/en/function.file-get-contents.php
			*/
			if(PHP_SAPI === 'cli')
			{
				$input = fopen("php://stdin","r");
				$input = fgets($input);
			}
			else
			{
				$input = file_get_contents("php://input");
			}

			if($input === false)
			{
				throw new Exception("No input.");

				return false;
			}
			else
			{
				if(empty($input))
				{
					throw new Exception("No input.");

					return false;
				}
				else
				{
					return $input;
				}
			}
		}
	}

?>