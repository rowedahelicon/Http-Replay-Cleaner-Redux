# Http-Replay-Cleaner-Redux
[TF2] Deletes old replay files based on specified settings

Install Instructions:

    Upload the php script to a folder above where the replay files are stored.
    Chmod the folder with php script and the folders that have the replay files to 777.
    Edit the replaycleaner.php file to add in your secret key. You only need to edit the top line.
    Code:

    //EDIT THIS ONLY

    $secret_key = "";

    //DO NOT EDIT ANYTHING ELSE

    Install the plugin
    When loaded, the plugin will create a config file at tf/cfg/sourcemod/HTTPReplayCleanup.cfg
    Set the above cvars in the config file.
    Restart the server or change maps to reload the config.


Example Setup:

        Your replay files are saved to http://yoursite.com/tf2/replays/server1/.
        You could drop the php file in the /tf2/ folder and name it "cleanup.php".
        You would then set sm_replaycleanup_link to "www.yoursite.com/tf2/cleanup.php".
        You would then set sm_replaycleanup_localpath to "replays/server1/"



Notes:

    Files are deleted at the end of each round.
    Example Logfile:
    Code:

    Session started - Fri, 27 May 2011 19:14:44 CDT
    The total filesize of "teamfortress/server1/" is: 500.89 MB.  Maximum 500 MB allowed 
    Deleting files from more than 1 days ago:
    ---------------------
    Deleting teamfortress/server1/20110526-201000-ctf_2fort_part_15.block - 83.79KB - Thu, 26 May 2011 19:14:08 CDT
    Deleting teamfortress/server1/20110526-201000-ctf_2fort_part_11.block - 97.25KB - Thu, 26 May 2011 19:13:08 CDT
    Deleting teamfortress/server1/20110526-201000-ctf_2fort_part_10.block - 84.62KB - Thu, 26 May 2011 19:12:53 CDT
    Deleting teamfortress/server1/20110526-201000-ctf_2fort_part_12.block - 83KB - Thu, 26 May 2011 19:13:22 CDT
    -Snip-
    Deleting teamfortress/server1/20110526-201000-ctf_2fort_part_13.block - 90.48KB - Thu, 26 May 2011 19:13:38 CDT
    Deleting teamfortress/server1/20110526-201000-ctf_2fort_part_17.block - 84.61KB - Thu, 26 May 2011 19:14:37 CDT
    Deleting teamfortress/server1/20110526-201000-ctf_2fort_part_16.block - 85.87KB - Thu, 26 May 2011 19:14:23 CDT
    Deleting teamfortress/server1/20110526-201000-ctf_2fort_part_14.block - 98.27KB - Thu, 26 May 2011 19:13:53 CDT
    ---------------------
    Total files deleted: 788 - The NEW total filesize of "teamfortress/server1/" is: 300.2MB.



Version History:

    V1.0.0
        Initial Release
    V1.1.1
        Fixed a potential infinite loop if the round ends and there are no human players.
        Added a cvar to make the plugin prune replays via a timer
    V2.0.0
        Resigned to use a Curl post rather than an Motd open
        Added the cvar sm_replaycleanup_secretkey which needs to be set on both ends in order to maintain security

