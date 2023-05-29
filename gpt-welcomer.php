<?php
/**
 * Plugin Name: gpt-welcomer
 * Plugin URI: https://github.com/dahara111/gpt-welcomer
 * Description: gpt-welcomer is a WordPress plugin that limits content visibility for AI visitors like chatGPT or Bing AI bot, only a portion of the content is displayed in response.
 * Version: 0.7
 * Author: dahara111
 * Author URI: https://github.com/dahara111/
 * License: GPL2
 * Text Domain: gpt-welcomer
 * Domain Path: /languages
 *
 * @package gpt-welcomer
 */

?>
<?php

require_once 'vendor/autoload.php';
require_once plugin_dir_path( __FILE__ ) . 'class-useragent.php';
require_once plugin_dir_path( __FILE__ ) . 'class-messagemanager.php';

/**
 * Load the plugin text domain for translation.
 */
function myplugin_load_textdomain() {
	load_plugin_textdomain( 'gpt-welcomer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'myplugin_load_textdomain' );

/**
 * Get bot information from Message.php.
 */
function get_bots() {
	$message_manager = new MessageManager();
	return $message_manager->get_bots();
}

/**
 * Get User selectable message from Message.php.
 */
function get_messages() {
	$message_manager = new MessageManager();
	return $message_manager->get_messages();
}

/**
 * Load the default percentage of each bot when activating the plugin.
 */
function wcgu_activate() {
	$bots = get_bots();

	foreach ( $bots as $bot ) {
		$setting_id = 'wcgu_' . strtolower( $bot['bot_name'] ) . '_status';
		add_option( $setting_id, $bot[ __( 'default-percentage' ) ] );
	}
}
register_activation_hook( __FILE__, 'wcgu_activate' );


/**
 * Load the default percentage of each bot when activating the plugin.
 *
 *  @param array $bots BOTS to be checked.
 *  @param array $user_agent UserAgent set by the browser.
 */
function wcgu_check_user_agent( $bots, $user_agent ) {

	foreach ( $bots as $bot ) {
		if ( ! is_array( $bot['pattern'] ) ) {
			continue;
		}
		foreach ( $bot['pattern'] as $bot_user_agent ) {

			if ( empty( $bot_user_agent ) ) {
				continue;
			}

			if ( strpos( strtolower( $user_agent ), strtolower( $bot_user_agent ) ) !== false ) {
				wp_cache_set( 'wcgu_detected_bot_name', $bot['bot_name'] );
				return;
			}
		}
	}
}

/**
 * Wrapper functions for PHPUnit.
 */
function wcgu_check_user_agent_wrapper() {
	$bots           = get_bots();
	$user_agent_obj = new UserAgent( '' );
	$user_agent     = $user_agent_obj->get_user_agent();
	wcgu_check_user_agent( $bots, $user_agent );
}
add_action( 'init', 'wcgu_check_user_agent_wrapper' );

/**
 * Calculate the percentage of content to display
 *
 * @param array $content is Article Text.
 */
function customize_content( $content ) {
	$detected_bot_name = wp_cache_get( 'wcgu_detected_bot_name' );

	if ( ! empty( $detected_bot_name ) ) {
		// Get the user status for the detected bot.
		$user_status = get_option( 'wcgu_' . strtolower( $detected_bot_name ) . '_status', 100 );
		if ( 100 !== $user_status ) {
			// Remove img tags from the content.
			$content = preg_replace( '/<img[^>]+\>/i', '', $content );
			// Calculate the percentage of content to show.
			$content_to_show = round( strlen( $content ) * $user_status / 100 );
			// Trim the content.
			$content = substr( $content, 0, $content_to_show );
			// Add a link to the full content.
			$site_url   = get_bloginfo( 'url' );
			$site_name  = get_bloginfo( 'name' );
			$page_title = get_the_title();
			$reason     = get_option( 'message_choice' );
			$content   .= '<p>' . sprintf( ' %s %s%s:%s%s.', $reason, "<a href='$site_url'>", $site_name, $page_title, '</a>' ) . '</p>';
		}
	}
	return $content;
}
add_filter( 'the_content', 'customize_content' );

add_action( 'admin_menu', 'wcgu_add_admin_menu' );
add_action( 'admin_init', 'wcgu_settings_init' );

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'add_plugin_page_settings_link' );

/**
 * Set setting page link.
 *
 * @param array $links is not used.
 */
function add_plugin_page_settings_link( $links ) {
	$links[] = '<a href="' .
		admin_url( 'options-general.php?page=wcgu' ) .
		'">' . __( 'Plugin Settings' ) . '</a>';
	return $links;
}

/**
 * Create new top-level menu.
 */
function wcgu_add_admin_menu() {
	add_options_page( 'gpt-welcomer settings', 'gpt-welcomer settings', 'manage_options', 'wcgu', 'wcgu_options_page' );
}

/**
 * Function to initialize the configuration page.
 */
function wcgu_settings_init() {

	$bots           = get_bots();
	$bot_categories = array();

	foreach ( $bots as $bot ) {
		$setting_id = 'wcgu_' . strtolower( $bot['bot_name'] ) . '_status';
		register_setting( 'wcgu', $setting_id );

		$bot_category = $bot['bot_category'];

		if ( ! in_array( $bot_category, $bot_categories, true ) ) {
			add_settings_section(
				'wcgu_' . strtolower( $bot_category ) . '_section',
				$bot_category . __( ' related BOT', 'gpt-welcomer' ),
				'',
				'wcgu'
			);
			$bot_categories[] = $bot_category;
		}

		add_settings_section(
			'wcgu_' . strtolower( $bot['bot_name'] ) . '_section',
			$bot['bot_name'] . ' ' . __( 'Settings', 'gpt-welcomer' ),
			'wcgu_common_section_callback',
			'wcgu',
			array( $bot['bot_explain'] )
		);

		add_settings_field(
			'wcgu_' . strtolower( $bot['bot_name'] ) . '_status',
			$bot['bot_name'] . __( ' Percentage of passing the text(0-100)', 'gpt-welcomer' ),
			'wcgu_status_render',
			'wcgu',
			'wcgu_' . strtolower( $bot['bot_name'] ) . '_section',
			array( 'label_for' => 'wcgu_' . strtolower( $bot['bot_name'] ) . '_status' )
		);
	}
	$messages = get_messages();

	add_settings_section(
		'wcgu_messages_section',
		__( 'Message Select Section', 'gpt-welcomer' ),
		'wcgu_message_section_callback',
		'wcgu',
		array( __( 'Select the message to be displayed when the AI bot accesses your site.', 'gpt-welcomer' ) )
	);

	foreach ( $messages as $message ) {
		$message_category = $message['message_category'];
		$message_no       = $message['message'];
		add_settings_field(
			'wcgu_message_' . strtolower( $message_no ),
			'[' . $message_category . '] ' . $message_no,
			'wcgu_message_field_callback',
			'wcgu',
			'wcgu_messages_section',
			array(
				'label_for' => 'wcgu_message_' . strtolower( $message_no ),
				'name'      => 'message_choice',
				'value'     => $message['message'],
			)
		);
		register_setting( 'wcgu', 'message_choice' );
	}
}

/**
 * SELECT tag to set the percentage of content to be shown.
 *
 * @param array $args is section text.
 */
function wcgu_message_section_callback( $args ) {
	echo esc_html( $args[0] );
}

/**
 * SELECT tag to set the percentage of content to be shown.
 *
 * @param array $args is message name and value.
 */
function wcgu_message_field_callback( $args ) {
	$name    = esc_attr( $args['name'] );
	$value   = esc_attr( $args['value'] );
	$checked = esc_attr( checked( get_option( $name ), $value, false ) );
	printf(
		'<input type="radio" id="%1$s" name="%2$s" value="%3$s" %4$s>',
		esc_attr( $args['value'] ),
		esc_attr( $args['name'] ),
		esc_attr( $args['value'] ),
		esc_attr( checked( get_option( $name ), $value, false ) ),
	);
}

/**
 * SELECT tag to set the percentage of content to be shown.
 *
 * @param array $args is bot_explain.
 */
function wcgu_common_section_callback( $args ) {
	$bot_explain = $args[0];
	echo esc_html( $bot_explain );
}

/**
 * SELECT tag to set the percentage of content to be shown.
 *
 * @param array $args Label.
 */
function wcgu_status_render( $args ) {
	$option = get_option( $args['label_for'] );
	?>
	<select name="<?php echo esc_attr( $args['label_for'] ); ?>" style="width: 70px;">
		<option value="0" <?php selected( $option, 0 ); ?>>0</option>
		<option value="10" <?php selected( $option, 10 ); ?>>10</option>
		<option value="20" <?php selected( $option, 20 ); ?>>20</option>
		<option value="30" <?php selected( $option, 30 ); ?>>30</option>
		<option value="40" <?php selected( $option, 40 ); ?>>40</option>
		<option value="50" <?php selected( $option, 50 ); ?>>50</option>
		<option value="60" <?php selected( $option, 60 ); ?>>60</option>
		<option value="70" <?php selected( $option, 70 ); ?>>70</option>
		<option value="80" <?php selected( $option, 80 ); ?>>80</option>
		<option value="90" <?php selected( $option, 90 ); ?>>90</option>
		<option value="100" <?php selected( $option, 100 ); ?>>100</option>
	</select>
	<?php
}

/**
 * For setting page.
 * Add extra header.
 * Because some image collection bots ignore robots.txt and require their own headers
 */
function wcgu_options_page() {
	?>
	<form action='options.php' method='post'>

		<h2>gpt-welcomer settings</h2>

		<?php
		settings_fields( 'wcgu' );
		do_settings_sections( 'wcgu' );
		submit_button();
		?>

	</form>
	<?php
}


/**
 * Add extra header.
 * Because some image collection bots ignore robots.txt and require their own headers
 */
function add_x_robots_tag_header() {
	$tags = array(
		'noai',
		'noindex',
		'noimageai',
		'noimageindex',
	);

	$header_value = '';
	foreach ( $tags as $tag ) {
		$header_value .= $tag . ', ';
	}
	$header_value = rtrim( $header_value, ', ' );

	header( 'X-Robots-Tag: ' . $header_value );
}
add_action( 'wp_head', 'add_x_robots_tag_header' );

