<?php
/*
Plugin Name: gpt-welcomer
Plugin URI: https://github.com/dahara111/gpt-welcomer
Description: 'This plugin welcomes users using chatGPT's web browsing plugin and encourages users to visit your website.
Version: 1.0.0
Author: dahara111
Author URI: https://github.com/dahara111/
License: GPL2
Text Domain: gpt-welcomer
Domain Path: /languages
*/

require_once 'vendor/autoload.php';
require_once plugin_dir_path(__FILE__) . 'UserAgent.php';
require_once plugin_dir_path(__FILE__) . 'Message.php';


function myplugin_load_textdomain() {
    load_plugin_textdomain( 'gpt-welcomer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'myplugin_load_textdomain' );


function get_bots() {
    $messageManager = new MessageManager();
    return $messageManager->get_bots();
}

function get_messages() {
    $messageManager = new MessageManager();
    return $messageManager->get_messages();
}


// プラグインが有効化された際に初期値を追加
function wcgu_activate() {
    $bots = get_bots();

    foreach ($bots as $bot) {
        $setting_id = 'wcgu_' . strtolower($bot['bot_name']) . '_status';
        // 初期値を設定
        add_option($setting_id, $bot['default_status']);
    }
}
register_activation_hook(__FILE__, 'wcgu_activate');


function wcgu_check_user_agent() {

    $bots = get_bots();
    $userAgentObj = new UserAgent('');
    $user_agent = $userAgentObj->getUserAgent();
    
    foreach ($bots as $bot) {
        foreach ($bot['pattern'] as $bot_user_agent) {
            if (strpos(strtolower($user_agent), strtolower($bot_user_agent)) !== false) {
                wp_cache_set('wcgu_detected_bot_name', $bot['bot_name']);
                return;
            }
        }
    }
}
add_action('init', 'wcgu_check_user_agent');

function customize_content($content) {
    $detected_bot_name = wp_cache_get('wcgu_detected_bot_name');

    if (!empty($detected_bot_name)) {
        // Get the user status for the detected bot.
        $user_status = get_option('wcgu_' . strtolower($detected_bot_name) . '_status', 1);
        // Remove img tags from the content
        $content = preg_replace('/<img[^>]+\>/i', '', $content);
        // Calculate the percentage of content to show.
        $content_to_show = round(strlen($content) * $user_status / 5);
        // Trim the content.
        $content = substr($content, 0, $content_to_show);
        // Add a link to the full content.
        $site_url = get_bloginfo('url');
        $site_name = get_bloginfo('name');
        $page_title = get_the_title();
        $content .= "<p>" . sprintf(__('For the latest information, please check our website at %s%s:%s%s.', 'gpt-welcomer'), "<a href='$site_url'>", $site_name, $page_title, "</a>") . "</p>";
    }
    return $content;
}
add_filter('the_content', 'customize_content');

add_action('admin_menu', 'wcgu_add_admin_menu');
add_action('admin_init', 'wcgu_settings_init');

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'add_plugin_page_settings_link');
function add_plugin_page_settings_link($links) {
    $links[] = '<a href="' . 
        admin_url('options-general.php?page=wcgu') . 
        '">' . __('Settings') . '</a>';
    return $links;
}

function wcgu_add_admin_menu() {
    // Create new top-level menu
    add_options_page('gpt-welcomer settings', 'gpt-welcomer settings', 'manage_options', 'wcgu', 'wcgu_options_page');
}

function wcgu_settings_init() {

    $bots = get_bots();
    $bot_categories = array();

    //register_setting('wcgu', 'wcgu_settings');
    foreach ($bots as $bot) {
        $setting_id = 'wcgu_' . strtolower($bot['bot_name']) . '_status';
        register_setting('wcgu', $setting_id);

        $bot_category = $bot['bot_category'];

        if (!in_array($bot_category, $bot_categories)) {
            add_settings_section(
                'wcgu_' . strtolower($bot_category) . '_section',
                __("BOT related to ", 'gpt-welcomer') . $bot_category,
                'wcgu_common_category_callback',
                'wcgu'
            );
            $bot_categories[] = $bot_category;
        }

        add_settings_section(
            'wcgu_' . strtolower($bot['bot_name']) . '_section',
            __($bot['bot_name'] . ' Settings', 'gpt-welcomer'),
            'wcgu_common_section_callback',
            'wcgu',
            array(__($bot['bot_explain'] ))
        );
        
        add_settings_field(
            'wcgu_' . strtolower($bot['bot_name']) . '_status',
            __($bot['bot_name'] . ' Status (0-5)', 'wcgu'),
            'wcgu_status_render',
            'wcgu',
            'wcgu_' . strtolower($bot['bot_name']) . '_section',
            array('label_for' => 'wcgu_' . strtolower($bot['bot_name']) . '_status')
        );   
    }
    $messages = get_messages();

    add_settings_section(
        'wcgu_messages_section',
        __('Message Select Section', 'gpt-welcomer'),
        'wcgu_message_section_callback',
        'wcgu',
        array(__('Select the message to be displayed when the AI bot accesses the site.', 'gpt-welcomer'))
    );


    foreach ($messages as $message) {
        $message_category = $message['message_category'];
        $message_no = $message['message'];

        // Add a setting field for each message
        add_settings_field(
            'wcgu_message_' . strtolower($message_no),
            __('[' . ucfirst($message_category) . ']  ' . $message_no, 'gpt-welcomer'),
            'wcgu_message_field_callback',
            'wcgu',
            'wcgu_messages_section',
            array(
                'label_for' => 'wcgu_message_' . strtolower($message_no),
                'name' => 'message_choice',
                'value' => $message['message']
            )
        );
        // Register the setting
        register_setting('wcgu', 'message_choice');
    }
}

function wcgu_message_section_callback($args) {
    echo esc_html($args[0]);
}

function wcgu_message_field_callback($args) {
    $name = esc_attr($args['name']);
    $value = esc_attr($args['value']);
    $checked = checked(get_option($name), $value, false);

    echo "<input type='radio' id='{$value}' name='{$name}' value='{$value}' {$checked}>";
}

function wcgu_common_category_callback($args) {
//    $bot_name = ucfirst(str_replace('wcgu_', '', $args['id']));
//    echo __('This is the settings section for ' . $bot_name . '. X/5 of the content is passed to the bot.', 'gpt-welcomer');
}

function wcgu_common_section_callback($args) {
    // $bot_name = ucfirst(str_replace('wcgu_', '', $args['id']));
    // echo __('This is the settings section for ' . $bot_name . '. X/5 of the content is passed to the bot.', 'gpt-welcomer');
    $bot_explain = $args[0]; // この例では、$argsは$bot['bot_explain']のみを含む配列としています
    // 取り出した情報を出力する
    echo esc_html($bot_explain);
}

function wcgu_status_render($args) {
    $option = get_option($args['label_for']);
    ?>
    <select name="<?php echo $args['label_for']; ?>" style="width: 50px;">
        <option value="0" <?php selected($option, 0); ?>>0</option>
        <option value="1" <?php selected($option, 1); ?>>1</option>
        <option value="2" <?php selected($option, 2); ?>>2</option>
        <option value="3" <?php selected($option, 3); ?>>3</option>
        <option value="4" <?php selected($option, 4); ?>>4</option>
        <option value="5" <?php selected($option, 5); ?>>5</option>
    </select>
    <?php
}

function wcgu_options_page() {
    ?>
    <form action='options.php' method='post'>

        <h2>gpt-welcomer settings</h2>

        <?php
        settings_fields('wcgu');
        do_settings_sections('wcgu');
        submit_button();
        ?>

    </form>
    <?php
}


// Add extra header.
// Because some image collection bots ignore robots.txt and require their own headers
function add_x_robots_tag_header() {
    $tags = array(
        'noai',
        'noindex',
        'noimageai',
        'noimageindex'
    );

    $header_value = '';
    foreach ($tags as $tag) {
        $header_value .= $tag . ', ';
    }
    $header_value = rtrim($header_value, ', ');

    header('X-Robots-Tag: ' . $header_value);
}
add_action('wp_head', 'add_x_robots_tag_header');

