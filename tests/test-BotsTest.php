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
				'bot_category' => 'OpenAI',
				'bot_name' => 'chatGPT-Web-Browsing',
				'bot_explain' => __('Developed by openAI, chatGPT uses the browsing function to access the Internet, collect information, and respond directly to the user.', 'gpt-welcomer'),
				'default-percentage' => 10,
				'pattern' => ['ChatGPT-User']
			]
		];

		$actual_bots = get_bots();
		$this->assertEquals($expected_bots, [$actual_bots[0]]);
	}
}
