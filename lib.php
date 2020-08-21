<?php // lib.php :: Common functions used throughout the program.

/**
 * This brings in a shim that allows the (unsafe) usage
 * of old mysql_ functions. It's a very temporary, very
 * undesirable fix for the project.
 * Credit: @dshafik https://github.com/dshafik/php7-mysql-shim
 */
require 'lib/mysql-shim.php';

$starttime = getmicrotime();
$numqueries = 0;
$version = "1.1.11";
$build = "";

function opendb() { // Open database connection.

    include('config.php');
    extract($dbsettings);
    $link = mysql_connect($server, $user, $pass) or die(mysql_error());
    mysql_select_db($name) or die(mysql_error());
    return $link;

}

function doquery($query, $table) { // Something of a tiny little database abstraction layer.
    
    include('config.php');
    global $numqueries;
    $sqlquery = mysql_query(str_replace("{{table}}", $dbsettings["prefix"] . "_" . $table, $query)) or die(mysql_error());
    $numqueries++;
    return $sqlquery;

}

/**
 * Retrieve a template from the template directory
 */
function gettemplate(string $template) {
    $path = 'templates/' . $template . '.html';

    if (!is_readable($path)) {
        throw new Exception('Unable to get template <<' . $template . '>>');
    }

    return file_get_contents($path);
}

/**
 * Parse a template with all the correct data
 */
function parsetemplate($template, $array) {
    return preg_replace_callback(
        '/{{\s*([A-Za-z0-9_-]+)\s*}}/',
        function($match) use ($array) {
            return isset($array[$match[1]]) ? $array[$match[1]] : $match[0];
        },
        $template
    );
}

function getmicrotime() { // Used for timing script operations.

    list($usec, $sec) = explode(" ",microtime()); 
    return ((float)$usec + (float)$sec); 

}

/**
 * Format MySQL datetime stamps into something friendlier
 */
function prettydate($uglydate) {
    $date = new DateTime($uglydate);

    return $date->format('F j, Y');
}

/**
 * Alias for prettydate()
 */
function prettyforumdate($uglydate) {
    prettydate($uglydate);
}

/**
 * Validate the formatting of an email address
 */
function is_email(string $email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Ensure no XSS attacks can occur by sanitizing strings
 * However, this doesn't prevent some JS eval() attacks
 */
function makesafe(string $string)
{
    return htmlentities($string, ENT_QUOTES);
}

function admindisplay($content, $title) { // Finalize page and output to browser.
    
    global $numqueries, $userrow, $controlrow, $starttime, $version, $build;
    if (!isset($controlrow)) {
        $controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
        $controlrow = mysql_fetch_array($controlquery);
    }
    
    $template = gettemplate("admin");
    
    // Make page tags for XHTML validation.
    $xml = "<!DOCTYPE html>\n"
    . "<html lang=\"en\">\n";

    $finalarray = array(
        "title"=>$title,
        "content"=>$content,
        "totaltime"=>round(getmicrotime() - $starttime, 4),
        "numqueries"=>$numqueries,
        "version"=>$version,
        "build"=>$build);
    $page = parsetemplate($template, $finalarray);
    $page = $xml . $page;

    if ($controlrow["compression"] == 1) { ob_start("ob_gzhandler"); }
    echo $page;
    die();
    
}

function display($content, $title, $topnav=true, $leftnav=true, $rightnav=true, $badstart=false) { // Finalize page and output to browser.
    
    global $numqueries, $userrow, $controlrow, $version, $build;
    if (!isset($controlrow)) {
        $controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
        $controlrow = mysql_fetch_array($controlquery);
    }
    if ($badstart == false) { global $starttime; } else { $starttime = $badstart; }
    
    // Make page tags for XHTML validation.
    $xml = "<!DOCTYPE html>\n"
    . "<html lang=\"en\">\n";

    $template = gettemplate("primary");
    
    if ($rightnav == true) { $rightnav = gettemplate("rightnav"); } else { $rightnav = ""; }
    if ($leftnav == true) { $leftnav = gettemplate("leftnav"); } else { $leftnav = ""; }
    if ($topnav == true) {
        $topnav = "<a href=\"login.php?do=logout\"><img src=\"images/button_logout.gif\" alt=\"Log Out\" title=\"Log Out\" border=\"0\" /></a> <a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Help\" title=\"Help\" border=\"0\" /></a>";
    } else {
        $topnav = "<a href=\"login.php?do=login\"><img src=\"images/button_login.gif\" alt=\"Log In\" title=\"Log In\" border=\"0\" /></a> <a href=\"users.php?do=register\"><img src=\"images/button_register.gif\" alt=\"Register\" title=\"Register\" border=\"0\" /></a> <a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Help\" title=\"Help\" border=\"0\" /></a>";
    }
    
    if (isset($userrow)) {
        
        // Get userrow again, in case something has been updated.
        $userquery = doquery("SELECT * FROM {{table}} WHERE id='".$userrow["id"]."' LIMIT 1", "users");
        unset($userrow);
        $userrow = mysql_fetch_array($userquery);
        
        // Current town name.
        if ($userrow["currentaction"] == "In Town") {
            $townquery = doquery("SELECT * FROM {{table}} WHERE latitude='".$userrow["latitude"]."' AND longitude='".$userrow["longitude"]."' LIMIT 1", "towns");
            $townrow = mysql_fetch_array($townquery);
            $userrow["currenttown"] = "Welcome to <b>".$townrow["name"]."</b>.<br /><br />";
        } else {
            $userrow["currenttown"] = "";
        }
        
        if ($controlrow["forumtype"] == 0) { $userrow["forumslink"] = ""; }
        elseif ($controlrow["forumtype"] == 1) { $userrow["forumslink"] = "<a href=\"forum.php\">Forum</a><br />"; }
        elseif ($controlrow["forumtype"] == 2) { $userrow["forumslink"] = "<a href=\"".$controlrow["forumaddress"]."\">Forum</a><br />"; }
        
        // Format various userrow stuffs...
        if ($userrow["latitude"] < 0) { $userrow["latitude"] = $userrow["latitude"] * -1 . "S"; } else { $userrow["latitude"] .= "N"; }
        if ($userrow["longitude"] < 0) { $userrow["longitude"] = $userrow["longitude"] * -1 . "W"; } else { $userrow["longitude"] .= "E"; }
        $userrow["experience"] = number_format($userrow["experience"]);
        $userrow["gold"] = number_format($userrow["gold"]);
        if ($userrow["authlevel"] == 1) { $userrow["adminlink"] = "<a href=\"admin.php\">Admin</a><br />"; } else { $userrow["adminlink"] = ""; }
        
        // HP/MP/TP bars.
        $stathp = ceil($userrow["currenthp"] / $userrow["maxhp"] * 100);
        if ($userrow["maxmp"] != 0) { $statmp = ceil($userrow["currentmp"] / $userrow["maxmp"] * 100); } else { $statmp = 0; }
        $stattp = ceil($userrow["currenttp"] / $userrow["maxtp"] * 100);
        $stattable = "<table width=\"100\"><tr><td width=\"33%\">\n";
        $stattable .= "<table cellspacing=\"0\" cellpadding=\"0\"><tr><td style=\"padding:0px; width:15px; height:100px; border:solid 1px black; vertical-align:bottom;\">\n";
        if ($stathp >= 66) { $stattable .= "<div style=\"padding:0px; height:".$stathp."px; border-top:solid 1px black; background-image:url(images/bars_green.gif);\"><img src=\"images/bars_green.gif\" alt=\"\" /></div>"; }
        if ($stathp < 66 && $stathp >= 33) { $stattable .= "<div style=\"padding:0px; height:".$stathp."px; border-top:solid 1px black; background-image:url(images/bars_yellow.gif);\"><img src=\"images/bars_yellow.gif\" alt=\"\" /></div>"; }
        if ($stathp < 33) { $stattable .= "<div style=\"padding:0px; height:".$stathp."px; border-top:solid 1px black; background-image:url(images/bars_red.gif);\"><img src=\"images/bars_red.gif\" alt=\"\" /></div>"; }
        $stattable .= "</td></tr></table></td><td width=\"33%\">\n";
        $stattable .= "<table cellspacing=\"0\" cellpadding=\"0\"><tr><td style=\"padding:0px; width:15px; height:100px; border:solid 1px black; vertical-align:bottom;\">\n";
        if ($statmp >= 66) { $stattable .= "<div style=\"padding:0px; height:".$statmp."px; border-top:solid 1px black; background-image:url(images/bars_green.gif);\"><img src=\"images/bars_green.gif\" alt=\"\" /></div>"; }
        if ($statmp < 66 && $statmp >= 33) { $stattable .= "<div style=\"padding:0px; height:".$statmp."px; border-top:solid 1px black; background-image:url(images/bars_yellow.gif);\"><img src=\"images/bars_yellow.gif\" alt=\"\" /></div>"; }
        if ($statmp < 33) { $stattable .= "<div style=\"padding:0px; height:".$statmp."px; border-top:solid 1px black; background-image:url(images/bars_red.gif);\"><img src=\"images/bars_red.gif\" alt=\"\" /></div>"; }
        $stattable .= "</td></tr></table></td><td width=\"33%\">\n";
        $stattable .= "<table cellspacing=\"0\" cellpadding=\"0\"><tr><td style=\"padding:0px; width:15px; height:100px; border:solid 1px black; vertical-align:bottom;\">\n";
        if ($stattp >= 66) { $stattable .= "<div style=\"padding:0px; height:".$stattp."px; border-top:solid 1px black; background-image:url(images/bars_green.gif);\"><img src=\"images/bars_green.gif\" alt=\"\" /></div>"; }
        if ($stattp < 66 && $stattp >= 33) { $stattable .= "<div style=\"padding:0px; height:".$stattp."px; border-top:solid 1px black; background-image:url(images/bars_yellow.gif);\"><img src=\"images/bars_yellow.gif\" alt=\"\" /></div>"; }
        if ($stattp < 33) { $stattable .= "<div style=\"padding:0px; height:".$stattp."px; border-top:solid 1px black; background-image:url(images/bars_red.gif);\"><img src=\"images/bars_red.gif\" alt=\"\" /></div>"; }
        $stattable .= "</td></tr></table></td>\n";
        $stattable .= "</tr><tr><td>HP</td><td>MP</td><td>TP</td></tr></table>\n";
        $userrow["statbars"] = $stattable;
        
        // Now make numbers stand out if they're low.
        if ($userrow["currenthp"] <= ($userrow["maxhp"]/5)) { $userrow["currenthp"] = "<blink><span class=\"highlight\"><b>*".$userrow["currenthp"]."*</b></span></blink>"; }
        if ($userrow["currentmp"] <= ($userrow["maxmp"]/5)) { $userrow["currentmp"] = "<blink><span class=\"highlight\"><b>*".$userrow["currentmp"]."*</b></span></blink>"; }

        $spellquery = doquery("SELECT id,name,type FROM {{table}}","spells");
        $userspells = explode(",",$userrow["spells"]);
        $userrow["magiclist"] = "";
        while ($spellrow = mysql_fetch_array($spellquery)) {
            $spell = false;
            foreach($userspells as $a => $b) {
                if ($b == $spellrow["id"] && $spellrow["type"] == 1) { $spell = true; }
            }
            if ($spell == true) {
                $userrow["magiclist"] .= "<a href=\"index.php?do=spell:".$spellrow["id"]."\">".$spellrow["name"]."</a><br />";
            }
        }
        if ($userrow["magiclist"] == "") { $userrow["magiclist"] = "None"; }
        
        // Travel To list.
        $townslist = explode(",",$userrow["towns"]);
        $townquery2 = doquery("SELECT * FROM {{table}} ORDER BY id", "towns");
        $userrow["townslist"] = "";
        while ($townrow2 = mysql_fetch_array($townquery2)) {
            $town = false;
            foreach($townslist as $a => $b) {
                if ($b == $townrow2["id"]) { $town = true; }
            }
            if ($town == true) { 
                $userrow["townslist"] .= "<a href=\"index.php?do=gotown:".$townrow2["id"]."\">".$townrow2["name"]."</a><br />\n"; 
            }
        }
        
    } else {
        $userrow = array();
    }

    $finalarray = array(
        "dkgamename"=>$controlrow["gamename"],
        "title"=>$title,
        "content"=>$content,
        "rightnav"=>parsetemplate($rightnav,$userrow),
        "leftnav"=>parsetemplate($leftnav,$userrow),
        "topnav"=>$topnav,
        "totaltime"=>round(getmicrotime() - $starttime, 4),
        "numqueries"=>$numqueries,
        "version"=>$version,
        "build"=>$build);
    $page = parsetemplate($template, $finalarray);
    $page = $xml . $page;
    
    echo $page;
    die();
}