<?php // install.php :: creates/populates database tables on a new installation.

include('config.php');
include('lib.php');
$link = opendb();
$start = getmicrotime();

if (isset($_GET["page"])) {
    $page = $_GET["page"];
    if ($page == 2) { second(); }
    elseif ($page == 3) { third(); }
    elseif ($page == 4) { fourth(); }
    elseif ($page == 5) { fifth(); }
    else { first(); }
} else { first(); }

// Thanks to Predrag Supurovic from php.net for this function!
function dobatch ($p_query) {
  $query_split = preg_split ("/[;]+/", $p_query);
  foreach ($query_split as $command_line) {
   $command_line = trim($command_line);
   if ($command_line != '') {
     $query_result = mysql_query($command_line);
     if ($query_result == 0) {
       break;
     };
   };
  };
  return $query_result;
}

function first() { // First page - show warnings and gather info.
    
$page = <<<END
<html>
<head>
<title>Dragon Knight Installation</title>
</head>
<body>
<b>Dragon Knight Installation: Page One</b><br /><br />
<b>NOTE:</b> Please ensure you have filled in the correct values in config.php before continuing. Installation will fail if these values are not correct. Also, the MySQL database needs to already exist. This installer script will take care of setting up its structure and content, but the database itself must already exist on your MySQL server before the installer will work.<br /><br />
Installation for Dragon Knight is a simple two-step process: set up the database tables, then create the admin user. After that, you're done.<br /><br />
You have two options for database setup: complete or partial.
<ul>
<li /><b>Complete Setup</b> includes table structure and all default data (items, drops, monsters, levels, spells, towns) - after complete setup, the game is totally ready to run.
<li /><b>Partial Setup</b> only creates the table structure, it does not populate the tables - use this if you are going to be creating and importing your own customized game data later.
</ul>
Click the appropriate button below for your preferred installation method.<br /><br />
<form action="install.php?page=2" method="post">
<input type="submit" name="complete" value="Complete Setup" /><br /> - OR - <br /><input type="submit" name="partial" value="Partial Setup" />
</form>
</body>
</html>
END;
echo $page;
die();
  
}

function second() { // Second page - set up the database tables.
    
    global $dbsettings;
    echo "<html><head><title>Dragon Knight Installation</title></head><body><b>Dragon Knight Installation: Page Two</b><br /><br />";
    $prefix = $dbsettings["prefix"];
    $babble = $prefix . "_babble";
    $control = $prefix . "_control";
    $drops = $prefix . "_drops";
    $forum = $prefix . "_forum";
    $items = $prefix . "_items";
    $levels = $prefix . "_levels";
    $monsters = $prefix . "_monsters";
    $news = $prefix . "_news";
    $spells = $prefix . "_spells";
    $towns = $prefix . "_towns";
    $users = $prefix . "_users";
    
    if (isset($_POST["complete"])) { $full = true; } else { $full = false; }
    
$query = <<<END
CREATE TABLE `$babble` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `posttime` time NOT NULL default '00:00:00',
  `author` varchar(30) NOT NULL default '',
  `babble` varchar(120) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
END;
if (dobatch($query) == 1) { echo "Babble Box table created.<br />"; } else { echo "Error creating Babble Box table."; }
unset($query);

$query = <<<END
CREATE TABLE `$control` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `gamename` varchar(50) NOT NULL default '',
  `gamesize` smallint(5) unsigned NOT NULL default '0',
  `gameopen` tinyint(3) unsigned NOT NULL default '0',
  `gameurl` varchar(200) NOT NULL default '',
  `adminemail` varchar(100) NOT NULL default '',
  `forumtype` tinyint(3) unsigned NOT NULL default '0',
  `forumaddress` varchar(200) NOT NULL default '',
  `class1name` varchar(50) NOT NULL default '',
  `class2name` varchar(50) NOT NULL default '',
  `class3name` varchar(50) NOT NULL default '',
  `diff1name` varchar(50) NOT NULL default '',
  `diff1mod` float unsigned NOT NULL default '0',
  `diff2name` varchar(50) NOT NULL default '',
  `diff2mod` float unsigned NOT NULL default '0',
  `diff3name` varchar(50) NOT NULL default '',
  `diff3mod` float unsigned NOT NULL default '0',
  `compression` tinyint(3) unsigned NOT NULL default '0',
  `verifyemail` tinyint(3) unsigned NOT NULL default '0',
  `shownews` tinyint(3) unsigned NOT NULL default '0',
  `showbabble` tinyint(3) unsigned NOT NULL default '0',
  `showonline` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

END;
if (dobatch($query) == 1) { echo "Control table created.<br />"; } else { echo "Error creating Control table."; }
unset($query);

$query = <<<END
INSERT INTO `$control` VALUES (1, 'Dragon Knight', 250, 1, '', '', 1, '', 'Mage', 'Warrior', 'Paladin', 'Easy', '1', 'Medium', '1.2', 'Hard', '1.5', 1, 1, 1, 1, 1);
END;
if (dobatch($query) == 1) { echo "Control table populated.<br />"; } else { echo "Error populating Control table."; }
unset($query);

$query = <<<END
CREATE TABLE `$drops` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `mlevel` smallint(5) unsigned NOT NULL default '0',
  `type` smallint(5) unsigned NOT NULL default '0',
  `attribute1` varchar(30) NOT NULL default '',
  `attribute2` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
END;
if (dobatch($query) == 1) { echo "Drops table created.<br />"; } else { echo "Error creating Drops table."; }
unset($query);

if ($full == true) {
$query = <<<END
INSERT INTO `$drops` VALUES (1, 'Life Pebble', 1, 1, 'maxhp,10', 'X');
INSERT INTO `$drops` VALUES (2, 'Life Stone', 10, 1, 'maxhp,25', 'X');
INSERT INTO `$drops` VALUES (3, 'Life Rock', 25, 1, 'maxhp,50', 'X');
INSERT INTO `$drops` VALUES (4, 'Magic Pebble', 1, 1, 'maxmp,10', 'X');
INSERT INTO `$drops` VALUES (5, 'Magic Stone', 10, 1, 'maxmp,25', 'X');
INSERT INTO `$drops` VALUES (6, 'Magic Rock', 25, 1, 'maxmp,50', 'X');
INSERT INTO `$drops` VALUES (7, 'Dragon\'s Scale', 10, 1, 'defensepower,25', 'X');
INSERT INTO `$drops` VALUES (8, 'Dragon\'s Plate', 30, 1, 'defensepower,50', 'X');
INSERT INTO `$drops` VALUES (9, 'Dragon\'s Claw', 10, 1, 'attackpower,25', 'X');
INSERT INTO `$drops` VALUES (10, 'Dragon\'s Tooth', 30, 1, 'attackpower,50', 'X');
INSERT INTO `$drops` VALUES (11, 'Dragon\'s Tear', 35, 1, 'strength,50', 'X');
INSERT INTO `$drops` VALUES (12, 'Dragon\'s Wing', 35, 1, 'dexterity,50', 'X');
INSERT INTO `$drops` VALUES (13, 'Demon\'s Sin', 35, 1, 'maxhp,-50', 'strength,50');
INSERT INTO `$drops` VALUES (14, 'Demon\'s Fall', 35, 1, 'maxmp,-50', 'strength,50');
INSERT INTO `$drops` VALUES (15, 'Demon\'s Lie', 45, 1, 'maxhp,-100', 'strength,100');
INSERT INTO `$drops` VALUES (16, 'Demon\'s Hate', 45, 1, 'maxmp,-100', 'strength,100');
INSERT INTO `$drops` VALUES (17, 'Angel\'s Joy', 25, 1, 'maxhp,25', 'strength,25');
INSERT INTO `$drops` VALUES (18, 'Angel\'s Rise', 30, 1, 'maxhp,50', 'strength,50');
INSERT INTO `$drops` VALUES (19, 'Angel\'s Truth', 35, 1, 'maxhp,75', 'strength,75');
INSERT INTO `$drops` VALUES (20, 'Angel\'s Love', 40, 1, 'maxhp,100', 'strength,100');
INSERT INTO `$drops` VALUES (21, 'Seraph\'s Joy', 25, 1, 'maxmp,25', 'dexterity,25');
INSERT INTO `$drops` VALUES (22, 'Seraph\'s Rise', 30, 1, 'maxmp,50', 'dexterity,50');
INSERT INTO `$drops` VALUES (23, 'Seraph\'s Truth', 35, 1, 'maxmp,75', 'dexterity,75');
INSERT INTO `$drops` VALUES (24, 'Seraph\'s Love', 40, 1, 'maxmp,100', 'dexterity,100');
INSERT INTO `$drops` VALUES (25, 'Ruby', 50, 1, 'maxhp,150', 'X');
INSERT INTO `$drops` VALUES (26, 'Pearl', 50, 1, 'maxmp,150', 'X');
INSERT INTO `$drops` VALUES (27, 'Emerald', 50, 1, 'strength,150', 'X');
INSERT INTO `$drops` VALUES (28, 'Topaz', 50, 1, 'dexterity,150', 'X');
INSERT INTO `$drops` VALUES (29, 'Obsidian', 50, 1, 'attackpower,150', 'X');
INSERT INTO `$drops` VALUES (30, 'Diamond', 50, 1, 'defensepower,150', 'X');
INSERT INTO `$drops` VALUES (31, 'Memory Drop', 5, 1, 'expbonus,10', 'X');
INSERT INTO `$drops` VALUES (32, 'Fortune Drop', 5, 1, 'goldbonus,10', 'X');
END;
if (dobatch($query) == 1) { echo "Drops table populated.<br />"; } else { echo "Error populating Drops table."; }
unset($query);
}

$query = <<<END
CREATE TABLE `$forum` (
  `id` int(11) NOT NULL auto_increment,
  `postdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `newpostdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `author` varchar(30) NOT NULL default '',
  `parent` int(11) NOT NULL default '0',
  `replies` int(11) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  `content` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
END;
if (dobatch($query) == 1) { echo "Forum table created.<br />"; } else { echo "Error creating Forum table."; }
unset($query);

$query = <<<END
CREATE TABLE `$items` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `type` tinyint(3) unsigned NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `buycost` smallint(5) unsigned NOT NULL default '0',
  `attribute` smallint(5) unsigned NOT NULL default '0',
  `special` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
END;
if (dobatch($query) == 1) { echo "Items table created.<br />"; } else { echo "Error creating Items table."; }
unset($query);

if ($full == true) {
$query = <<<END
INSERT INTO `$items` VALUES (1, 1, 'Stick', 10, 2, 'X');
INSERT INTO `$items` VALUES (2, 1, 'Branch', 30, 4, 'X');
INSERT INTO `$items` VALUES (3, 1, 'Club', 40, 5, 'X');
INSERT INTO `$items` VALUES (4, 1, 'Dagger', 90, 8, 'X');
INSERT INTO `$items` VALUES (5, 1, 'Hatchet', 150, 12, 'X');
INSERT INTO `$items` VALUES (6, 1, 'Axe', 200, 16, 'X');
INSERT INTO `$items` VALUES (7, 1, 'Brand', 300, 25, 'X');
INSERT INTO `$items` VALUES (8, 1, 'Poleaxe', 500, 35, 'X');
INSERT INTO `$items` VALUES (9, 1, 'Broadsword', 800, 45, 'X');
INSERT INTO `$items` VALUES (10, 1, 'Battle Axe', 1200, 50, 'X');
INSERT INTO `$items` VALUES (11, 1, 'Claymore', 2000, 60, 'X');
INSERT INTO `$items` VALUES (12, 1, 'Dark Axe', 3000, 100, 'expbonus,-5');
INSERT INTO `$items` VALUES (13, 1, 'Dark Sword', 4500, 125, 'expbonus,-10');
INSERT INTO `$items` VALUES (14, 1, 'Bright Sword', 6000, 100, 'expbonus,10');
INSERT INTO `$items` VALUES (15, 1, 'Magic Sword', 10000, 150, 'maxmp,50');
INSERT INTO `$items` VALUES (16, 1, 'Destiny Blade', 50000, 250, 'strength,50');
INSERT INTO `$items` VALUES (17, 2, 'Skivvies', 25, 2, 'goldbonus,10');
INSERT INTO `$items` VALUES (18, 2, 'Clothes', 50, 5, 'X');
INSERT INTO `$items` VALUES (19, 2, 'Leather Armor', 75, 10, 'X');
INSERT INTO `$items` VALUES (20, 2, 'Hard Leather Armor', 150, 25, 'X');
INSERT INTO `$items` VALUES (21, 2, 'Chain Mail', 300, 30, 'X');
INSERT INTO `$items` VALUES (22, 2, 'Bronze Plate', 900, 50, 'X');
INSERT INTO `$items` VALUES (23, 2, 'Iron Plate', 2000, 100, 'X');
INSERT INTO `$items` VALUES (24, 2, 'Magic Armor', 4000, 125, 'maxmp,50');
INSERT INTO `$items` VALUES (25, 2, 'Dark Armor', 5000, 150, 'expbonus,-10');
INSERT INTO `$items` VALUES (26, 2, 'Bright Armor', 10000, 175, 'expbonus,10');
INSERT INTO `$items` VALUES (27, 2, 'Destiny Raiment', 50000, 200, 'dexterity,50');
INSERT INTO `$items` VALUES (28, 3, 'Reed Shield', 50, 2, 'X');
INSERT INTO `$items` VALUES (29, 3, 'Buckler', 100, 4, 'X');
INSERT INTO `$items` VALUES (30, 3, 'Small Shield', 500, 10, 'X');
INSERT INTO `$items` VALUES (31, 3, 'Large Shield', 2500, 30, 'X');
INSERT INTO `$items` VALUES (32, 3, 'Silver Shield', 10000, 60, 'X');
INSERT INTO `$items` VALUES (33, 3, 'Destiny Aegis', 25000, 100, 'maxhp,50');
END;
if (dobatch($query) == 1) { echo "Items table populated.<br />"; } else { echo "Error populating Items table."; }
unset($query);
}

$query = <<<END
CREATE TABLE `$levels` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `1_exp` mediumint(8) unsigned NOT NULL default '0',
  `1_hp` smallint(5) unsigned NOT NULL default '0',
  `1_mp` smallint(5) unsigned NOT NULL default '0',
  `1_tp` smallint(5) unsigned NOT NULL default '0',
  `1_strength` smallint(5) unsigned NOT NULL default '0',
  `1_dexterity` smallint(5) unsigned NOT NULL default '0',
  `1_spells` tinyint(3) unsigned NOT NULL default '0',
  `2_exp` mediumint(8) unsigned NOT NULL default '0',
  `2_hp` smallint(5) unsigned NOT NULL default '0',
  `2_mp` smallint(5) unsigned NOT NULL default '0',
  `2_tp` smallint(5) unsigned NOT NULL default '0',
  `2_strength` smallint(5) unsigned NOT NULL default '0',
  `2_dexterity` smallint(5) unsigned NOT NULL default '0',
  `2_spells` tinyint(3) unsigned NOT NULL default '0',
  `3_exp` mediumint(8) unsigned NOT NULL default '0',
  `3_hp` smallint(5) unsigned NOT NULL default '0',
  `3_mp` smallint(5) unsigned NOT NULL default '0',
  `3_tp` smallint(5) unsigned NOT NULL default '0',
  `3_strength` smallint(5) unsigned NOT NULL default '0',
  `3_dexterity` smallint(5) unsigned NOT NULL default '0',
  `3_spells` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
END;
if (dobatch($query) == 1) { echo "Levels table created.<br />"; } else { echo "Error creating Levels table."; }
unset($query);

if ($full == true) {
$query = <<<END
INSERT INTO `$levels` VALUES (1, 0, 15, 0, 5, 5, 5, 0, 0, 15, 0, 5, 5, 5, 0, 0, 15, 0, 5, 5, 5, 0);
INSERT INTO `$levels` VALUES (2, 15, 2, 5, 1, 0, 1, 1, 18, 2, 4, 1, 2, 1, 1, 20, 2, 5, 1, 0, 2, 1);
INSERT INTO `$levels` VALUES (3, 45, 3, 4, 2, 1, 2, 0, 54, 2, 3, 2, 3, 2, 0, 60, 2, 3, 2, 1, 3, 0);
INSERT INTO `$levels` VALUES (4, 105, 3, 3, 2, 1, 2, 6, 126, 2, 3, 2, 3, 2, 0, 140, 2, 4, 2, 1, 3, 0);
INSERT INTO `$levels` VALUES (5, 195, 2, 5, 2, 0, 1, 0, 234, 2, 4, 2, 2, 1, 6, 260, 2, 4, 2, 0, 2, 6);
INSERT INTO `$levels` VALUES (6, 330, 4, 5, 2, 2, 3, 0, 396, 3, 4, 2, 4, 3, 0, 440, 3, 5, 2, 2, 4, 0);
INSERT INTO `$levels` VALUES (7, 532, 3, 4, 2, 1, 2, 11, 639, 2, 3, 2, 3, 2, 0, 710, 2, 3, 2, 1, 3, 0);
INSERT INTO `$levels` VALUES (8, 835, 2, 4, 2, 0, 1, 0, 1003, 2, 3, 2, 2, 1, 11, 1115, 2, 4, 2, 0, 2, 11);
INSERT INTO `$levels` VALUES (9, 1290, 5, 3, 2, 3, 4, 2, 1549, 4, 2, 2, 5, 4, 0, 1722, 4, 2, 2, 3, 5, 0);
INSERT INTO `$levels` VALUES (10, 1973, 10, 3, 2, 4, 3, 0, 2369, 10, 2, 2, 6, 3, 0, 2633, 10, 3, 2, 4, 4, 0);
INSERT INTO `$levels` VALUES (11, 2997, 5, 2, 2, 3, 4, 0, 3598, 4, 1, 2, 5, 4, 2, 3999, 4, 1, 2, 3, 5, 2);
INSERT INTO `$levels` VALUES (12, 4533, 4, 2, 2, 2, 3, 7, 5441, 4, 1, 2, 4, 3, 0, 6047, 4, 2, 2, 2, 4, 0);
INSERT INTO `$levels` VALUES (13, 6453, 4, 3, 2, 2, 3, 0, 7745, 4, 2, 2, 4, 3, 0, 8607, 4, 2, 2, 2, 4, 0);
INSERT INTO `$levels` VALUES (14, 8853, 5, 4, 2, 3, 4, 17, 10625, 4, 3, 2, 5, 4, 7, 11807, 4, 4, 2, 3, 5, 7);
INSERT INTO `$levels` VALUES (15, 11853, 5, 5, 2, 3, 4, 0, 14225, 4, 4, 2, 5, 4, 0, 15808, 4, 4, 2, 3, 5, 0);
INSERT INTO `$levels` VALUES (16, 15603, 5, 3, 2, 3, 4, 0, 18725, 5, 2, 2, 5, 4, 0, 20807, 5, 3, 2, 3, 5, 0);
INSERT INTO `$levels` VALUES (17, 20290, 4, 2, 2, 2, 3, 12, 24350, 4, 1, 2, 4, 3, 0, 27057, 4, 1, 2, 2, 4, 0);
INSERT INTO `$levels` VALUES (18, 25563, 4, 2, 2, 2, 3, 0, 30678, 3, 1, 2, 4, 3, 14, 34869, 3, 2, 2, 2, 4, 17);
INSERT INTO `$levels` VALUES (19, 31495, 4, 5, 2, 2, 3, 0, 37797, 3, 4, 2, 4, 3, 0, 43657, 3, 4, 2, 2, 4, 0);
INSERT INTO `$levels` VALUES (20, 38169, 10, 6, 2, 3, 3, 0, 45805, 10, 5, 2, 5, 3, 0, 53543, 10, 6, 2, 3, 4, 0);
INSERT INTO `$levels` VALUES (21, 45676, 4, 4, 2, 2, 3, 0, 54814, 4, 3, 2, 4, 3, 0, 64664, 4, 3, 2, 2, 4, 0);
INSERT INTO `$levels` VALUES (22, 54121, 5, 5, 2, 3, 4, 0, 64949, 4, 4, 2, 5, 4, 12, 77175, 4, 5, 2, 3, 5, 12);
INSERT INTO `$levels` VALUES (23, 63622, 5, 3, 2, 3, 4, 0, 76350, 4, 2, 2, 5, 4, 0, 91250, 4, 2, 2, 3, 5, 0);
INSERT INTO `$levels` VALUES (24, 74310, 5, 5, 2, 3, 4, 0, 89176, 4, 4, 2, 5, 4, 0, 107083, 4, 5, 2, 3, 5, 0);
INSERT INTO `$levels` VALUES (25, 86334, 4, 4, 2, 2, 3, 3, 103605, 3, 3, 2, 4, 3, 17, 124895, 3, 3, 2, 2, 4, 14);
INSERT INTO `$levels` VALUES (26, 99861, 6, 3, 2, 4, 5, 0, 119837, 5, 2, 2, 6, 5, 0, 144933, 5, 3, 2, 4, 6, 0);
INSERT INTO `$levels` VALUES (27, 115078, 6, 2, 2, 4, 5, 0, 138098, 5, 1, 2, 6, 5, 0, 167475, 5, 1, 2, 4, 6, 0);
INSERT INTO `$levels` VALUES (28, 132197, 4, 2, 2, 2, 3, 0, 158641, 4, 1, 2, 4, 3, 0, 192835, 4, 2, 2, 2, 4, 0);
INSERT INTO `$levels` VALUES (29, 151456, 6, 3, 2, 4, 5, 0, 181751, 5, 2, 2, 6, 5, 3, 221365, 5, 2, 2, 4, 6, 3);
INSERT INTO `$levels` VALUES (30, 173121, 10, 4, 3, 4, 4, 0, 207749, 10, 3, 3, 6, 4, 0, 253461, 10, 4, 3, 4, 5, 0);
INSERT INTO `$levels` VALUES (31, 197494, 5, 5, 3, 3, 4, 8, 236996, 4, 3, 3, 5, 4, 0, 289568, 4, 3, 3, 3, 5, 0);
INSERT INTO `$levels` VALUES (32, 224913, 6, 4, 3, 4, 5, 0, 269898, 5, 3, 3, 6, 5, 0, 330188, 5, 4, 3, 4, 6, 0);
INSERT INTO `$levels` VALUES (33, 255758, 5, 4, 3, 3, 4, 0, 306912, 5, 3, 3, 5, 4, 0, 375885, 5, 3, 3, 3, 5, 0);
INSERT INTO `$levels` VALUES (34, 290458, 6, 4, 3, 4, 5, 0, 348552, 5, 3, 3, 6, 5, 8, 427294, 5, 4, 3, 4, 6, 8);
INSERT INTO `$levels` VALUES (35, 329495, 5, 3, 3, 3, 4, 0, 395397, 4, 2, 3, 5, 4, 0, 485126, 4, 2, 3, 3, 5, 0);
INSERT INTO `$levels` VALUES (36, 373412, 4, 3, 3, 2, 3, 18, 448097, 5, 2, 3, 4, 3, 0, 550188, 5, 3, 3, 2, 4, 0);
INSERT INTO `$levels` VALUES (37, 422818, 5, 4, 3, 3, 4, 0, 507384, 5, 3, 3, 5, 4, 0, 623383, 5, 3, 3, 3, 5, 0);
INSERT INTO `$levels` VALUES (38, 478399, 6, 5, 3, 4, 5, 0, 574081, 5, 4, 3, 6, 5, 15, 705726, 5, 5, 3, 4, 6, 18);
INSERT INTO `$levels` VALUES (39, 540927, 6, 4, 3, 4, 5, 0, 649115, 5, 3, 3, 6, 5, 0, 798362, 5, 3, 3, 4, 6, 0);
INSERT INTO `$levels` VALUES (40, 611271, 15, 3, 3, 5, 5, 13, 733528, 15, 2, 3, 7, 5, 0, 902577, 15, 3, 3, 5, 6, 0);
INSERT INTO `$levels` VALUES (41, 690408, 7, 3, 3, 5, 2, 0, 828492, 6, 2, 3, 7, 2, 0, 1019818, 6, 2, 3, 5, 3, 0);
INSERT INTO `$levels` VALUES (42, 779437, 7, 4, 3, 5, 6, 0, 935326, 6, 3, 3, 7, 6, 0, 1151714, 6, 4, 3, 5, 7, 0);
INSERT INTO `$levels` VALUES (43, 879592, 8, 5, 3, 6, 7, 0, 1055514, 7, 4, 3, 8, 7, 0, 1300096, 7, 4, 3, 6, 8, 0);
INSERT INTO `$levels` VALUES (44, 992268, 6, 3, 3, 4, 5, 0, 1190725, 5, 2, 3, 6, 5, 0, 1448478, 5, 3, 3, 4, 6, 0);
INSERT INTO `$levels` VALUES (45, 1119028, 5, 8, 3, 3, 4, 4, 1325936, 5, 8, 3, 5, 4, 18, 1596860, 5, 8, 3, 3, 5, 4);
INSERT INTO `$levels` VALUES (46, 1245788, 6, 5, 3, 4, 5, 0, 1461147, 5, 4, 3, 6, 5, 0, 1745242, 5, 5, 3, 4, 6, 0);
INSERT INTO `$levels` VALUES (47, 1372548, 7, 4, 3, 5, 6, 0, 1596358, 6, 3, 3, 7, 6, 0, 1893624, 6, 3, 3, 5, 7, 0);
INSERT INTO `$levels` VALUES (48, 1499308, 6, 4, 3, 4, 5, 0, 1731569, 5, 3, 3, 6, 5, 0, 2042006, 5, 4, 3, 4, 6, 0);
INSERT INTO `$levels` VALUES (49, 1626068, 5, 3, 3, 3, 4, 0, 1866780, 4, 2, 3, 5, 4, 0, 2190388, 4, 2, 3, 3, 5, 0);
INSERT INTO `$levels` VALUES (50, 1752828, 15, 3, 3, 5, 5, 0, 2001991, 15, 2, 3, 7, 5, 0, 2338770, 15, 3, 3, 5, 6, 0);
INSERT INTO `$levels` VALUES (51, 1879588, 6, 2, 3, 4, 5, 9, 2137202, 5, 1, 3, 6, 5, 13, 2487152, 5, 1, 3, 4, 6, 13);
INSERT INTO `$levels` VALUES (52, 2006348, 7, 2, 3, 5, 6, 0, 2272413, 6, 1, 3, 7, 6, 0, 2635534, 6, 2, 3, 5, 7, 0);
INSERT INTO `$levels` VALUES (53, 2133108, 8, 2, 3, 6, 7, 0, 2407624, 7, 1, 3, 8, 7, 0, 2783916, 7, 1, 3, 6, 8, 0);
INSERT INTO `$levels` VALUES (54, 2259868, 8, 4, 3, 6, 7, 0, 2542835, 7, 3, 3, 8, 7, 0, 2932298, 7, 4, 3, 6, 8, 0);
INSERT INTO `$levels` VALUES (55, 2386628, 7, 4, 3, 5, 6, 0, 2678046, 6, 3, 3, 7, 6, 0, 3080680, 6, 3, 3, 5, 7, 0);
INSERT INTO `$levels` VALUES (56, 2513388, 7, 4, 3, 5, 6, 0, 2813257, 6, 3, 3, 7, 6, 0, 3229062, 6, 4, 3, 5, 7, 9);
INSERT INTO `$levels` VALUES (57, 2640148, 6, 5, 3, 4, 5, 0, 2948468, 6, 4, 3, 6, 5, 0, 3377444, 6, 4, 3, 4, 6, 0);
INSERT INTO `$levels` VALUES (58, 2766908, 5, 5, 3, 3, 4, 0, 3083679, 5, 4, 3, 5, 4, 19, 3525826, 5, 5, 3, 3, 5, 0);
INSERT INTO `$levels` VALUES (59, 2893668, 8, 3, 3, 6, 7, 0, 3218890, 7, 2, 3, 8, 7, 0, 3674208, 7, 2, 3, 6, 8, 0);
INSERT INTO `$levels` VALUES (60, 3020428, 15, 4, 4, 6, 6, 19, 3354101, 15, 3, 4, 8, 6, 0, 3822590, 15, 4, 4, 6, 7, 15);
INSERT INTO `$levels` VALUES (61, 3147188, 8, 5, 4, 6, 7, 0, 3489312, 7, 4, 4, 8, 7, 0, 3970972, 7, 4, 4, 6, 8, 0);
INSERT INTO `$levels` VALUES (62, 3273948, 8, 4, 4, 6, 7, 0, 3624523, 7, 3, 4, 8, 7, 0, 4119354, 7, 4, 4, 6, 8, 0);
INSERT INTO `$levels` VALUES (63, 3400708, 9, 5, 4, 7, 8, 0, 3759734, 8, 4, 4, 9, 8, 0, 4267736, 8, 4, 4, 7, 9, 0);
INSERT INTO `$levels` VALUES (64, 3527468, 5, 5, 4, 3, 4, 0, 3894945, 5, 4, 4, 5, 4, 0, 4416118, 5, 5, 4, 3, 5, 0);
INSERT INTO `$levels` VALUES (65, 3654228, 6, 4, 4, 4, 5, 0, 4030156, 6, 3, 4, 6, 5, 0, 4564500, 6, 3, 4, 4, 6, 0);
INSERT INTO `$levels` VALUES (66, 3780988, 8, 4, 4, 6, 7, 0, 4165367, 8, 3, 4, 8, 7, 0, 4712882, 8, 4, 4, 6, 8, 0);
INSERT INTO `$levels` VALUES (67, 3907748, 7, 3, 4, 5, 6, 0, 4300578, 7, 2, 4, 7, 6, 0, 4861264, 7, 2, 4, 5, 7, 0);
INSERT INTO `$levels` VALUES (68, 4034508, 9, 3, 4, 7, 8, 0, 4435789, 8, 2, 4, 9, 8, 0, 5009646, 8, 3, 4, 7, 9, 0);
INSERT INTO `$levels` VALUES (69, 4161268, 5, 4, 4, 3, 4, 0, 4571000, 5, 3, 4, 5, 4, 0, 5158028, 5, 3, 4, 3, 5, 0);
INSERT INTO `$levels` VALUES (70, 4288028, 20, 4, 4, 6, 6, 5, 4706211, 20, 3, 4, 8, 6, 16, 5306410, 20, 4, 4, 6, 7, 0);
INSERT INTO `$levels` VALUES (71, 4414788, 5, 5, 4, 3, 4, 0, 4841422, 5, 4, 4, 5, 4, 0, 5454792, 5, 4, 4, 3, 5, 0);
INSERT INTO `$levels` VALUES (72, 4541548, 6, 2, 4, 4, 5, 0, 4976633, 5, 1, 4, 6, 5, 0, 5603174, 5, 2, 4, 4, 6, 0);
INSERT INTO `$levels` VALUES (73, 4668308, 8, 4, 4, 6, 7, 0, 5111844, 8, 3, 4, 8, 7, 0, 5751556, 8, 3, 4, 6, 8, 0);
INSERT INTO `$levels` VALUES (74, 4795068, 7, 5, 4, 5, 6, 0, 5247055, 6, 4, 4, 7, 6, 0, 5899938, 6, 5, 4, 5, 7, 0);
INSERT INTO `$levels` VALUES (75, 4921828, 5, 3, 4, 3, 4, 0, 5382266, 5, 2, 4, 5, 4, 0, 6048320, 5, 2, 4, 3, 5, 0);
INSERT INTO `$levels` VALUES (76, 5048588, 6, 3, 4, 4, 5, 0, 5517477, 6, 2, 4, 6, 5, 0, 6196702, 6, 3, 4, 4, 6, 0);
INSERT INTO `$levels` VALUES (77, 5175348, 6, 4, 4, 4, 5, 0, 5652688, 7, 3, 4, 6, 5, 0, 6345084, 7, 3, 4, 4, 6, 0);
INSERT INTO `$levels` VALUES (78, 5302108, 7, 4, 4, 5, 6, 0, 5787899, 7, 3, 4, 7, 6, 0, 6493466, 7, 4, 4, 5, 7, 0);
INSERT INTO `$levels` VALUES (79, 5428868, 8, 4, 4, 6, 7, 10, 5923110, 7, 3, 4, 8, 7, 0, 6641848, 7, 3, 4, 6, 8, 0);
INSERT INTO `$levels` VALUES (80, 5555628, 20, 5, 4, 6, 7, 0, 6058321, 20, 4, 4, 8, 7, 0, 6790230, 20, 5, 4, 6, 8, 0);
INSERT INTO `$levels` VALUES (81, 5682388, 7, 3, 4, 5, 6, 0, 6193532, 7, 2, 4, 7, 6, 0, 6938612, 7, 2, 4, 5, 7, 0);
INSERT INTO `$levels` VALUES (82, 5809148, 6, 4, 4, 4, 5, 0, 6328743, 5, 3, 4, 6, 5, 0, 7086994, 5, 4, 4, 4, 6, 0);
INSERT INTO `$levels` VALUES (83, 5935908, 6, 2, 4, 4, 5, 0, 6463954, 6, 1, 4, 6, 5, 0, 7235376, 6, 1, 4, 4, 6, 0);
INSERT INTO `$levels` VALUES (84, 6062668, 5, 4, 4, 3, 4, 0, 6599165, 5, 3, 4, 5, 4, 0, 7383758, 5, 4, 4, 3, 5, 0);
INSERT INTO `$levels` VALUES (85, 6189428, 7, 4, 4, 5, 6, 0, 6734376, 6, 3, 4, 7, 6, 0, 7532140, 6, 3, 4, 5, 7, 0);
INSERT INTO `$levels` VALUES (86, 6316188, 8, 5, 4, 6, 7, 0, 6869587, 8, 4, 4, 8, 7, 0, 7680522, 8, 5, 4, 6, 8, 0);
INSERT INTO `$levels` VALUES (87, 6442948, 8, 4, 4, 6, 7, 0, 7004798, 7, 3, 4, 8, 7, 0, 7828904, 7, 3, 4, 6, 8, 0);
INSERT INTO `$levels` VALUES (88, 6569708, 9, 5, 4, 7, 8, 0, 7140009, 8, 4, 4, 9, 8, 0, 7977286, 8, 5, 4, 7, 9, 0);
INSERT INTO `$levels` VALUES (89, 6696468, 5, 2, 4, 3, 4, 0, 7275220, 5, 1, 4, 5, 4, 0, 8125668, 5, 1, 4, 3, 5, 0);
INSERT INTO `$levels` VALUES (90, 6823228, 20, 2, 5, 7, 8, 0, 7410431, 20, 1, 5, 9, 8, 0, 8274050, 20, 2, 5, 7, 9, 0);
INSERT INTO `$levels` VALUES (91, 6949988, 5, 3, 5, 3, 4, 0, 7545642, 5, 2, 5, 5, 4, 0, 8422432, 5, 2, 5, 3, 5, 0);
INSERT INTO `$levels` VALUES (92, 7076748, 6, 3, 5, 4, 5, 0, 7680853, 4, 2, 5, 6, 5, 0, 8570814, 4, 3, 5, 4, 6, 0);
INSERT INTO `$levels` VALUES (93, 7203508, 8, 4, 5, 6, 7, 0, 7816064, 6, 2, 5, 8, 7, 0, 8719196, 6, 2, 5, 6, 8, 0);
INSERT INTO `$levels` VALUES (94, 7330268, 4, 4, 5, 3, 3, 0, 7951275, 4, 3, 5, 5, 3, 0, 8867578, 4, 4, 5, 3, 4, 0);
INSERT INTO `$levels` VALUES (95, 7457028, 3, 3, 5, 5, 2, 0, 8086486, 4, 2, 5, 7, 2, 0, 9015960, 4, 2, 5, 5, 3, 0);
INSERT INTO `$levels` VALUES (96, 7583788, 5, 3, 5, 4, 3, 0, 8221697, 5, 2, 5, 7, 3, 0, 9164342, 5, 3, 5, 4, 4, 0);
INSERT INTO `$levels` VALUES (97, 7710548, 5, 4, 5, 4, 5, 0, 8356908, 5, 3, 5, 7, 5, 0, 9312724, 5, 3, 5, 4, 6, 0);
INSERT INTO `$levels` VALUES (98, 7837308, 4, 5, 5, 4, 3, 0, 8492119, 4, 3, 5, 7, 3, 0, 9461106, 4, 4, 5, 4, 4, 0);
INSERT INTO `$levels` VALUES (99, 7964068, 50, 5, 5, 6, 5, 0, 8627330, 50, 3, 5, 9, 5, 0, 9609488, 50, 4, 5, 6, 6, 0);
INSERT INTO `$levels` VALUES (100, 16777215, 0, 0, 0, 0, 0, 0, 16777215, 0, 0, 0, 0, 0, 0, 16777215, 0, 0, 0, 0, 0, 0);
END;
if (dobatch($query) == 1) { echo "Levels table populated.<br />"; } else { echo "Error populating Levels table."; }
unset($query);
}

$query = <<<END
CREATE TABLE `$monsters` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `maxhp` smallint(5) unsigned NOT NULL default '0',
  `maxdam` smallint(5) unsigned NOT NULL default '0',
  `armor` smallint(5) unsigned NOT NULL default '0',
  `level` smallint(5) unsigned NOT NULL default '0',
  `maxexp` smallint(5) unsigned NOT NULL default '0',
  `maxgold` smallint(5) unsigned NOT NULL default '0',
  `immune` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
END;
if (dobatch($query) == 1) { echo "Monsters table created.<br />"; } else { echo "Error creating Monsters table."; }
unset($query);

if ($full == true) {
$query = <<<END
INSERT INTO `$monsters` VALUES (1, 'Blue Slime', 4, 3, 1, 1, 1, 1, 0);
INSERT INTO `$monsters` VALUES (2, 'Red Slime', 6, 5, 1, 1, 2, 1, 0);
INSERT INTO `$monsters` VALUES (3, 'Critter', 6, 5, 2, 1, 4, 2, 0);
INSERT INTO `$monsters` VALUES (4, 'Creature', 10, 8, 2, 2, 4, 2, 0);
INSERT INTO `$monsters` VALUES (5, 'Shadow', 10, 9, 3, 2, 6, 2, 1);
INSERT INTO `$monsters` VALUES (6, 'Drake', 11, 10, 3, 2, 8, 3, 0);
INSERT INTO `$monsters` VALUES (7, 'Shade', 12, 10, 3, 3, 10, 3, 1);
INSERT INTO `$monsters` VALUES (8, 'Drakelor', 14, 12, 4, 3, 10, 3, 0);
INSERT INTO `$monsters` VALUES (9, 'Silver Slime', 15, 100, 200, 30, 15, 1000, 2);
INSERT INTO `$monsters` VALUES (10, 'Scamp', 16, 13, 5, 4, 15, 5, 0);
INSERT INTO `$monsters` VALUES (11, 'Raven', 16, 13, 5, 4, 18, 6, 0);
INSERT INTO `$monsters` VALUES (12, 'Scorpion', 18, 14, 6, 5, 20, 7, 0);
INSERT INTO `$monsters` VALUES (13, 'Illusion', 20, 15, 6, 5, 20, 7, 1);
INSERT INTO `$monsters` VALUES (14, 'Nightshade', 22, 16, 6, 6, 24, 8, 0);
INSERT INTO `$monsters` VALUES (15, 'Drakemal', 22, 18, 7, 6, 24, 8, 0);
INSERT INTO `$monsters` VALUES (16, 'Shadow Raven', 24, 18, 7, 6, 26, 9, 1);
INSERT INTO `$monsters` VALUES (17, 'Ghost', 24, 20, 8, 6, 28, 9, 0);
INSERT INTO `$monsters` VALUES (18, 'Frost Raven', 26, 20, 8, 7, 30, 10, 0);
INSERT INTO `$monsters` VALUES (19, 'Rogue Scorpion', 28, 22, 9, 7, 32, 11, 0);
INSERT INTO `$monsters` VALUES (20, 'Ghoul', 29, 24, 9, 7, 34, 11, 0);
INSERT INTO `$monsters` VALUES (21, 'Magician', 30, 24, 10, 8, 36, 12, 0);
INSERT INTO `$monsters` VALUES (22, 'Rogue', 30, 25, 12, 8, 40, 13, 0);
INSERT INTO `$monsters` VALUES (23, 'Drakefin', 32, 26, 12, 8, 40, 13, 0);
INSERT INTO `$monsters` VALUES (24, 'Shimmer', 32, 26, 14, 8, 45, 15, 1);
INSERT INTO `$monsters` VALUES (25, 'Fire Raven', 34, 28, 14, 9, 45, 15, 0);
INSERT INTO `$monsters` VALUES (26, 'Dybbuk', 34, 28, 14, 9, 50, 17, 0);
INSERT INTO `$monsters` VALUES (27, 'Knave', 36, 30, 15, 9, 52, 17, 0);
INSERT INTO `$monsters` VALUES (28, 'Goblin', 36, 30, 15, 10, 54, 18, 0);
INSERT INTO `$monsters` VALUES (29, 'Skeleton', 38, 30, 18, 10, 58, 19, 0);
INSERT INTO `$monsters` VALUES (30, 'Dark Slime', 38, 32, 18, 10, 62, 21, 0);
INSERT INTO `$monsters` VALUES (31, 'Silver Scorpion', 30, 160, 350, 40, 63, 2000, 2);
INSERT INTO `$monsters` VALUES (32, 'Mirage', 40, 32, 20, 11, 64, 21, 1);
INSERT INTO `$monsters` VALUES (33, 'Sorceror', 41, 33, 22, 11, 68, 23, 0);
INSERT INTO `$monsters` VALUES (34, 'Imp', 42, 34, 22, 12, 70, 23, 0);
INSERT INTO `$monsters` VALUES (35, 'Nymph', 43, 35, 22, 12, 70, 23, 0);
INSERT INTO `$monsters` VALUES (36, 'Scoundrel', 43, 35, 22, 12, 75, 25, 0);
INSERT INTO `$monsters` VALUES (37, 'Megaskeleton', 44, 36, 24, 13, 78, 26, 0);
INSERT INTO `$monsters` VALUES (38, 'Grey Wolf', 44, 36, 24, 13, 82, 27, 0);
INSERT INTO `$monsters` VALUES (39, 'Phantom', 46, 38, 24, 14, 85, 28, 1);
INSERT INTO `$monsters` VALUES (40, 'Specter', 46, 38, 24, 14, 90, 30, 0);
INSERT INTO `$monsters` VALUES (41, 'Dark Scorpion', 48, 40, 26, 15, 95, 32, 1);
INSERT INTO `$monsters` VALUES (42, 'Warlock', 48, 40, 26, 15, 100, 33, 1);
INSERT INTO `$monsters` VALUES (43, 'Orc', 49, 42, 28, 15, 104, 35, 0);
INSERT INTO `$monsters` VALUES (44, 'Sylph', 49, 42, 28, 15, 106, 35, 0);
INSERT INTO `$monsters` VALUES (45, 'Wraith', 50, 45, 30, 16, 108, 36, 0);
INSERT INTO `$monsters` VALUES (46, 'Hellion', 50, 45, 30, 16, 110, 37, 0);
INSERT INTO `$monsters` VALUES (47, 'Bandit', 52, 45, 30, 16, 114, 38, 0);
INSERT INTO `$monsters` VALUES (48, 'Ultraskeleton', 52, 46, 32, 16, 116, 39, 0);
INSERT INTO `$monsters` VALUES (49, 'Dark Wolf', 54, 47, 36, 17, 120, 40, 1);
INSERT INTO `$monsters` VALUES (50, 'Troll', 56, 48, 36, 17, 120, 40, 0);
INSERT INTO `$monsters` VALUES (51, 'Werewolf', 56, 48, 38, 17, 124, 41, 0);
INSERT INTO `$monsters` VALUES (52, 'Hellcat', 58, 50, 38, 18, 128, 43, 0);
INSERT INTO `$monsters` VALUES (53, 'Spirit', 58, 50, 38, 18, 132, 44, 0);
INSERT INTO `$monsters` VALUES (54, 'Nisse', 60, 52, 40, 19, 132, 44, 0);
INSERT INTO `$monsters` VALUES (55, 'Dawk', 60, 54, 40, 19, 136, 45, 0);
INSERT INTO `$monsters` VALUES (56, 'Figment', 64, 55, 42, 19, 140, 47, 1);
INSERT INTO `$monsters` VALUES (57, 'Hellhound', 66, 56, 44, 20, 140, 47, 0);
INSERT INTO `$monsters` VALUES (58, 'Wizard', 66, 56, 44, 20, 144, 48, 0);
INSERT INTO `$monsters` VALUES (59, 'Uruk', 68, 58, 44, 20, 146, 49, 0);
INSERT INTO `$monsters` VALUES (60, 'Siren', 68, 400, 800, 50, 10000, 50, 2);
INSERT INTO `$monsters` VALUES (61, 'Megawraith', 70, 60, 46, 21, 155, 52, 0);
INSERT INTO `$monsters` VALUES (62, 'Dawkin', 70, 60, 46, 21, 155, 52, 0);
INSERT INTO `$monsters` VALUES (63, 'Grey Bear', 70, 62, 48, 21, 160, 53, 0);
INSERT INTO `$monsters` VALUES (64, 'Haunt', 72, 62, 48, 22, 160, 53, 0);
INSERT INTO `$monsters` VALUES (65, 'Hellbeast', 74, 64, 50, 22, 165, 55, 0);
INSERT INTO `$monsters` VALUES (66, 'Fear', 76, 66, 52, 23, 165, 55, 0);
INSERT INTO `$monsters` VALUES (67, 'Beast', 76, 66, 52, 23, 170, 57, 0);
INSERT INTO `$monsters` VALUES (68, 'Ogre', 78, 68, 54, 23, 170, 57, 0);
INSERT INTO `$monsters` VALUES (69, 'Dark Bear', 80, 70, 56, 24, 175, 58, 1);
INSERT INTO `$monsters` VALUES (70, 'Fire', 80, 72, 56, 24, 175, 58, 0);
INSERT INTO `$monsters` VALUES (71, 'Polgergeist', 84, 74, 58, 25, 180, 60, 0);
INSERT INTO `$monsters` VALUES (72, 'Fright', 86, 76, 58, 25, 180, 60, 0);
INSERT INTO `$monsters` VALUES (73, 'Lycan', 88, 78, 60, 25, 185, 62, 0);
INSERT INTO `$monsters` VALUES (74, 'Terra Elemental', 88, 80, 62, 25, 185, 62, 1);
INSERT INTO `$monsters` VALUES (75, 'Necromancer', 90, 80, 62, 26, 190, 63, 0);
INSERT INTO `$monsters` VALUES (76, 'Ultrawraith', 90, 82, 64, 26, 190, 63, 0);
INSERT INTO `$monsters` VALUES (77, 'Dawkor', 92, 82, 64, 26, 195, 65, 0);
INSERT INTO `$monsters` VALUES (78, 'Werebear', 92, 84, 65, 26, 195, 65, 0);
INSERT INTO `$monsters` VALUES (79, 'Brute', 94, 84, 65, 27, 200, 67, 0);
INSERT INTO `$monsters` VALUES (80, 'Large Beast', 96, 88, 66, 27, 200, 67, 0);
INSERT INTO `$monsters` VALUES (81, 'Horror', 96, 88, 68, 27, 210, 70, 0);
INSERT INTO `$monsters` VALUES (82, 'Flame', 100, 90, 70, 28, 210, 70, 0);
INSERT INTO `$monsters` VALUES (83, 'Lycanthor', 100, 90, 70, 28, 210, 70, 0);
INSERT INTO `$monsters` VALUES (84, 'Wyrm', 100, 92, 72, 28, 220, 73, 0);
INSERT INTO `$monsters` VALUES (85, 'Aero Elemental', 104, 94, 74, 29, 220, 73, 1);
INSERT INTO `$monsters` VALUES (86, 'Dawkare', 106, 96, 76, 29, 220, 73, 0);
INSERT INTO `$monsters` VALUES (87, 'Large Brute', 108, 98, 78, 29, 230, 77, 0);
INSERT INTO `$monsters` VALUES (88, 'Frost Wyrm', 110, 100, 80, 30, 230, 77, 0);
INSERT INTO `$monsters` VALUES (89, 'Knight', 110, 102, 80, 30, 240, 80, 0);
INSERT INTO `$monsters` VALUES (90, 'Lycanthra', 112, 104, 82, 30, 240, 80, 0);
INSERT INTO `$monsters` VALUES (91, 'Terror', 115, 108, 84, 31, 250, 83, 0);
INSERT INTO `$monsters` VALUES (92, 'Blaze', 118, 108, 84, 31, 250, 83, 0);
INSERT INTO `$monsters` VALUES (93, 'Aqua Elemental', 120, 110, 90, 31, 260, 87, 1);
INSERT INTO `$monsters` VALUES (94, 'Fire Wyrm', 120, 110, 90, 32, 260, 87, 0);
INSERT INTO `$monsters` VALUES (95, 'Lesser Wyvern', 122, 110, 92, 32, 270, 90, 0);
INSERT INTO `$monsters` VALUES (96, 'Doomer', 124, 112, 92, 32, 270, 90, 0);
INSERT INTO `$monsters` VALUES (97, 'Armor Knight', 130, 115, 95, 33, 280, 93, 0);
INSERT INTO `$monsters` VALUES (98, 'Wyvern', 134, 120, 95, 33, 290, 97, 0);
INSERT INTO `$monsters` VALUES (99, 'Nightmare', 138, 125, 100, 33, 300, 100, 0);
INSERT INTO `$monsters` VALUES (100, 'Fira Elemental', 140, 125, 100, 34, 310, 103, 1);
INSERT INTO `$monsters` VALUES (101, 'Megadoomer', 140, 128, 105, 34, 320, 107, 0);
INSERT INTO `$monsters` VALUES (102, 'Greater Wyvern', 145, 130, 105, 34, 335, 112, 0);
INSERT INTO `$monsters` VALUES (103, 'Advocate', 148, 132, 108, 35, 350, 117, 0);
INSERT INTO `$monsters` VALUES (104, 'Strong Knight', 150, 135, 110, 35, 365, 122, 0);
INSERT INTO `$monsters` VALUES (105, 'Liche', 150, 135, 110, 35, 380, 127, 0);
INSERT INTO `$monsters` VALUES (106, 'Ultradoomer', 155, 140, 115, 36, 395, 132, 0);
INSERT INTO `$monsters` VALUES (107, 'Fanatic', 160, 140, 115, 36, 410, 137, 0);
INSERT INTO `$monsters` VALUES (108, 'Green Dragon', 160, 140, 115, 36, 425, 142, 0);
INSERT INTO `$monsters` VALUES (109, 'Fiend', 160, 145, 120, 37, 445, 148, 0);
INSERT INTO `$monsters` VALUES (110, 'Greatest Wyvern', 162, 150, 120, 37, 465, 155, 0);
INSERT INTO `$monsters` VALUES (111, 'Lesser Devil', 164, 150, 120, 37, 485, 162, 0);
INSERT INTO `$monsters` VALUES (112, 'Liche Master', 168, 155, 125, 38, 505, 168, 0);
INSERT INTO `$monsters` VALUES (113, 'Zealot', 168, 155, 125, 38, 530, 177, 0);
INSERT INTO `$monsters` VALUES (114, 'Serafiend', 170, 155, 125, 38, 555, 185, 0);
INSERT INTO `$monsters` VALUES (115, 'Pale Knight', 175, 160, 130, 39, 580, 193, 0);
INSERT INTO `$monsters` VALUES (116, 'Blue Dragon', 180, 160, 130, 39, 605, 202, 0);
INSERT INTO `$monsters` VALUES (117, 'Obsessive', 180, 160, 135, 40, 630, 210, 0);
INSERT INTO `$monsters` VALUES (118, 'Devil', 184, 164, 135, 40, 666, 222, 0);
INSERT INTO `$monsters` VALUES (119, 'Liche Prince', 190, 168, 138, 40, 660, 220, 0);
INSERT INTO `$monsters` VALUES (120, 'Cherufiend', 195, 170, 140, 41, 690, 230, 0);
INSERT INTO `$monsters` VALUES (121, 'Red Dragon', 200, 180, 145, 41, 720, 240, 0);
INSERT INTO `$monsters` VALUES (122, 'Greater Devil', 200, 180, 145, 41, 750, 250, 0);
INSERT INTO `$monsters` VALUES (123, 'Renegade', 205, 185, 150, 42, 780, 260, 0);
INSERT INTO `$monsters` VALUES (124, 'Archfiend', 210, 190, 150, 42, 810, 270, 0);
INSERT INTO `$monsters` VALUES (125, 'Liche Lord', 210, 190, 155, 42, 850, 283, 0);
INSERT INTO `$monsters` VALUES (126, 'Greatest Devil', 215, 195, 160, 43, 890, 297, 0);
INSERT INTO `$monsters` VALUES (127, 'Dark Knight', 220, 200, 160, 43, 930, 310, 0);
INSERT INTO `$monsters` VALUES (128, 'Giant', 220, 200, 165, 43, 970, 323, 0);
INSERT INTO `$monsters` VALUES (129, 'Shadow Dragon', 225, 200, 170, 44, 1010, 337, 0);
INSERT INTO `$monsters` VALUES (130, 'Liche King', 225, 205, 170, 44, 1050, 350, 0);
INSERT INTO `$monsters` VALUES (131, 'Incubus', 230, 205, 175, 44, 1100, 367, 1);
INSERT INTO `$monsters` VALUES (132, 'Traitor', 230, 205, 175, 45, 1150, 383, 0);
INSERT INTO `$monsters` VALUES (133, 'Demon', 240, 210, 180, 45, 1200, 400, 0);
INSERT INTO `$monsters` VALUES (134, 'Dark Dragon', 245, 215, 180, 45, 1250, 417, 1);
INSERT INTO `$monsters` VALUES (135, 'Insurgent', 250, 220, 190, 46, 1300, 433, 0);
INSERT INTO `$monsters` VALUES (136, 'Leviathan', 255, 225, 190, 46, 1350, 450, 0);
INSERT INTO `$monsters` VALUES (137, 'Grey Daemon', 260, 230, 190, 46, 1400, 467, 0);
INSERT INTO `$monsters` VALUES (138, 'Succubus', 265, 240, 200, 47, 1460, 487, 1);
INSERT INTO `$monsters` VALUES (139, 'Demon Prince', 270, 240, 200, 47, 1520, 507, 0);
INSERT INTO `$monsters` VALUES (140, 'Black Dragon', 275, 250, 205, 47, 1580, 527, 1);
INSERT INTO `$monsters` VALUES (141, 'Nihilist', 280, 250, 205, 47, 1640, 547, 0);
INSERT INTO `$monsters` VALUES (142, 'Behemoth', 285, 260, 210, 48, 1700, 567, 0);
INSERT INTO `$monsters` VALUES (143, 'Demagogue', 290, 260, 210, 48, 1760, 587, 0);
INSERT INTO `$monsters` VALUES (144, 'Demon Lord', 300, 270, 220, 48, 1820, 607, 0);
INSERT INTO `$monsters` VALUES (145, 'Red Daemon', 310, 280, 230, 48, 1880, 627, 0);
INSERT INTO `$monsters` VALUES (146, 'Colossus', 320, 300, 240, 49, 1940, 647, 0);
INSERT INTO `$monsters` VALUES (147, 'Demon King', 330, 300, 250, 49, 2000, 667, 0);
INSERT INTO `$monsters` VALUES (148, 'Dark Daemon', 340, 320, 260, 49, 2200, 733, 1);
INSERT INTO `$monsters` VALUES (149, 'Titan', 360, 340, 270, 50, 2400, 800, 0);
INSERT INTO `$monsters` VALUES (150, 'Black Daemon', 400, 400, 280, 50, 3000, 1000, 1);
INSERT INTO `$monsters` VALUES (151, 'Lucifuge', 600, 600, 400, 50, 10000, 10000, 2);
END;
if (dobatch($query) == 1) { echo "Monsters table populated.<br />"; } else { echo "Error populating Monsters table."; }
unset($query);
}

$query = <<<END
CREATE TABLE `$news` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `postdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `content` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
END;
if (dobatch($query) == 1) { echo "News table created.<br />"; } else { echo "Error creating News table."; }
unset($query);

$query = <<<END
INSERT INTO `$news` VALUES (1, '2004-01-01 12:00:00', 'This is the first news post. Please use the admin control panel to add another one and make this one go away.');
END;
if (dobatch($query) == 1) { echo "News table populated.<br />"; } else { echo "Error populating News table."; }
unset($query);

$query = <<<END
CREATE TABLE `$spells` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `mp` smallint(5) unsigned NOT NULL default '0',
  `attribute` smallint(5) unsigned NOT NULL default '0',
  `type` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
END;
if (dobatch($query) == 1) { echo "Spells table created.<br />"; } else { echo "Error creating Spells table."; }
unset($query);

if ($full == true) {
$query = <<<END
INSERT INTO `$spells` VALUES (1, 'Heal', 5, 10, 1);
INSERT INTO `$spells` VALUES (2, 'Revive', 10, 25, 1);
INSERT INTO `$spells` VALUES (3, 'Life', 25, 50, 1);
INSERT INTO `$spells` VALUES (4, 'Breath', 50, 100, 1);
INSERT INTO `$spells` VALUES (5, 'Gaia', 75, 150, 1);
INSERT INTO `$spells` VALUES (6, 'Hurt', 5, 15, 2);
INSERT INTO `$spells` VALUES (7, 'Pain', 12, 35, 2);
INSERT INTO `$spells` VALUES (8, 'Maim', 25, 70, 2);
INSERT INTO `$spells` VALUES (9, 'Rend', 40, 100, 2);
INSERT INTO `$spells` VALUES (10, 'Chaos', 50, 130, 2);
INSERT INTO `$spells` VALUES (11, 'Sleep', 10, 5, 3);
INSERT INTO `$spells` VALUES (12, 'Dream', 30, 9, 3);
INSERT INTO `$spells` VALUES (13, 'Nightmare', 60, 13, 3);
INSERT INTO `$spells` VALUES (14, 'Craze', 10, 10, 4);
INSERT INTO `$spells` VALUES (15, 'Rage', 20, 25, 4);
INSERT INTO `$spells` VALUES (16, 'Fury', 30, 50, 4);
INSERT INTO `$spells` VALUES (17, 'Ward', 10, 10, 5);
INSERT INTO `$spells` VALUES (18, 'Fend', 20, 25, 5);
INSERT INTO `$spells` VALUES (19, 'Barrier', 30, 50, 5);
END;
if (dobatch($query) == 1) { echo "Spells table populated.<br />"; } else { echo "Error populating Spells table."; }
unset($query);
}

$query = <<<END
CREATE TABLE `$towns` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `latitude` smallint(6) NOT NULL default '0',
  `longitude` smallint(6) NOT NULL default '0',
  `innprice` tinyint(4) NOT NULL default '0',
  `mapprice` smallint(6) NOT NULL default '0',
  `travelpoints` smallint(5) unsigned NOT NULL default '0',
  `itemslist` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
END;
if (dobatch($query) == 1) { echo "Towns table created.<br />"; } else { echo "Error creating Towns table."; }
unset($query);

if ($full == true) {
$query = <<<END
INSERT INTO `$towns` VALUES (1, 'Midworld', 0, 0, 5, 0, 0, '1,2,3,17,18,19,28,29');
INSERT INTO `$towns` VALUES (2, 'Roma', 30, 30, 10, 25, 5, '2,3,4,18,19,29');
INSERT INTO `$towns` VALUES (3, 'Bris', 70, -70, 25, 50, 15, '2,3,4,5,18,19,20,29.30');
INSERT INTO `$towns` VALUES (4, 'Kalle', -100, 100, 40, 100, 30, '5,6,8,10,12,21,22,23,29,30');
INSERT INTO `$towns` VALUES (5, 'Narcissa', -130, -130, 60, 500, 50, '4,7,9,11,13,21,22,23,29,30,31');
INSERT INTO `$towns` VALUES (6, 'Hambry', 170, 170, 90, 1000, 80, '10,11,12,13,14,23,24,30,31');
INSERT INTO `$towns` VALUES (7, 'Gilead', 200, -200, 100, 3000, 110, '12,13,14,15,24,25,26,32');
INSERT INTO `$towns` VALUES (8, 'Endworld', -250, -250, 125, 9000, 160, '16,27,33');
END;
if (dobatch($query) == 1) { echo "Towns table populated.<br />"; } else { echo "Error populating Towns table."; }
unset($query);
}

$query = <<<END
CREATE TABLE `$users` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `verify` varchar(8) NOT NULL default '0',
  `charname` varchar(30) NOT NULL default '',
  `regdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `onlinetime` datetime NOT NULL default '0000-00-00 00:00:00',
  `authlevel` tinyint(3) unsigned NOT NULL default '0',
  `latitude` smallint(6) NOT NULL default '0',
  `longitude` smallint(6) NOT NULL default '0',
  `difficulty` tinyint(3) unsigned NOT NULL default '0',
  `charclass` tinyint(4) unsigned NOT NULL default '0',
  `currentaction` varchar(30) NOT NULL default 'In Town',
  `currentfight` tinyint(4) unsigned NOT NULL default '0',
  `currentmonster` smallint(6) unsigned NOT NULL default '0',
  `currentmonsterhp` smallint(6) unsigned NOT NULL default '0',
  `currentmonstersleep` tinyint(3) unsigned NOT NULL default '0',
  `currentmonsterimmune` tinyint(4) NOT NULL default '0',
  `currentuberdamage` tinyint(3) unsigned NOT NULL default '0',
  `currentuberdefense` tinyint(3) unsigned NOT NULL default '0',
  `currenthp` smallint(6) unsigned NOT NULL default '15',
  `currentmp` smallint(6) unsigned NOT NULL default '0',
  `currenttp` smallint(6) unsigned NOT NULL default '10',
  `maxhp` smallint(6) unsigned NOT NULL default '15',
  `maxmp` smallint(6) unsigned NOT NULL default '0',
  `maxtp` smallint(6) unsigned NOT NULL default '10',
  `level` smallint(5) unsigned NOT NULL default '1',
  `gold` mediumint(8) unsigned NOT NULL default '100',
  `experience` mediumint(8) unsigned NOT NULL default '0',
  `goldbonus` smallint(5) NOT NULL default '0',
  `expbonus` smallint(5) NOT NULL default '0',
  `strength` smallint(5) unsigned NOT NULL default '5',
  `dexterity` smallint(5) unsigned NOT NULL default '5',
  `attackpower` smallint(5) unsigned NOT NULL default '5',
  `defensepower` smallint(5) unsigned NOT NULL default '5',
  `weaponid` smallint(5) unsigned NOT NULL default '0',
  `armorid` smallint(5) unsigned NOT NULL default '0',
  `shieldid` smallint(5) unsigned NOT NULL default '0',
  `slot1id` smallint(5) unsigned NOT NULL default '0',
  `slot2id` smallint(5) unsigned NOT NULL default '0',
  `slot3id` smallint(5) unsigned NOT NULL default '0',
  `weaponname` varchar(30) NOT NULL default 'None',
  `armorname` varchar(30) NOT NULL default 'None',
  `shieldname` varchar(30) NOT NULL default 'None',
  `slot1name` varchar(30) NOT NULL default 'None',
  `slot2name` varchar(30) NOT NULL default 'None',
  `slot3name` varchar(30) NOT NULL default 'None',
  `dropcode` mediumint(8) unsigned NOT NULL default '0',
  `spells` varchar(50) NOT NULL default '0',
  `towns` varchar(50) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
END;
if (dobatch($query) == 1) { echo "Users table created.<br />"; } else { echo "Error creating Users table."; }
unset($query);
    
    global $start;
    $time = round((getmicrotime() - $start), 4);
    echo "<br />Database setup complete in $time seconds.<br /><br /><a href=\"install.php?page=3\">Click here to continue with installation.</a></body></html>";
    die();
    
}

function third() { // Third page: gather user info for admin account.

$page = <<<END
<html>
<head>
<title>Dragon Knight Installation</title>
</head>
<body>
<b>Dragon Knight Installation: Page Three</b><br /><br />
Now you must create an administrator account so you can use the control panel. Fill out the form below to create your account. You will be able to customize the class names through the control panel once your admin account is created.<br /><br />
<form action="install.php?page=4" method="post">
<table width="50%">
<tr><td width="20%" style="vertical-align:top;">Username:</td><td><input type="text" name="username" size="30" maxlength="30" /><br /><br /><br /></td></tr>
<tr><td style="vertical-align:top;">Password:</td><td><input type="password" name="password1" size="30" maxlength="30" /></td></tr>
<tr><td style="vertical-align:top;">Verify Password:</td><td><input type="password" name="password2" size="30" maxlength="30" /><br /><br /><br /></td></tr>
<tr><td style="vertical-align:top;">Email Address:</td><td><input type="text" name="email1" size="30" maxlength="100" /></td></tr>
<tr><td style="vertical-align:top;">Verify Email:</td><td><input type="text" name="email2" size="30" maxlength="100" /><br /><br /><br /></td></tr>
<tr><td style="vertical-align:top;">Character Name:</td><td><input type="text" name="charname" size="30" maxlength="30" /></td></tr>
<tr><td style="vertical-align:top;">Character Class:</td><td><select name="charclass"><option value="1">Mage</option><option value="2">Warrior</option><option value="3">Paladin</option></select></td></tr>
<tr><td style="vertical-align:top;">Difficulty:</td><td><select name="difficulty"><option value="1">Easy</option><option value="2">Medium</option><option value="3">Hard</option></select></td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="Submit" /> <input type="reset" name="reset" value="Reset" /></td></tr>
</table>
</form>
</body>
</html>
END;
echo $page;
die();

}

function fourth() { // Final page: insert new user row, congratulate the person on a job well done.
    
    extract($_POST);
    if (!isset($username)) { die("Username is required."); }
    if (!isset($password1)) { die("Password is required."); }
    if (!isset($password2)) { die("Verify Password is required."); }
    if ($password1 != $password2) { die("Passwords don't match."); }
    if (!isset($email1)) { die("Email is required."); }
    if (!isset($email2)) { die("Verify Email is required."); }
    if ($email1 != $email2) { die("Emails don't match."); }
    if (!isset($charname)) { die("Character Name is required."); }
    $password = md5($password1);
    
    global $dbsettings;
    $users = $dbsettings["prefix"] . "_users";
    $query = mysql_query("INSERT INTO $users SET id='1',username='$username',password='$password',email='$email1',verify='1',charname='$charname',charclass='$charclass',regdate=NOW(),onlinetime=NOW(),authlevel='1'") or die(mysql_error());

$page = <<<END
<html>
<head>
<title>Dragon Knight Installation</title>
</head>
<body>
<b>Dragon Knight Installation: Page Four</b><br /><br />
Your admin account was created successfully. Installation is complete.<br /><br />
Be sure to delete install.php from your Dragon Knight directory for security purposes.<br /><br />
You are now ready to <a href="index.php">play the game</a>. Note that you must log in through the public section before being allowed into the control panel. Once logged in, an "Admin" link will appear in the Functions box of the left sidebar panel.<br /><br/>
Thank you for using Dragon Knight!<br /><br />-----<br /><br />
<b>Optional:</b> Dragon Knight is a free product, and does not require registration of any sort. However, there is an 
optional "call home" function in the installer, which notifies the author of your game installation. The ONLY information 
transmitted with this function is the URL to your game. This is included mainly to satisfy the author's curiosity about
how many copies of the game are being installed and used. If you choose to submit your URL to the author, please
<a href="install.php?page=5">click here</a>.
</body>
</html>
END;

    echo $page;
    die();

}

function fifth() { // Call Home function.
    
    $url = "http://".$_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"];
    if (mail("jamin@se7enet.com", "Dragon Knight Call Home", "$url") != true) { die("Dragon Knight was unable to send your URL. Please go back and try again, or just continue on to <a href=\"index.php\">the game</a>."); }
    
$page = <<<END
<html>
<head>
<title>Dragon Knight Installation</title>
</head>
<body>
<b>Dragon Knight Installation: Page Five</b><br /><br />
Thank you for submitting your URL!<br /><br />
You are now ready to <a href="index.php">play the game</a>. Note that you must log in through the public section before being allowed into the control panel. Once logged in, an "Admin" link will appear in the Functions box of the left sidebar panel.
</body>
</html>
END;
    
    echo $page;
    die();
    
}

?>                                                                           