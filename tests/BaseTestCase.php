// BaseTestCase.php

require_once 'UserAgent.php';

class BaseTestCase extends WP_UnitTestCase {
    protected $userAgent;

    function setUp(): void {
        parent::setUp();
        $this->userAgent = new UserAgent();
    }
}

