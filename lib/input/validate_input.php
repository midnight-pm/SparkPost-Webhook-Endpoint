<?php

	/*
		http://php.net/manual/en/function.function-exists.php
	*/
	if(!function_exists("validate_input"))
	{
		/*
			http://php.net/manual/en/functions.user-defined.php
		*/
		function validate_input($input)
		{
			/*
				Test input to confirm it is valid JSON.

				json_validate() returns "true" if fine, and returns an error string if it's not.
			*/
			$input_test = json_validate($input);

			if($input_test !== true)
			{
				throw new Exception("$input_test");

				return false;
			}
			else
			{
				/*
					http://php.net/manual/en/function.utf8-encode.php
					-------------------------------------------------
					Take the input, and set it to UTF-8.
				*/
				$input_utf8 = utf8_encode($input);

				/*
					http://php.net/manual/en/function.json-decode.php
					-------------------------------------------------
					Decode the UTF-8-encoded JSON and change it to
					an associative array.
				*/
				$array = json_decode($input_utf8, true);

				/*
					http://php.net/manual/en/function.is-array.php
				*/
				if(is_array($array))
				{
					/*
						Drop the array from memory and return true.
					*/
					$array = NULL;
					unset($array);

					return true;
				}
				else
				{
					throw new Exception("Provided input could not be processed.");

					return false;
				}
			}
		}
	}