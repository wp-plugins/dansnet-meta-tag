<?php
	/*
	Plugin Name: Dansnet Meta Tag
	Plugin URI: http://dansnet.de/dansnet-meta-tag
	Description: Dansnet Meta Tag is a lightweight plugin that allows you to generate HTML metadata to provide information for search engines like Google.
	Version: 1.0.2
	Author: Deniz Schmid
	Author URI: http://dansnet.de
	*/

	/*  Copyright 2015  Deniz Schmid  (email : deniz.schmid@dansnet.de)

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License, version 2, as 
		published by the Free Software Foundation.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/
	defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

	define(DANSNET_DMT_DIR, dirname(__FILE__).DIRECTORY_SEPARATOR);
	define(DANSNET_DMT_URL, plugins_url().DIRECTORY_SEPARATOR.basename(DANSNET_DMT_DIR).DIRECTORY_SEPARATOR);
	
	require_once DANSNET_DMT_DIR.'Settings.php';

	$dansnet_dmt_settings = new DANSNET_DMT_Settings;
	
	add_action( 'wp_head', array( &$dansnet_dmt_settings, 'load_meta_tags' ) );

?>