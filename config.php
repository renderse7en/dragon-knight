<?php // config.php :: Low-level app/database variables.

$dbsettings = Array(
        "server"        => "localhost",     // MySQL server name. (Default: localhost)
        "user"          => "",              // MySQL username.
        "pass"          => "",              // MySQL password.
        "name"          => "",              // MySQL database name.
        "prefix"        => "dk",            // Prefix for table names. (Default: dk)
        "secretword"    => "");             // Secret word used when hashing information for cookies.

// These are used for display purposes only. Technically you could change them, but it's not going to
// do anything special. And I'd prefer if you didn't, just to keep things all nice and standardized.
$version = "1.1.0";
$build = "";

?>