<?php
/*
Plugin Name: gpt-welcomer
Plugin URI: https://github.com/dahara111/gpt-welcomer
Description: This plugin welcomes users using chatGPT's web browsing plugin and encourages users to visit your website.
Version: 1.0.0
Author: dahara111
Author URI: https://github.com/dahara111/
License: GPL2
Text Domain: gpt-welcomer
Domain Path: /languages
*/

function myplugin_load_textdomain() {
    load_plugin_textdomain( 'gpt-welcomer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'myplugin_load_textdomain' );


function get_bots() {
    return [
        [
            'bot_name' => 'chatGPT',
            'bot_explain' => __('ChatGPT visits your site to fetch information and directly respond to users on chat.openai.com. Links to your site will be displayed subtly, reducing the likelihood of clicks.', 'wcgu'),
            'default_status' => 1,
            'pattern' => ['ChatGPT-User']
        ],
        [
            'bot_name' => 'CommonCrawl',
            'bot_explain' => __('CommonCrawl collects data to train AI products, including commercial ones. The companies using this AI technology won\'t provide payment or contribute to your site.', 'wcgu'),
            'default_status' => 0,
            'pattern' => ['CCBot'],
        ],
        [
            'bot_name' => 'Bingbot',
            'bot_explain' => __('Bingbot is transitioning from traditional Bing search to Bing AI search, where AI provides all responses. They claim to link to the data provider within the search results. However, you can verify the traffic from Bing AI search by checking the number of visits from edgeservice.bing.com. From a site I know, it had 1400 visits from the traditional Bing search, while during the same period, the number of visits from Bing AI search was just 5. Despite this, Bing AI search is already generating ad revenue.', 'wcgu'),
            'default_status' => 1,
            'pattern' => ['MicrosoftPreview', 'bingbot'],
        ]
    ];
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
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
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
        // Calculate the percentage of content to show.
        $content_to_show = round(strlen($content) * $user_status / 5);
        // Trim the content.
        $content = substr($content, 0, $content_to_show);
        // Add a link to the full content.
        $site_url = get_bloginfo('url');
        $site_name = get_bloginfo('name');
        $content .= "<p>" . sprintf(__('For the latest information, please check our website at %s%s%s.', 'wcgu'), "<a href='$site_url'>", $site_name, "</a>") . "</p>";
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
    add_options_page('Welcome ChatGPT User', 'Welcome ChatGPT User', 'manage_options', 'wcgu', 'wcgu_options_page');
}

function wcgu_settings_init() {

    $bots = get_bots();

    register_setting('wcgu', 'wcgu_settings');
    foreach ($bots as $bot) {
        $setting_id = 'wcgu_' . strtolower($bot['bot_name']) . '_status';
        register_setting('wcgu', $setting_id);

        add_settings_section(
            'wcgu_' . strtolower($bot['bot_name']) . '_section',
            __($bot['bot_name'] . ' Settings', 'wcgu'),
            'wcgu_common_section_callback',
            'wcgu',
            array('label_for' => 'wcgu_' . strtolower($bot['bot_name']) . '_status')
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
}

function wcgu_common_section_callback($args) {
    $bot_name = ucfirst(str_replace('wcgu_', '', $args['id']));
    echo __('This is the settings section for ' . $bot_name . '. X/5 of the content is passed to the bot.', 'wcgu');
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

        <h2>Welcome ChatGPT User</h2>

        <?php
        settings_fields('wcgu');
        do_settings_sections('wcgu');
        submit_button();
        ?>

    </form>
    <?php
}


