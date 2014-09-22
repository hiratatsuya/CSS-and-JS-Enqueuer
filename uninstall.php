<?php
	if ( ! defined ( 'WP_UNINSTALL_PLUGIN' ) ) {
	    exit;
	}

	$css_js_enqueuer_options = array(
		'css_js_enqueuer_plugin_options',
	);

	foreach ( $css_js_enqueuer_options as $css_js_enqueuer_option ) {
		delete_option( $css_js_enqueuer_option );
	}
