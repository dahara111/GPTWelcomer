<?php
/**
 * Class Test_Customize_Content
 *
 * @package Gpt_Welcomer
 */

require_once './tests/bootstrap.php';

class Test_Customize_Content extends WP_UnitTestCase {

	function test_customize_content() {

		// Google 100% pattern.
		wcgu_activate();
		wcgu_check_user_agent_wrapper( 'Googlebot' );
		$content = '<p>This is a test content</p><img src="test.jpg" alt="Test Image">';
		$result = customize_content( $content );
		$this->assertStringContainsString( $content, $result );

		// Bing 20% pattern.
		wcgu_activate();
		wcgu_check_user_agent_wrapper( 'bingbot' );
		$content = '<p>This is a test content</p><img src="test.jpg" alt="Test Image">';
		$result = customize_content( $content );
		$this->assertStringNotContainsString( '<img src="test.jpg" alt="Test Image">', $result );

		// Messsage Add pattern.
		wcgu_activate();
		$bot = get_bots();
		$messages   = get_messages();
		$my_message = $messages[0]['message'];
		update_option( 'gpt-welcomer_message_choice', $my_message );
		wcgu_check_user_agent_wrapper( 'CCBot/1.0' );
		$content = '<p>This is a test content</p><img src="test.jpg" alt="Test Image">';
		$result = customize_content( $content );
		$this->assertStringContainsString( $my_message, $result );
	}
}
