<?php

$debug = false;  // Print outf debugging text on page

//EDIT THIS ONLY

$secret_key = "";

//DO NOT EDIT ANYTHING ELSE

//Turn on errors
if ($debug)
{
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
}

//Make sure the file isn't being accessed at random with no data passed
if (!isset($_POST["max_size"]) || !isset($_POST["age_days"]) || !isset($_POST["path"]) || !isset($_POST["log"]) || !isset($_POST["key"]))
{
	die("Error: Inputs not specified!");
}

if ($_POST[key] != $secret_key)
{
	die("Error : Secret key not correct!");
}

$maxMB = $_POST["max_size"];		//files will be deleted if folder exceeds this size in MB
$deleteAge = $_POST["age_days"];	//Files older then this will be deleted
$path = $_POST["path"];			// Local path to the replay files
$log = false;
if ($_POST["log"] == 1)
{
	$log = true;				//Whether or not to save a log file
	//$logFile = "CleaningSessions.log";
	$logFileHandle = fopen("CleaningSessions.log", "a");
	//fwrite($logFileHandle, "SESSION IP : $_SERVER[REMOTE_ADDR] COMPLETED. \n"); //Logs the incoming IP, this could be used as added security to only allow access to certain people. I may include it later.
	
	if ($_POST[key] != $secret_key)
{
	write($logFileHandle, "ERROR : Secret key not correct! \n");
}
	
}

function dirSize($directory)
{
	$size = 0;
	foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file)
	{
		$size+=$file->getSize();
	}
	return $size;
}
$filesize = dirSize("$path");	//Bytes
$filesizeKB = $filesize/1024;	//KB
$filesizeMB = $filesizeKB/1024;	//MB

if ($debug)
{
	echo "The total filesize of \"$path\" is: ".round($filesizeMB, 2)."MB.<br />";
}

$filesDeleted = 0;
if ($filesizeMB > $maxMB)
{
	if ($log)
	{
		fwrite($logFileHandle, "Session started - ". date(DATE_RFC822). "\n");
		fwrite($logFileHandle, "The total filesize of \"$path\" is: ".round($filesizeMB, 2)." MB.  Maximum $maxMB MB allowed \n");
	}

	if ($handle = opendir("$path"))
	{
		$deleteAgeHours = $deleteAge*24;
		$deleteAgeMinutes = $deleteAgeHours*60;
		$deleteAgeSeconds = $deleteAgeMinutes*60;
		if ($debug)
		{
			echo "Deleting files older then $deleteAgeSeconds seconds<br />---------------------";
		}
		if ($log)
		{
			fwrite($logFileHandle, "Deleting files from more than $deleteAge days ago:\n---------------------\n");
		}
		
		while (false !== ($file = readdir($handle)))
		{
			$filelastmodified = filemtime($path . $file);
			if(($filelastmodified) < (time()-$deleteAgeSeconds))
			{
				if (strcmp($file, "..") != 0 && strcmp($file, ".") != 0)
				{
					if ($debug)
					{
						echo "Deleting ". $path . $file ."<br />";
					}
					if ($log)
					{
						//fwrite($logFileHandle, "Deleting ". $path . $file ." - " . round((filesize($path . $file)/1024)/1024, 4) . "MB - " . date(DATE_RFC822, $filelastmodified). "\n"); // Show file sizes in MB
						fwrite($logFileHandle, "Deleting ". $path . $file ." - " . round(filesize($path . $file)/1024, 2) . "KB - " . date(DATE_RFC822, $filelastmodified). "\n");
					}
					unlink($path . $file);
					$filesDeleted++;
				}
			}
		}
		closedir($handle);
	}
	
	if ($log)
	{
		$filesize = dirSize("$path");
		$filesizeKB = $filesize/1024;
		$filesizeMB = $filesizeKB/1024;
		fwrite($logFileHandle, "---------------------\nTotal files deleted: $filesDeleted - The NEW total filesize of \"$path\" is: ".round($filesizeMB, 2)."MB. \n\n\n");
		fclose($logFileHandle);
	}
}
if ($debug)
{
	echo "---------------------<br />Files deleted: $filesDeleted <br />";

	$filesize = dirSize("$path");
	$filesizeKB = $filesize/1024;
	$filesizeMB = $filesizeKB/1024;

	echo "The NEW total filesize of \"$path\" is: ".round($filesizeMB, 2)."MB.<br />";
}
?>
