<?php

	/*
		If the request was not made from the CLI, and the request method was not POST, full stop.

		http://php.net/manual/en/function.php-sapi-name.php
	*/
	if(PHP_SAPI !== "cli" && $_SERVER["REQUEST_METHOD"] !== "POST")
	{
		/*
			Return "405 Method Not Allowed" to client.
			http://php.net/http-response-code
		*/
		http_response_code(405);

		print "Method " . $_SERVER["REQUEST_METHOD"] . " not supported.";

		exit();
	}

	/*
		Define named constants.
		http://php.net/manual/en/function.define.php
	*/

	define('BASE_PATH', dirname(__FILE__));
	define('RES_PATH', BASE_PATH . "/res");
	define('LIB_PATH', BASE_PATH . "/lib");
	define('CLASS_PATH', BASE_PATH . "/cls");

	require(RES_PATH . "/config.inc.php");
	require(CLASS_PATH . "/logger.class.php");
	require(LIB_PATH . "/auth/demand_basic_auth.php");
	require(LIB_PATH . "/error/exception_error_log.php");
	require(LIB_PATH . "/error/exception_error_output.php");
	require(LIB_PATH . "/error/exception_handler.php");
	require(LIB_PATH . "/guid/generate_guid.php");
	require(LIB_PATH . "/headers/get_request_headers.php");
	require(LIB_PATH . "/headers/validate_content_type.php");
	require(LIB_PATH . "/input/read_input.php");
	require(LIB_PATH . "/input/validate_input.php");
	require(LIB_PATH . "/validation/json_validate.php");
	require(LIB_PATH . "/output/store_json_input.php");

	/*
		Open logger
	*/
	try
	{
		$log = new logger();
	}
	catch (Exception $e)
	{
		/*
			http://php.net/manual/en/function.php-sapi-name.php
		*/
		if(PHP_SAPI !== "cli")
		{
			/*
				Return "500 Internal Server error" to client.
			*/
			http_response_code(500);

			$log->write("Returned HTTP status code of 500 to client.");

			print "An error occurred. If this message continues to persist, please contact support." . PHP_EOL;
		}

		if($config["debug_mode"] === true)
		{
			exception_error_output($e);
		}
	}

	/*
		Generate a GUID.
	*/
	$guid = generate_guid();

	/*
		Request client authentication.
	*/
	try
	{
		$log->write("[$guid] Storing request headers.");
		$log->write("[$guid] " . get_request_headers());
	}
	catch (Exception $e)
	{
		if(PHP_SAPI !== "cli")
		{
			/*
				Return "500 Internal Server Error" to client.
			*/
			http_response_code(500);

			$log->write("[$guid] Returned HTTP status code of 500 to client.");
		}

		print "[$guid] " . $e->getMessage() . PHP_EOL;

		exception_error_log($e, $guid);
		if($config["debug_mode"] === true)
		{
			exception_error_output($e, $guid);
		}

		exit();
	}

	/*
		Request client authentication.
	*/
	try
	{
		$log->write("[$guid] Requesting client authentication.");
		demand_basic_auth($config["authentication"]["username"], $config["authentication"]["password"]);
	}
	catch (Exception $e)
	{
		if(PHP_SAPI !== "cli")
		{
			/*
				Return "401 Unauthorized" to client. (RFC 7235)
			*/
			http_response_code(401);

			$log->write("[$guid] Returned HTTP status code of 401 to client.");
		}

		print "[$guid] " . $e->getMessage() . PHP_EOL;

		exception_error_log($e, $guid);
		if($config["debug_mode"] === true)
		{
			exception_error_output($e, $guid);
		}

		exit();
	}

	/*
		Check the content type of the request.
	*/
	try
	{
		$log->write("[$guid] Checking Request Header.");
		validate_content_type();
	}
	catch (Exception $e)
	{
		if(PHP_SAPI !== "cli")
		{
			/*
				Return "400 Bad Request" to client.
			*/
			http_response_code(400);

			$log->write("[$guid] Returned HTTP status code of 400 to client.");
		}

		print "[$guid] " . $e->getMessage() . PHP_EOL;

		exception_error_log($e, $guid);
		if($config["debug_mode"] === true)
		{
			exception_error_output($e, $guid);
		}

		exit();
	}

	/*
		Take the input.
	*/
	try
	{
		$log->write("[$guid] Reading input.");
		$input = read_input();
	}
	catch (Exception $e)
	{
		if(PHP_SAPI !== "cli")
		{
			/*
				Return "422 Unprocessable Entity" to client. (WebDAV; RFC 4918)
			*/
			http_response_code(422);

			$log->write("[$guid] Returned HTTP status code of 422 to client.");
		}

		print "[$guid] Provided input was empty." . PHP_EOL;

		exception_error_log($e, $guid);
		if($config["debug_mode"] === true)
		{
			exception_error_output($e, $guid);
		}

		exit();
	}

	/*
		Validate the input.
	*/
	try
	{
		$log->write("[$guid] Validating input.");
		$validation = validate_input($input);
	}
	catch (Exception $e)
	{
		if(PHP_SAPI !== "cli")
		{
			/*
				Return "422 Unprocessable Entity" to client. (WebDAV; RFC 4918)
			*/
			http_response_code(422);

			$log->write("[$guid] Returned HTTP status code of 422 to client.");
		}

		print "[$guid] $validation." . PHP_EOL;

		exception_error_log($e, $guid);
		if($config["debug_mode"] === true)
		{
			exception_error_output($e, $guid);
		}

		exit();
	}

	/*
		Store the input.
	*/
	try
	{
		$log->write("[$guid] Storing input.");
		$store_input = store_json_input($input, $config["storage"]["timezone"], $config["storage"]["directory"], $config["storage"]["prefix"], $config["storage"]["permissions"]);
	}
	catch (Exception $e)
	{
		if(PHP_SAPI !== "cli")
		{
			/*
				Return "500 Internal Server error" to client.
			*/
			http_response_code(500);

			$log->write("[$guid] Returned HTTP status code of 500 to client.");
		}

		print "An error occurred. Please reference the ID \"$guid\" when contacting support." . PHP_EOL;

		exception_error_log($e, $guid);
		if($config["debug_mode"] === true)
		{
			exception_error_output($e, $guid);
		}

		exit();
	}
	
	if(PHP_SAPI !== "cli")
	{
		/*
			Return "200 OK" to client.
		*/
		http_response_code(200);

		$log->write("[$guid] Returned HTTP status code of 200 to client.");
	}

	$log->close();
	exit();

?>