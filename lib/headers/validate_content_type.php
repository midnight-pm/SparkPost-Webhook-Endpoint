<?php

	/*
		http://php.net/manual/en/function.function-exists.php
	*/
	if(!function_exists("validate_content_type"))
	{
		/*
			http://php.net/manual/en/functions.user-defined.php
		*/
		function validate_content_type()
		{
			/*
				http://php.net/manual/en/function.php-sapi-name.php
				http://php.net/manual/en/reserved.constants.php#reserved.constants.core
			*/
			if(PHP_SAPI === 'cli')
			{
				/*
					No need for this if this script was called from a command line.
				*/
				return true;
			}
			else
			{
				/*
					http://php.net/manual/en/function.apache-request-headers.php
				*/
				$headers = apache_request_headers();

				if($headers === false)
				{
					throw new Exception ("No headers were present in the request.");

					return false;
				}
				else
				{
					/*
						Change case of array keys to lower.

						http://php.net/manual/en/function.array-change-key-case.php
					*/
					$hdrs_lower = array_change_key_case($headers, CASE_LOWER);

					if($hdrs_lower === false)
					{
						throw new Exception ("No headers were present in the request.");

						return false;
					}
					else
					{
						$content_type = $hdrs_lower["content-type"];

						if($content_type === false)
						{
							throw new Exception ("Request missing \"Content-Type\" header.");

							return false;
						}

						if(empty($content_type))
						{
							throw new Exception ("Request missing \"Content-Type\" header.");

							return false;
						}

						if($content_type !== strtolower(trim("application/json")))
						{
							throw new Exception ("Invalid \"Content-Type\" specified. \"Content-Type\" of \"$content_type\" is not valid.");

							return false;
						}
						else
						{
							return true;
						}
					}
				}
			}
		}
	}

?>