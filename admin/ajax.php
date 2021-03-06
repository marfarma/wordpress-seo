<?php

function wpseo_set_option() {
	update_option($_POST['option'], $_POST['newval']);
	return 1;
	die();
}
add_action('wp_ajax_wpseo_set_option', 'wpseo_set_option');

function wpseo_set_ignore() {
	$options = get_option('wpseo');
	$options['ignore_'.$_POST['option']] = 'ignore';
	update_option('wpseo', $options);
	return 1;
	die();
}
add_action('wp_ajax_wpseo_set_ignore', 'wpseo_set_ignore');

function wpseo_ajax_generate_sitemap_callback() {
	$options = get_option('wpseo');
	$type = (isset($_POST['type'])) ? $_POST['type'] : '';
	
	if ($type == '') {
		global $wpseo_generate, $wpseo_echo;
		$wpseo_generate = true;
		$wpseo_echo = true;
		
		$mem_before = function_exists('memory_get_peak_usage') ? memory_get_peak_usage() : memory_get_usage();
		require_once WPSEO_PATH.'/sitemaps/xml-sitemap-class.php';
		$mem_after = function_exists('memory_get_peak_usage') ? memory_get_peak_usage() : memory_get_usage();
		echo number_format( ($mem_after - $mem_before) / 1024 ).'KB of memory used.';

	} else {
		global $wpseo_generate, $wpseo_echo;
		$wpseo_generate = true;
		$module_name = $type;
		if($type == 'kml' || $type == 'geo') {
			$module_name = 'local';
			$type = 'geo';
		}
		require_once WP_PLUGIN_DIR.'/wordpress-seo-modules/wpseo-' . $module_name . '/xml-' . $type . '-sitemap-class.php';
	}	
	die();
}
add_action('wp_ajax_wpseo_generate_sitemap', 'wpseo_ajax_generate_sitemap_callback');