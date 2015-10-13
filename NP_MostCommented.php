<?php
/*
 * Plugin for Nucleus CMS (http://plugins.nucleuscms.org/)
 * Original: Copyright (C) 2005 Jeroen Budts (The Nucleus Group)
 * Changes:  Copyright (C) 2015 Matthew Brown
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * see http://nucleuscms.org/license.txt for the full license
 */

/**
 * A plugin for Nucleus CMS to show a list of most commented items.
 * @license  http://www.gnu.org/licenses/gpl.txt
 * @author   Lord Matt
 * @version  2.0
 */
class NP_MostCommented extends NucleusPlugin {

	function getName() 			{ return 'Most Commented 2.0';    }
	function getAuthor()  		{ return 'Lord Matt, TeRanEX, mod by Edmond Hui (admun)'; }
	function getURL() 			{ return 'https://github.com/Lord-Matt-NucleusCMS-Stuff/NP_MostCommented-2.0/'; }
	function getVersion() 		{ return '2.0'; }
	function getDescription() 	{ return 'Shows a list of items with the most comments on all blogs taking age of item and age of comments into account'; }

	function supportsFeature($what) {
		switch($what) {
		case 'SqlTablePrefix':
			return 1;
		default:
			return 0;
		}
	}

	function install() {
		$this->createOption('header','Header formatting','textarea','<ol>');
		$this->createOption('link','Link formatting','textarea','<li><a href="%l">%p</a> [%c comments]</li>');
		$this->createOption('footer','Footer formatting','textarea','</ol>');
	}


	function doSkinVar($skinType, $numOfPostsToShow) {
		global $blog;
		if ($numOfPostsToShow <= 0) {
			$numOfPostsToShow = 10;
		}

		$q = 	"SELECT inumber as id, ititle as title, ".
                        "citem,COUNT(cnumber) as num_of_comments, ".
                        "SUM(SubComment.cVal)*POW(COUNT(cnumber),2)*SUM(SubComment.iVal) as CurrentVal ". 
                        "FROM ( ".
                        "SELECT *, ".
                        "SQRT(1.0 / POW((DATEDIFF(c.ctime,CURDATE()) / 365),2)) as cVal,". 
                        "SQRT(1.0 / POW((DATEDIFF(i.itime,CURDATE()) / 365),2)) as iVal ". 
                        "FROM ".sql_table('comment')." as c ". 
                        "INNER JOIN ".sql_table('item')." as i ". 
                        "ON i.inumber=c.citem) as SubComment ". 
                        "GROUP BY inumber, ititle ". 
                        "ORDER BY `CurrentVal` DESC ". 
                        "LIMIT 0, ".intval($numOfPostsToShow);

		$res = mysql_query($q);

		echo($this->getOption('header')) ;
		$link_templ = $this->getOption('link');

		while($row = mysql_fetch_array($res)) {
			$out = str_replace("%l", createItemLink($row[id]), $link_templ);
			$out = str_replace("%p", $row['title'], $out);
			$out = str_replace("%c", $row['num_of_comments'], $out);
			$out = str_replace("%s", $row['CurrentVal'], $out);
			echo($out);
		}

		echo($this->getOption('footer')) ;

	}
	
}
