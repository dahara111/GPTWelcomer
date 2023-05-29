<?php
/**
 * This file includes the Message Manager class.
 *
 * This class handles all operations related to managing messages in the gpt-welcomer plugin.
 *
 * @package gpt-welcomer
 */

?>
<?php


if ( ! function_exists( '__' ) ) {
	/**
	 * To allow testing outside of WordPress.
	 *
	 * @param string $text is text to print.
	 * @param string $domain is text domain.
	 */
	function __( $text, $domain ) {
		return $text;
	}
}

/**
 * Class MessageManager
 * Aggregate messages for internationalization.
 */
class MessageManager {
	/**
	 * A variable to store all messages.
	 *
	 * @var $data
	 */
	private $data;

	/**
	 * Constructer.
	 */
	public function __construct() {
		$this->data = array(
			__( 'User selectable messages', 'gpt-welcomer' ) => array(
				__( 'Reasons for rejection', 'gpt-welcomer' ) => array(
					__( 'Community cultivation', 'gpt-welcomer' ) => array(
						'1' => array(
							'text' => __( 'The reason our site prioritizes human visitors is to promote the formation of a unique community. Please understand and access as a human.', 'gpt-welcomer' ),
						),
					),
					__( 'Loss of engagement opportunities', 'gpt-welcomer' ) => array(
						'1' => array(
							'text' => __( 'Our site values meaningful interaction with human visitors. Access by AI bots is partially limited because it could impair this engagement.', 'gpt-welcomer' ),
						),
					),
					__( 'Ad revenue', 'gpt-welcomer' ) => array(
						'1' => array(
							'text' => __( 'Our site is operated relying on advertising revenue. Access by AI bots could potentially hinder this, so we have some browsing restrictions in place.', 'gpt-welcomer' ),
						),
					),
					__( 'Human PV as KPI', 'gpt-welcomer' ) => array(
						'1' => array(
							'text' => __( 'Page views by human viewers are a critical indicator for us. Therefore, we have placed restrictions on access by AI bots.', 'gpt-welcomer' ),
						),
					),
					__( 'Analysis of user behavior-and-Popular-Content', 'gpt-welcomer' ) => array(
						'1' => array(
							'text' => __( 'We limit access by AI bots in order to improve our service through the analysis of human visitor behavior.', 'gpt-welcomer' ),
						),
					),
					__( 'Unexpected security risks', 'gpt-welcomer' ) => array(
						'1' => array(
							'text' => __( 'The unique behavior of AI bots can potentially lead to security risks, so we have placed restrictions on their access.', 'gpt-welcomer' ),
						),
					),
					__( 'Excessive AI Bot Access', 'gpt-welcomer' ) => array(
						'1' => array(
							'text' => __( 'Massive access from AI bots increases the cost of site operation, so we restrict their browsing.', 'gpt-welcomer' ),
						),
					),
					__( 'Maintaining content originality', 'gpt-welcomer' ) => array(
						'1' => array(
							'text' => __( 'Our website content is created independently, and in order to maintain its value, we restrict automatic collection, learning, and excerpts by AI bots.', 'gpt-welcomer' ),
						),
					),
					__( 'Emphasis on human visitors.', 'gpt-welcomer' ) => array(
						'1' => array(
							'text' => __( 'By limiting the access of AI bots, we are showing our emphasis on our human visitors. We value our connections with humans, and hence, we have implemented this measure.', 'gpt-welcomer' ),
						),
					),
					__( 'Brand of the site', 'gpt-welcomer' ) => array(
						'1' => array(
							'text' => __( 'To maintain the brand value of our site and enhance its value for human visitors, we have put some restrictions on automatic information collection by AI bots.', 'gpt-welcomer' ),
						),
					),
					// Add more subcategories here.
				),
				// Add more categories here.
			),
			__( 'Bot information', 'gpt-welcomer' ) => array(
				__( 'OpenAI', 'gpt-welcomer' )    => array(
					__( 'chatGPT Web Browsing', 'gpt-welcomer' ) => array(
						__( 'default-percentage', 'gpt-welcomer' ) => 10,
						__( 'pattern', 'gpt-welcomer' ) => array(
							'ChatGPT-User',
						),
						__( 'bot_explain', 'gpt-welcomer' ) => __( 'Developed by openAI, chatGPT uses the browsing function to access the Internet, collect information, and respond directly to the user.', 'gpt-welcomer' ),
					),
					__( 'chatGPT plugin xxx', 'gpt-welcomer' ) => array(
						__( 'default-percentage', 'gpt-welcomer' ) => 10,
						__( 'pattern', 'gpt-welcomer' ) => array(
							'',
						),
						__( 'bot_explain', 'gpt-welcomer' ) => __( 'There are plug-ins provided by third parties for chatGPT that access the Internet, collect information, and respond directly to the user; These can\'t be at this time because they pretend to be human by spoofing the UserAgent.', 'gpt-welcomer' ),
					),
				),
				__( 'OtherBot', 'gpt-welcomer' )  => array(
					__( 'Common Crawl', 'gpt-welcomer' ) => array(
						__( 'default-percentage', 'gpt-welcomer' ) => 0,
						__( 'pattern', 'gpt-welcomer' ) => array(
							'CCBot',
						),
						__( 'bot_explain', 'gpt-welcomer' ) => __( 'CommonCrawl will access the Internet to collect information and provide it to various AIs, including commercial ones. No data source citations or revenue returns will be provided.', 'gpt-welcomer' ),
					),
				),
				__( 'Microsoft', 'gpt-welcomer' ) => array(
					__( 'Bingbot', 'gpt-welcomer' ) => array(
						__( 'default-percentage', 'gpt-welcomer' ) => 10,
						__( 'pattern', 'gpt-welcomer' ) => array(
							'MicrosoftPreview',
							'bingbot',
						),
						__( 'bot_explain', 'gpt-welcomer' ) => __( 'Microsoft monopolizes advertising revenue by building AI systems that directly answer user queries using information from individual websites. At this time, it seems that the data for Bing Search and the data for AI are not separated.', 'gpt-welcomer' ),
					),
				),
				__( 'Google', 'gpt-welcomer' )    => array(
					__( 'Googlebot', 'gpt-welcomer' )      => array(
						__( 'default-percentage', 'gpt-welcomer' ) => 100,
						__( 'pattern', 'gpt-welcomer' ) => array(
							'Googlebot',
						),
						__( 'domain', 'gpt-welcomer' )  => array(
							'.googlebot.com',
						),
						__( 'bot_explain', 'gpt-welcomer' ) => __( 'Google\'s Bard is not restricted by default, as it does not have a web search function at this time.', 'gpt-welcomer' ),
					),
					__( 'GoogleOtherBot', 'gpt-welcomer' ) => array(
						__( 'default-percentage', 'gpt-welcomer' ) => 100,
						__( 'pattern', 'gpt-welcomer' ) => array(
							'GoogleOther',
						),
						__( 'bot_explain', 'gpt-welcomer' ) => __( 'GoogleOtherBot is a bot for Google products other than Google Search, and it may be used for AI products, but we\'re looking at it now.', 'gpt-welcomer' ),
					),
				),
			),
			// Add more top level categories here.
		);
	}

	/**
	 * Get Item from has.
	 *
	 * @param string $top_category is top_category.
	 * @param string $category is category.
	 * @param string $subcategory is subcategory.
	 * @param string $id is id.
	 */
	public function get_item( $top_category, $category, $subcategory, $id ) {
		if ( isset( $this->data[ $top_category ][ $category ][ $subcategory ][ $id ] ) ) {
			return $this->data[ $top_category ][ $category ][ $subcategory ][ $id ];
		} else {
			return null;
		}
	}

	/**
	 * Get hash for test.
	 */
	public function get_hash() {
		return $this->data;
	}

	/**
	 * Make the bot information as object.
	 *
	 * @param string $top_category is top_category.
	 * @param string $category is category.
	 */
	public function get_items_by_category( $top_category, $category ) {
		if ( isset( $this->data[ $top_category ][ $category ] ) ) {
			return $this->data[ $top_category ][ $category ];
		} else {
			return null;
		}
	}

	/**
	 * Make the bot information as object.
	 */
	public function get_bots() {
		$bots      = array();
		$bots_data = $this->data[ __( 'Bot information', 'gpt-welcomer' ) ];

		foreach ( $bots_data as $bot_category => $bots_array ) {
			foreach ( $bots_array as $bot_name => $bot_data ) {
				$bot                       = array();
				$bot['bot_category']       = $bot_category;
				$bot['bot_name']           = $bot_name;
				$bot['bot_explain']        = $bot_data[ __( 'bot_explain', 'gpt-welcomer' ) ];
				$bot['default-percentage'] = $bot_data[ __( 'default-percentage', 'gpt-welcomer' ) ];
				$bot['pattern']            = $bot_data[ __( 'pattern', 'gpt-welcomer' ) ];
				$bots[]                    = $bot;
			}
		}
		return $bots;
	}

	/**
	 * Make the message as object.
	 */
	public function get_messages() {
		$bots          = array();
		$messages_data = $this->data[ __( 'User selectable messages', 'gpt-welcomer' ) ][ __( 'Reasons for rejection', 'gpt-welcomer' ) ];

		foreach ( $messages_data as $message_category => $messages_array ) {
			foreach ( $messages_array as $message_no => $message_struct ) {
				$message                     = array();
				$message['message_category'] = $message_category;
				$message['message_no']       = $message_no;
				$message['message']          = $message_struct['text'];
				$messages[]                  = $message;
			}
		}
		return $messages;
	}

}
