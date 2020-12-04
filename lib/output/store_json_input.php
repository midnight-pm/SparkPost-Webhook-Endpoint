<?php

	/*
		http://php.net/manual/en/function.function-exists.php
	*/
	if(!function_exists("store_json_input"))
	{
		function store_json_input($input, $timezone, $storage_path, $storage_prefix, $storage_perms)
		{
			/*
				http://php.net/manual/en/function.is-string.php
			*/
			if(!is_string($input))
			{
				throw new Exception ("Provided input is invalid. - [store_json_input]");

				return false;
			}
			else
			{
				/*
					http://php.net/manual/en/function.empty.php
				*/
				if(empty($input))
				{
					throw new Exception ("Provided input is empty. - [store_json_input]");

					return false;
				}
			}

			if(!is_string($storage_path))
			{
				throw new Exception ("Provided storage path is invalid. - [store_json_input]");

				return false;
			}
			else
			{
				if(empty($storage_path))
				{
					throw new Exception ("Provided storage path parameter is empty. - [store_json_input]");

					return false;
				}
				else
				{
					if(!file_exists($storage_path))
					{
						trigger_error("The directory \"$storage_path\" does not exist. - [store_json_input]", E_USER_WARNING);
						trigger_error("Attempting to create the directory \"$storage_path\". - [store_json_input]", E_USER_NOTICE);

						/*
							http://php.net/manual/en/function.mkdir.php
						*/
						if(!mkdir($storage_path, $storage_perms, true))
						{
							throw new Exception ("Could not create the directory \"$storage_path\". - [store_json_input]");

							return false;
						}
					}
				}
			}

			if(!is_string($timezone))
			{
				trigger_error("Configuration error. Verify that the timezone parameter has been properly configured. - [store_json_input]", E_USER_WARNING);

				return false;
			}
			else
			{
				/*
					http://php.net/manual/en/function.empty.php
				*/
				if(empty($timezone))
				{
					trigger_error("Configuration error. Verify that the timezone parameter has been properly configured. - [store_json_input]", E_USER_WARNING);

					return false;
				}
			}

			/*
				http://php.net/manual/en/function.date-default-timezone-set.php
			*/
			date_default_timezone_set($timezone);

			/*
				String: "1970-01-01T000000.000000+0000"

				Bash: $(date +%Y"-"date +%m)"-"$(date +%d)"T"$(date +%H)$(date +%M)$(date +%S)"."$(date +%N)$(date +%z);
				PHP: date("Y-m-d\THis\.uO");

				----------
				http://php.net/manual/en/function.microtime.php
				http://php.net/manual/en/function.date.php
				http://php.net/manual/en/datetime.construct.php
			*/
			$time = microtime(true);
			$micro = sprintf("%06d",($time - floor($time)) * 1000000);
			$date = new DateTime(date("Y-m-d H:i:s\." . $micro, $time));
			$file_date_stamp = $date->format("Y-m-d\THis\.uO");

			/*
				Append a trailing slash to the $storage_path, fill out the
				file name for the json object to store, and then specify the
				full path to the expected file.
			*/
			$file_name = $storage_prefix . "_" . $file_date_stamp . ".json";

			$full_path = $storage_path . "/" . $file_name;

			/*
				Check whether or not the storage path already exists.
				If it does not, attempt to create it.
			*/
			if(!file_exists($storage_path))
			{
				trigger_error("The directory \"$storage_path\" does not exist. - [store_json_input]", E_USER_WARNING);
				trigger_error("Attempting to create the directory \"$storage_path\". - [store_json_input]", E_USER_NOTICE);

				if(!mkdir($storage_path, $storage_perms, true))
				{
					throw new Exception ("Failed to create directory \"$storage_path\". - [store_json_input]");

					return false;
				}
			}

			/*
				http://php.net/manual/en/function.file-put-contents.php
			*/
			if(file_put_contents($full_path, $input, FILE_APPEND | LOCK_EX) === false)
			{
				throw new Exception ("An error occurred when attempting to write data to \"$storage_path\". - [store_json_input]");

				return false;
			}
			else
			{
				return true;
			}
		}
	}

?>