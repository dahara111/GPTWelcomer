<?php

/**
 * Class BotsTest
 *
 * @package Gpt_Welcomer
 */

require_once './tests/bootstrap.php';
require_once './gpt-welcomer.php'; // Replace with the actual path to the PHP file containing the `get_bots` function

/**
 * Bots test case.
 */
class BotsTest extends WP_UnitTestCase {

	/**
	 * Test that get_bots function returns expected bots array.
	 */
	public function test_get_bots() {
		$expected_bots = [
			[
				'bot_name' => 'chatGPT',
				'bot_explain' => __('bot_explain_chatGPT', 'wcgu'),
				'default_status' => 1,
				'pattern' => ['ChatGPT-User']
			],
			[
				'bot_name' => 'Bingbot',
				'bot_explain' => __('bot_explain_Bingbot', 'wcgu'),
				'default_status' => 1,
				'pattern' => ['MicrosoftPreview', 'bingbot']
			],
			[
				'bot_name' => 'CommonCrawl',
				'bot_explain' => __('bot_explain_CommonCrawl', 'wcgu'),
				'default_status' => 0,
				'pattern' => ['CCBot']
			]
		];

		$actual_bots = get_bots();
		$this->assertEquals($expected_bots, $actual_bots);
	}
}
