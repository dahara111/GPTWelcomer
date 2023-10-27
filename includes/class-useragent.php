<?php
/**
 * This file includes the UserAgent class.
 *
 * This class handles all operations related to UserAgent in the gpt-welcomer plugin.
 * to avoid global variables and test case.
 *
 * @package gpt-welcomer
 */

?>
<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly      

/**
 * Class UserAgent
 * to avoid global variables and test case.
 */
class UserAgent {

	/**
	 * A variable to store UserAgent text.
	 *
	 * @var $data
	 */
	private $user_agent;

	/**
	 * Constructer.
	 *
	 * @param string $user_agent is user_agent text.
	 */
	public function __construct( $user_agent ) {
		if ( ! empty( $user_agent ) ) {
			$this->user_agent = $user_agent;
		} elseif ( ! isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$this->user_agent = 'ChatGPT-User';
		} else {
			$this->user_agent = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
		}
	}

	/**
	 * Get user agent text for DB.
	 */
	public function get_user_agent_for_db() {
		// TODO: When ready to save to database, use the following line:
		// global $wpdb;
		// $safe_user_agent = $wpdb->prepare("%s", $this->user_agent).
		return null;
	}

	/**
	 * Get user agent text.
	 */
	public function get_user_agent() {
		return $this->user_agent;
	}

	/**
	 * Set user agent text.
	 *
	 * @param string $user_agent is user_agent text.
	 */
	public function set_user_agent( $user_agent ) {
		$this->user_agent = $user_agent;
	}
}

