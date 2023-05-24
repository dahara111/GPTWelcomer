<?php

require_once './tests/bootstrap.php';

class Test_Customize_Content extends WP_UnitTestCase {

    function test_customize_content() {
        wp_cache_set('wcgu_detected_bot_name', 'dummy_bot');

        // Create a mock content
        $content = '<p>This is a test content</p><img src="test.jpg" alt="Test Image">';
        
        // Call the function
        $result = customize_content($content);
        // Verify that img tag is removed
        $this->assertStringNotContainsString('<img src="test.jpg" alt="Test Image">', $result);
    }

}
