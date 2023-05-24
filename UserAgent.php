<?php
// UserAgent.php

class UserAgent {
    private $userAgent;

    public function __construct($userAgent) {
        if (! empty($userAgent)){
            $this->userAgent = $userAgent;
        }
        else if(!isset($_SERVER['HTTP_USER_AGENT'])) {
            $this->userAgent = "ChatGPT-User";
        } else {
            $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        }
    }

    public function getUserAgent() {
        return $this->userAgent;
    }

    public function setUserAgent($userAgent) {
        $this->userAgent = $userAgent;
    }
}

