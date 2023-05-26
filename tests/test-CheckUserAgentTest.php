<?php

require_once './tests/bootstrap.php';

class Test_WcguCheckUserAgentTest extends WP_UnitTestCase {

    public function test_detects_bot_user_agent()
    {
        $mockBots = [
            [
                'pattern' => ['botUserAgent1', 'botUserAgent2'],
                'bot_name' => 'Bot1'
            ],
            [
				'bot_category' => 'OpenAI',
				'bot_name' => 'chatGPT-Web-Browsing',
				'bot_explain' => __('Developed by openAI, chatGPT uses the browsing function to access the Internet, collect information, and respond directly to the user.', 'gpt-welcomer'),
				'default-percentage' => 10,
				'pattern' => ['ChatGPT-User']
			],
            [
				'bot_category' => 'Microsoft',
				'bot_name' => 'Bingbot',
				'bot_explain' => __('Microsoft generates advertising revenue by building a system in which AI directly answers user inquiries using website data collected for traditional Bing searches. At present, the only way to prevent unauthorized use of content by AI is to prevent bots for Bing search.', 'gpt-welcomer'),
				'default-percentage' => 10,
				'pattern' => ['MicrosoftPreview', 'bingbot']
            ],
            [
				'bot_category' => 'OpenAI',
				'bot_name' => 'chatGPT-plugin-xxx',
				'bot_explain' => __('There are plug-ins provided by third parties for chatGPT that access the Internet, collect information, and respond directly to the user; we cannot exclude them at this time because they falsify UserAgent.', 'gpt-welcomer'),
				'default-percentage' => 10,
				'pattern' => ['']
			],
        ];
    
        $mockUserAgent = 'botUserAgent1';
        wp_cache_set('wcgu_detected_bot_name', 'Bot1');
        wcgu_check_user_agent($mockBots, $mockUserAgent);
        $this->assertEquals('Bot1', wp_cache_get('wcgu_detected_bot_name'));

        $mockUserAgent = 'Mozzila ChatGPT-User/1.0';
        wp_cache_set('wcgu_detected_bot_name', 'Bot1');
        wcgu_check_user_agent($mockBots, $mockUserAgent);
        $this->assertEquals('chatGPT-Web-Browsing', wp_cache_get('wcgu_detected_bot_name'));

        $mockUserAgent = 'Mozzila MicrosoftPreview / 1.0';
        wp_cache_set('wcgu_detected_bot_name', 'Bot1');
        wcgu_check_user_agent($mockBots, $mockUserAgent);
        $this->assertEquals('Bingbot', wp_cache_get('wcgu_detected_bot_name'));

        $mockUserAgent = 'Mozzila bingbot / 1.0';
        wp_cache_set('wcgu_detected_bot_name', 'Bot1');
        wcgu_check_user_agent($mockBots, $mockUserAgent);
        $this->assertEquals('Bingbot', wp_cache_get('wcgu_detected_bot_name'));

        $mockUserAgent = 'chatGPT-plugin-xxx/ 1.0';
        wp_cache_set('wcgu_detected_bot_name', 'chatGPT-plugin-xxx');
        wcgu_check_user_agent($mockBots, $mockUserAgent);
        $this->assertEquals('chatGPT-plugin-xxx', wp_cache_get('wcgu_detected_bot_name'));

    }
}
