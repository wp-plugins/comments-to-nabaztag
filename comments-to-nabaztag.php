<?php
/*
Plugin Name: Comments to Nabaztag
Plugin URI: http://web2.0du.de/
Description: This plugin forwards your comments to your Nabaztag.
Version: 0.1
Author: Robert Curth
Author URI: http://web2.0du.de
*/

/*
    Copyright (C) 2009 Robert Curth

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

include_once("lib/nabaztagApi.class.php");

//  Get i18n strings
$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain( 'nabaztag', 'wp-content/plugins/' . $plugin_dir. '/i18n/', $plugin_dir );



/**
 * Send the comment to nabaztag if it is not spam
 * @param int comment_id
 * @param mixed $approved
 */

function send_to_nabaztag($comment_id, $approved)
{
	global $wpdb;
	$nab_id = get_option("nab_id");
	$nab_token = get_option("nab_token");
	// Get comment and send notification if comment was no spam
	if($approved !== "spam" && $nab_id && $nab_token)
	{
		$comment = $wpdb->get_row("SELECT * FROM $wpdb->comments WHERE comment_ID='{$comment_id}' LIMIT 1");
		$api = new NabaztagApi($nab_id, $nab_token); 
		$message = sprintf("%s hat folgenden Kommentar geschrieben: %s", $comment->comment_author, $comment->comment_content);
		$api->sendTts($message);
	}
}

function nabaztag_option_page() 
{
	if (isset($_POST['nab_id']))
	{
		$nab_id = mysql_escape_string($_POST['nab_id']);
		$nab_token = mysql_escape_string($_POST['nab_token']);
		update_option("nab_id",$nab_id);
		update_option("nab_token",$nab_token);
	}
	$nab_id = get_option("nab_id");
	$nab_token = get_option("nab_token");
	$api = new NabaztagApi($nab_id, $nab_token); 	
	$voices = $api->getVoicesList();
	include_once("template/admin_template.php");
}

function nabaztag_add_menu() 
{
	add_option("nab_id","Id deine Nabaztags"); 
	add_option("nab_token","Token deine Nabaztags"); 	
	add_options_page('Nabaztag-Comments', 'Nabaztag Comments', 9, __FILE__, 'nabaztag_option_page'); //optionenseite hinzuf�gen
}


add_action("comment_post", "send_to_nabaztag", 10, 2);
add_action('admin_menu', 'nabaztag_add_menu');