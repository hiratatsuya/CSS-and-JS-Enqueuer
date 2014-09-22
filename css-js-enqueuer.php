<?php
/*
Plugin Name: CSS and JS Enqueuer
Plugin URI: http://wordpress.org/plugins/css-js-enqueuer/
Description: CSS and JS Enqueuer plugin will enable to enqueue  external CSS and JS, and edit it.
Version: 0.1
Author: hiratatsuya
Author URI: https://profiles.wordpress.org/hiratatsuya
Domain Path: /languages
Text Domain: css-js-enqueuer
*/

define( 'CSSandJSenqueuer_URL',   plugins_url( '', __FILE__) );
define( 'CSSandJSenqueuer_PATH', dirname( __FILE__ ) );

	add_action( 'plugins_loaded', 'css_js_enqueuer_load_textdomain' );
		function css_js_enqueuer_load_textdomain() {
			load_plugin_textdomain( 'css-js-enqueuer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

	function css_js_enqueuer_default_plugin_options() {
		$css_js_enqueuer_args = array(
			'enqueue_css' => '',
			'enqueue_js' => '',
			'custom_css' => '/* CSS */',
			'custom_js' => '/* JS */',
		);
		return apply_filters( 'css_js_enqueuer_default_plugin_options', $css_js_enqueuer_args );
	}

	function css_js_enqueuer_get_plugin_options() {
		return get_option( 'css_js_enqueuer_plugin_options', css_js_enqueuer_default_plugin_options() );
	}

	add_action( 'admin_init', 'css_js_enqueuer_plugin_options_init' );
		function css_js_enqueuer_plugin_options_init() {
			$css_js_enqueuer_options = css_js_enqueuer_get_plugin_options();

			register_setting(
				'css_js_enqueuer_options',
				'css_js_enqueuer_plugin_options',
				'css_js_enqueuer_plugin_options_validate'
			);

			add_settings_section(
				'general',
				'',
				'__return_false',
				'css_js_enqueuer_options'
			);

			add_settings_field( 'enqueue_css', __( 'enqueue_css', 'css-js-enqueuer' ), 'css_js_enqueuer_settings_field_enqueue_css', 'css_js_enqueuer_options', 'general' );
			add_settings_field( 'enqueue_js', __( 'enqueue_js', 'css-js-enqueuer' ), 'css_js_enqueuer_settings_field_enqueue_js', 'css_js_enqueuer_options', 'general' );
			add_settings_field( 'custom_css', __( 'custom_css', 'css-js-enqueuer' ), 'css_js_enqueuer_settings_field_custom_css', 'css_js_enqueuer_options', 'general' );
			add_settings_field( 'custom_js', __( 'custom_js', 'css-js-enqueuer' ), 'css_js_enqueuer_settings_field_custom_js', 'css_js_enqueuer_options', 'general' );
		}

	add_action( 'admin_menu', 'css_js_enqueuer_plugin_options_add_page' );
		function css_js_enqueuer_plugin_options_add_page() {
			add_theme_page(
				__( 'Enqueue CSS, JS', 'css-js-enqueuer' ),
				__( 'Enqueue CSS, JS', 'css-js-enqueuer' ),
				'edit_theme_options',
				'css_js_enqueuer_options',
				'css_js_enqueuer_plugin_options_render_page'
			);
		}

	$css_js_enqueuer_request_uri = site_url( $_SERVER[ 'REQUEST_URI' ] );
	if ( ! is_admin() && ! preg_match( '/(wp\-admin|wp\-login\.php)/i', $css_js_enqueuer_request_uri ) ) {
		add_action( 'wp_before_admin_bar_render', 'css_js_enqueuer_plugin_options_add_admin_bar' );
		function css_js_enqueuer_plugin_options_add_admin_bar() {
			global $wp_admin_bar;
			$css_js_enqueuer_args = array (
				'parent' => 'site-name',
				'id' => 'css-js-enqueuer-options',
				'title' => __( 'Enqueue CSS, JS', 'css-js-enqueuer' ),
				'href' => admin_url( 'themes.php?page=css_js_enqueuer_options' ),
			);
			$wp_admin_bar -> add_menu( $css_js_enqueuer_args );
		}
	}

	function css_js_enqueuer_plugin_options_render_page() {
		wp_enqueue_style( 'codemirror-4.6', CSSandJSenqueuer_URL . '/js/codemirror-4.6/lib/codemirror.css' );
		wp_enqueue_script( 'codemirror-4.6', CSSandJSenqueuer_URL . '/js/codemirror-4.6/lib/codemirror.js' );
		wp_enqueue_script( 'codemirror-JS', CSSandJSenqueuer_URL . '/js/codemirror-4.6/mode/javascript/javascript.js' );
		wp_enqueue_script( 'codemirror-CSS', CSSandJSenqueuer_URL . '/js/codemirror-4.6/mode/css/css.js' );
		wp_enqueue_script( 'css-js-enqueuer-JS', CSSandJSenqueuer_URL . '/js/css-js-enqueuer.js', array( 'jquery' ) );

		?>
		<h2><?php _e( 'Enqueue CSS, JS', 'css-js-enqueuer' ); ?></h2>
		<div class="wrap">
			<?php settings_errors(); ?>
			<form method="post" action="options.php">
			<?php
				settings_fields( 'css_js_enqueuer_options' );
				do_settings_sections( 'css_js_enqueuer_options' );
				submit_button();
			?>
			</form>
		</div>
		<?php
	}

		function css_js_enqueuer_settings_field_enqueue_css() {
			$css_js_enqueuer_options = css_js_enqueuer_get_plugin_options();
			echo '<textarea name="css_js_enqueuer_plugin_options[enqueue_css]" id="enqueue_css" class="large-text" cols="50" rows="10" class="large-text">' . esc_textarea( $css_js_enqueuer_options[ 'enqueue_css' ] ) . '</textarea><br />' ;
			_e( 'e.g. ', 'css-js-enqueuer' );
			_e( 'handle=css&src=http://www.example.com/style.css&deps=&ver=&media=all<br />', 'css-js-enqueuer' );
			_e( 'c.f. ', 'css-js-enqueuer' );
			echo '<a href="' . esc_url( __( 'http://codex.wordpress.org/Function_Reference/wp_enqueue_style', 'css-js-enqueuer' ) ) . '" target="_blank">' . __( 'Codex : Function Reference/wp enqueue style', 'css-js-enqueuer' ) . '</a><br />' ;
			_e( 'if you want to use external style, then you must set some value.', 'css-js-enqueuer' );
		}

		function css_js_enqueuer_settings_field_enqueue_js() {
			$css_js_enqueuer_options = css_js_enqueuer_get_plugin_options();
			echo '<textarea name="css_js_enqueuer_plugin_options[enqueue_js]" id="enqueue_js" class="large-text" cols="50" rows="10" class="large-text">' . esc_textarea( $css_js_enqueuer_options[ 'enqueue_js' ] ) . '</textarea><br />' ;
			_e( 'e.g. ', 'css-js-enqueuer' );
			_e( 'handle=js&src=http://www.example.com/script.js&deps=jquery&ver=&in_footer=true<br />', 'css-js-enqueuer' );
			_e( 'c.f. ', 'css-js-enqueuer' );
			echo '<a href="' . esc_url( __( 'http://codex.wordpress.org/Function_Reference/wp_enqueue_script', 'css-js-enqueuer' ) ) . '" target="_blank">' . __( 'Codex : Function Reference/wp enqueue script', 'css-js-enqueuer' ) . '</a><br />' ;
			_e( 'if you want to use external script, then you must set some value.', 'css-js-enqueuer' );

		}

		function css_js_enqueuer_settings_field_custom_css() {
			$css_js_enqueuer_options = css_js_enqueuer_get_plugin_options();
			echo '<textarea name="css_js_enqueuer_plugin_options[custom_css]" id="custom_css" class="large-text" cols="50" rows="10" class="large-text">' . esc_textarea( $css_js_enqueuer_options[ 'custom_css' ] ) . '</textarea>' ;
		}

		function css_js_enqueuer_settings_field_custom_js() {
			$css_js_enqueuer_options = css_js_enqueuer_get_plugin_options();
			echo '<textarea name="css_js_enqueuer_plugin_options[custom_js]" id="custom_js" class="large-text" cols="50" rows="10" class="large-text">' . esc_textarea( $css_js_enqueuer_options[ 'custom_js' ] ) . '</textarea>' ;
		}

	function css_js_enqueuer_plugin_options_validate( $css_js_enqueuer_input ) {
		$css_js_enqueuer_output = $css_js_enqueuer_defaults = css_js_enqueuer_default_plugin_options();

		if ( isset( $css_js_enqueuer_input[ 'enqueue_css' ] ) )
			$css_js_enqueuer_output[ 'enqueue_css' ] = $css_js_enqueuer_input[ 'enqueue_css' ];

		if ( isset( $css_js_enqueuer_input[ 'enqueue_js' ] ) )
			$css_js_enqueuer_output[ 'enqueue_js' ] = $css_js_enqueuer_input[ 'enqueue_js' ];

		if ( isset( $css_js_enqueuer_input[ 'custom_css' ] ) )
			$css_js_enqueuer_output[ 'custom_css' ] = $css_js_enqueuer_input[ 'custom_css' ];

		if ( isset( $css_js_enqueuer_input[ 'custom_js' ] ) )
			$css_js_enqueuer_output[ 'custom_js' ] = $css_js_enqueuer_input[ 'custom_js' ];

		return apply_filters( 'css_js_enqueuer_plugin_options_validate', $css_js_enqueuer_output, $css_js_enqueuer_input, $css_js_enqueuer_defaults );
	}

	add_filter( 'wp_loaded', 'css_js_enqueuer_rewrite_rules' );
	if ( ! function_exists( 'css_js_enqueuer_rewrite_rules' ) ) {
		function css_js_enqueuer_rewrite_rules() {
			register_activation_hook( __FILE__, 'flush_rewrite_rules' );
			new CSSandJSenqueuer_AddRewriteRules( 'css_js_enqueuer.css$', 'css-js-enqueuer-css', 'css_js_enqueuer_css' );
			new CSSandJSenqueuer_AddRewriteRules( 'css_js_enqueuer.js$', 'css-js-enqueuer-js', 'css_js_enqueuer_js' );
		}
	}

		function css_js_enqueuer_css() {
			header( 'Content-type:text/css;charset=UTF-8' );
			$css_js_enqueuer_options = css_js_enqueuer_get_plugin_options();
			echo $css_js_enqueuer_options[ 'custom_css' ] ;
			exit;
		}

		function css_js_enqueuer_js() {
			header( 'Content-type:text/javascript;charset=UTF-8' );
			$css_js_enqueuer_options = css_js_enqueuer_get_plugin_options();
			echo $css_js_enqueuer_options[ 'custom_js' ] ;
			exit;
		}

	function css_js_enqueuer_rewrite_url() {
		$css_js_enqueuer_home = home_url( '/' );
		$css_js_enqueuer_url = array(
			'css-js-enqueuer-css' => $css_js_enqueuer_home . '?css-js-enqueuer-css=true',
			'css-js-enqueuer-js' => $css_js_enqueuer_home . '?css-js-enqueuer-js=true',
		);
		return $css_js_enqueuer_url ;
	}

		add_action( 'wp_enqueue_scripts', 'css_js_enqueuer_enqueue_styles' );
			function css_js_enqueuer_enqueue_styles() {
				$css_js_enqueuer_request_uri = site_url( $_SERVER[ 'REQUEST_URI' ] );
				if ( ! is_admin() && ! preg_match( '/(wp\-admin|wp\-login\.php)/i', $css_js_enqueuer_request_uri ) ) {
					$css_js_enqueuer_options = css_js_enqueuer_get_plugin_options();
					$css_js_enqueuer_enqueue_styles = explode( "\n", $css_js_enqueuer_options[ 'enqueue_css' ] );
					foreach( $css_js_enqueuer_enqueue_styles as $css_js_enqueuer_enqueue_style ) {
						css_js_enqueuer_enqueue_style( $css_js_enqueuer_enqueue_style );
					}
					$css_js_enqueuer_url = css_js_enqueuer_rewrite_url();
					wp_enqueue_style( 'css_js_enqueuer_css', $css_js_enqueuer_url[ 'css-js-enqueuer-css' ] );
				}
			}

			function css_js_enqueuer_enqueue_style( $css_js_enqueuer_args ) {
				$css_js_enqueuer_defaults = array (
					'handle' => '',
					'src' => false,
					'deps' => false,
					'ver' => false,
					'media' => 'all',
				);
				$css_js_enqueuer_tmp = wp_parse_args( $css_js_enqueuer_args, $css_js_enqueuer_defaults );
				extract( $css_js_enqueuer_tmp, EXTR_SKIP );
				wp_enqueue_style( $css_js_enqueuer_tmp[ 'handle' ], $css_js_enqueuer_tmp[ 'src' ], $css_js_enqueuer_tmp[ 'deps' ], $css_js_enqueuer_tmp[ 'ver' ], $css_js_enqueuer_tmp[ 'media' ] );
			}

		add_action( 'wp_enqueue_scripts', 'css_js_enqueuer_enqueue_scripts' );
			function css_js_enqueuer_enqueue_scripts() {
				$css_js_enqueuer_request_uri = site_url( $_SERVER[ 'REQUEST_URI' ] );
				if ( ! is_admin() && ! preg_match( '/(wp\-admin|wp\-login\.php)/i', $css_js_enqueuer_request_uri ) ) {
					$css_js_enqueuer_options = css_js_enqueuer_get_plugin_options();
					$css_js_enqueuer_enqueue_scripts = explode( "\n", $css_js_enqueuer_options[ 'enqueue_js' ] );
					foreach ( $css_js_enqueuer_enqueue_scripts as $css_js_enqueuer_enqueue_script ) {
						css_js_enqueuer_enqueue_script( $css_js_enqueuer_enqueue_script );
					}
					$css_js_enqueuer_url = css_js_enqueuer_rewrite_url();
					wp_enqueue_script( 'css_js_enqueuer_js', $css_js_enqueuer_url[ 'css-js-enqueuer-js' ] , array( 'jquery' ), '', true );
				}
			}

			function css_js_enqueuer_enqueue_script( $css_js_enqueuer_args ) {
				$css_js_enqueuer_defaults = array (
					'handle' => '',
					'src' => false,
					'deps' => false,
					'ver' => false,
					'in_footer' => false,
				);
				$css_js_enqueuer_tmp = wp_parse_args( $css_js_enqueuer_args, $css_js_enqueuer_defaults );
				extract( $css_js_enqueuer_tmp, EXTR_SKIP );
				wp_enqueue_script( $css_js_enqueuer_tmp[ 'handle' ], $css_js_enqueuer_tmp[ 'src' ], $css_js_enqueuer_tmp[ 'deps' ], $css_js_enqueuer_tmp[ 'ver' ], $css_js_enqueuer_tmp[ 'in_footer' ] );
			}

	// AddRewriteRules
	if ( ! class_exists( 'CSSandJSenqueuer_AddRewriteRules' ) ) {
		class CSSandJSenqueuer_AddRewriteRules {
			private $css_js_enqueuer_rule = null;
			private $css_js_enqueuer_query = null;
			private $css_js_enqueuer_callback = null;

			function __construct( $css_js_enqueuer_rule, $css_js_enqueuer_query, $css_js_enqueuer_callback ) {
				$this -> rule = $css_js_enqueuer_rule;
				$this -> query = $css_js_enqueuer_query;
				$this -> callback = $css_js_enqueuer_callback;
				add_filter( 'query_vars', array( &$this, 'query_vars' ) );
				add_action( 'generate_rewrite_rules', array( &$this, 'generate_rewrite_rules' ) );
				add_action( 'wp', array( &$this, 'wp' ) );
			}

			public function generate_rewrite_rules( $wp_rewrite ) {
				$new_rules[ $this -> rule ] = $wp_rewrite -> index . '?' . (
					strpos( $this->query, '=' ) === FALSE ? $this -> query . '=1' : $this -> query
				);
				$wp_rewrite -> rules = $new_rules + $wp_rewrite -> rules;
			}

			private function parse_query( $css_js_enqueuer_query ) {
				$css_js_enqueuer_query = explode( '&', $css_js_enqueuer_query );
				$css_js_enqueuer_query = explode( '=', is_array( $css_js_enqueuer_query ) && isset( $css_js_enqueuer_query[ 0 ] ) ? $css_js_enqueuer_query[ 0 ] : $css_js_enqueuer_query );
				return is_array( $css_js_enqueuer_query ) && isset( $css_js_enqueuer_query[ 0 ] ) ? $css_js_enqueuer_query[ 0 ] : $css_js_enqueuer_query ;
			}

			public function query_vars( $css_js_enqueuer_vars ) {
				$css_js_enqueuer_vars[] = $this -> parse_query( $this -> query );
				return $css_js_enqueuer_vars ;
			}

			public function wp() {
				if ( get_query_var( $this -> parse_query( $this -> query ) ) ) {
					call_user_func( $this -> callback );
				}
			}
		}
	}
