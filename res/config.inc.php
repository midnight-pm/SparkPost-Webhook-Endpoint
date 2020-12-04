<?php

	$config = array(

		/*
			Enable/Disable Debug Mode
		*/
		"debug_mode" => false

		/*
			Authentication

			In order to not just accept data from *anyone*, Basic Authentication is present.
			Requests must successfully authenticate using Basic Authentication in order to proceed.
			Failed attempts to authenticate will result in a "401 Unauthorized" error.

			No reattempts are allowed during a single request. One auth attempt per request.
		*/
		, "authentication" => array(

				/*
					Specify a username for requests to use.
				*/
				"username" => "{{USERNAME}}"

				/*
					Specify a password for requests to use.
				*/
				, "password" => "{{PASSWORD}}"
			)

		/*
			Data Storage Parameters
		*/
		, "storage" => array(

				/*
					Define a timezone to use for date related functions.
					http://php.net/manual/en/timezones.php
				*/
				"timezone" => "America/New_York"

				/*
					Where will received data be stored?
					In some cases, this may not need to be changed.
					By default, it is set to create a directory named "received" in the script's location.
					If this script does not have permissions to do so, a directory will need to be created for it.
					This script will require permissions to write into that directory.
				*/
				, "directory" => "/data/sparkpost/events/webhooks/received"

				/*
					This identifies the prefix of the file name for stored json files.
				*/
				, "prefix" => "sparkpost"

				/*
					This controls the permissions of the data storage directory.

					This will need to be defined using NUMERIC notation.
					In most cases, 0755 should suffice. Adjust accordingly as necessary.

					For further information, reference: 
					https://en.wikipedia.org/w/index.php?title=File_system_permissions&oldid=808567801#Numeric_notation

					Note: This setting has no effect when executed under Windows, but will still *need* to be set.
				*/
				, "permissions" => 0755
			)

		/*
			Logging Parameters
		*/
		, "logger" => array(

				/*
					Define a timezone to use for date related functions.
					http://php.net/manual/en/timezones.php
				*/
				"timezone" => "America/New_York"

				/*
					Where will log files be stored?
					In some cases, this may not need to be changed.
					By default, it is set to create a directory named "logs" in the script's location.
					If this script does not have permissions to do so, a directory will need to be created for it.
					This script will require permissions to write into that directory.
				*/
				, "log_dir_path" => "/data/sparkpost/events/webhooks/logs/endpoint"

				/*
					This controls the permissions of the log directory.

					This will need to be defined using NUMERIC notation.
					In most cases, 0755 should suffice. Adjust accordingly as necessary.

					For further information, reference: 
					https://en.wikipedia.org/w/index.php?title=File_system_permissions&oldid=808567801#Numeric_notation

					Note: This setting has no effect when executed under Windows, but will still *need* to be set.
				*/
				, "log_dir_permissions" => 0755

				/*
					This identifies the prefix of the file name for the log files.
				*/
				, "log_file_name_prexfix" => "sparkpost_wh"
			)
	);

?>