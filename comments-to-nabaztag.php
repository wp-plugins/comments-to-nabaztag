<?php
/*
Plugin Name: Comments to Nabaztag
Plugin URI: http://web2.0du.de/
Description: This plugin forwards your comments to your Nabaztag.
Version: 0.1.2
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

include_once("lib/NabPHP.class.php");
include_once("lib/NabChor.class.php");
$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain('nabaztag', '', $plugin_dir);

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
		
		// Set optional parameters
		$options = array();
		$voice = get_option("nab_voice");
		if($voice)
		{
		  $options["voice"] = $voice;
		}
		
		$api = new NabPHP($nab_id, $nab_token, $options); 
		$message = sprintf(get_option("nab_message"), $comment->comment_author, $comment->comment_content);
		
		$api->sendTts($message);
	}
}

function nabaztag_option_page() 
{
	if (isset($_POST['nab_id']))
	{
		$nab_id = mysql_escape_string($_POST['nab_id']);
		$nab_token = mysql_escape_string($_POST['nab_token']);
		
		// Validate credentials
		$api = new NabPHP($nab_id, $nab_token);
		if($api->validateCredentials())
		{	
		  update_option("nab_id",$nab_id);
		  update_option("nab_token",$nab_token);
		  update_option("nab_voices_cache", implode(",", $api->getVoicesList()));
		  update_option("nab_valid", 1);
		  update_option("nab_name", $api->getRabbitName());
		  $flash_2 = true;		  
		}
		else
		{
		  $flash_1 = true;
		}
	}
	if (isset($_POST['voice']))
	{
	  $voice = mysql_escape_string($_POST['voice']);
	  update_option("nab_voice",$voice);
	}
	
	if (isset($_POST['nb_preview']))
	{
	  $options = array("voice" => $_POST['voice']);
	  $api = new NabPHP(get_option("nab_id"), get_option("nab_token"), $options);
	  $message = sprintf($_POST['nab_message'], "Robert Curth", __("This is a test. Do you like this voice? Then use submit to save these settings!", "nabaztag"));
	  $api->sendTts($message);
	}
	
	include_once("template/admin_template.php");
}

function nabaztag_add_menu() 
{
	add_option("nab_id",""); 
	add_option("nab_token","");
	add_option("nab_voice", "");
	add_option("nab_valid", 0);
	add_option("nab_voices_cache", "");
	add_option("nab_name", "");
	add_option("nab_message", __("%s has written following comment: %s", "nabaztag"));	
	add_options_page('Nabaztag-Comments', 'Nabaztag Comments', 9, __FILE__, 'nabaztag_option_page'); //optionenseite hinzuf�gen
}

add_action("comment_post", "send_to_nabaztag", 10, 2);
add_action('admin_menu', 'nabaztag_add_menu');