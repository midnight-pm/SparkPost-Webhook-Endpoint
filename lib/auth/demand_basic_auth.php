<?php

	/*
		http://php.net/manual/en/function.function-exists.php
	*/
	if(!function_exists("demand_basic_auth"))
	{
		/*
			http://php.net/manual/en/functions.user-defined.php
		*/
		function demand_basic_auth($username, $password)
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
				return "Running from cli SAPI. Skipping client authentication.";
			}
			else
			{
				/*
					http://php.net/manual/en/features.http-auth.php
					https://gist.github.com/rchrd2/c94eb4701da57ce9a0ad4d2b00794131
					http://www.jonasjohn.de/snippets/php/auth.htm

					http://php.net/manual/en/features.http-auth.php#73386
				*/

				if(!isset($_SERVER['PHP_AUTH_USER']))
				{
					/*
						Present authentication request
					*/
					header('Cache-Control: no-cache, must-revalidate, max-age=0');
					header('WWW-Authenticate: Basic realm="Publishing Services -- EASY" charset="UTF-8"');

					/*
						Throw exception on authentication failure.
					*/
					throw New Exception("Unauthorized User, Invalid User, or Invalid Password.");

					return false;
				}
				else
				{
					$auth_user = $_SERVER['PHP_AUTH_USER'];
					$auth_pass = $_SERVER['PHP_AUTH_PW'];

					if(!empty($auth_user))
					{
						if(!is_string($auth_user))
						{
							throw new Exception("Invalid username entry.");

							return false;
						}
					}
					else
					{
						throw new Exception("Invalid username entry.");

						return false;
					}

					if(!empty($auth_pass))
					{
						if(!is_string($auth_pass))
						{
							throw new Exception("Invalid password entry.");

							return false;
						}
					}
					else
					{
						throw new Exception("Invalid password entry.");

						return false;
					}
						

					if($username === $auth_user)
					{
						if($password === $auth_pass)
						{
							/*
								Successful authentication
							*/
							return true;
						}
						else
						{
							throw new Exception("Invalid User or Invalid Password.");

							return false;
						}
					}
					else
					{
						throw new Exception("User is unauthorized or has been expressly prohibited.");

						return false;
					}
				}
			}
		}
	}

?>