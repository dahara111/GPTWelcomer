<?php
// Message.php


if (!function_exists('__')) {
    function __($text, $domain) {
        return $text;
    }
}


class MessageManager {
    private $data;

    public function __construct() {
        $this->data = array(
            __('User-Selectable-Messages', 'gpt-welcomer') => array(
                __('Reasons-for-Rejection', 'gpt-welcomer') => array(
                    __('Community-Cultivation', 'gpt-welcomer') => array(
                        '1' => array(
                            'text' => __('The reason our site prioritizes human visitors is to promote the formation of a unique community. Please understand and access as a human.', 'gpt-welcomer'),
                        ),
                        '2' => array(
                            'text' => __('AI browsing is limited. Our site aims to truly nurture human bonds and connections. We appreciate your cooperation.', 'gpt-welcomer'),
                        ),
                        '3' => array(
                            'text' => __('Due to our utmost importance placed on communication with humans, page display to non-human visitors is restricted. We appreciate your understanding and cooperation.', 'gpt-welcomer'),
                        ),
                        '4' => array(
                            'text' => __('Our website provides information that should be shared among humans. To avoid excessive load from automated programs, some restrictions have been set.', 'gpt-welcomer'),
                        ),
                        '5' => array(
                            'text' => __('Access from AI is partially restricted in order to manage the site load and improve the human user experience. We ask for your access as a human visitor.', 'gpt-welcomer'),
                        ),
                    ),
                    __('Loss-of-Engagement-Opportunities', 'gpt-welcomer') => array(
                        '1' => array(
                            'text' => __('Our site values meaningful interaction with human visitors. Access by AI bots is partially limited because it could impair this engagement.', 'gpt-welcomer'),
                        ),
                        '2' => array(
                            'text' => __('In order to respect and maintain the opportunity for direct engagement with customers, our website limits browsing by AI bots.', 'gpt-welcomer'),
                        ),
                        '3' => array(
                            'text' => __('Our website values connection with human readers. Therefore, access from AI bots is restricted. We appreciate your understanding.', 'gpt-welcomer'),
                        ),
                    ),
                    __('Ad-Revenue', 'gpt-welcomer') => array(
                        '1' => array(
                            'text' => __('Our site is operated relying on advertising revenue. Access by AI bots could potentially hinder this, so we have some browsing restrictions in place.', 'gpt-welcomer'),
                        ),
                        '2' => array(
                            'text' => __('In order to protect ad revenue, we restrict access by AI bots. We ask for understanding and cooperation from our human visitors.', 'gpt-welcomer'),
                        ),
                        '3' => array(
                            'text' => __('Human visitor engagement is key to maintaining ad revenue, therefore, we have some browsing restrictions in place for AI bots.', 'gpt-welcomer'),
                        ),
                    ),
                    __('Human-PV-as-KPI', 'gpt-welcomer') => array(
                        '1' => array(
                            'text' => __('Page views by human viewers are a critical indicator for us. Therefore, we have placed restrictions on access by AI bots.', 'gpt-welcomer'),
                        ),
                        '2' => array(
                            'text' => __('On our site, we use page views by humans as a performance indicator. To achieve this target, we limit browsing by AI bots.', 'gpt-welcomer'),
                        ),
                        '3' => array(
                            'text' => __('We prioritize page views by humans as our main performance indicator. As a result, we limit visits from AI bots. We appreciate your cooperation.', 'gpt-welcomer'),
                        ),
                    ),
                    __('Analysis-of-User-Behavior-and-Popular-Content', 'gpt-welcomer') => array(
                        '1' => array(
                            'text' => __('We limit access by AI bots in order to improve our service through the analysis of human visitor behavior.', 'gpt-welcomer'),
                        ),
                        '2' => array(
                            'text' => __('In order to understand the interests and actions of human readers, and provide the best content, we limit browsing from AI bots.', 'gpt-welcomer'),
                        ),
                        '3' => array(
                            'text' => __('Our website aims to improve services based on the behavior and preferences of readers. Therefore, we restrict access by AI bots.', 'gpt-welcomer'),
                        ),
                    ),
                    __('Unexpected-Security-Risks', 'gpt-welcomer') => array(
                        '1' => array(
                            'text' => __('The unique behavior of AI bots can potentially lead to security risks, so we have placed restrictions on their access.', 'gpt-welcomer'),
                        ),
                        '2' => array(
                            'text' => __('The behavior of AI bots often differs from that of humans and can cause security concerns, so their access is limited.', 'gpt-welcomer'),
                        ),
                        '3' => array(
                            'text' => __('Access by AI bots can potentially threaten site security, so we limit some of their browsing.', 'gpt-welcomer'),
                        ),
                    ),
                    __('Excessive-AI-Bot-Access', 'gpt-welcomer') => array(
                        '1' => array(
                            'text' => __('Massive access from AI bots increases the cost of site operation, so we restrict their browsing.', 'gpt-welcomer'),
                        ),
                        '2' => array(
                            'text' => __('To maintain the performance of our site, we have some restrictions on access by AI bots.', 'gpt-welcomer'),
                        ),
                        '3' => array(
                            'text' => __('Maintaining site performance is essential to providing the best experience to our users. Therefore, we limit access from AI bots.', 'gpt-welcomer'),
                        ),
                    ),
                    __('Maintaining-Content-Originality', 'gpt-welcomer') => array(
                        '1' => array(
                            'text' => __('Our website content is created independently, and in order to maintain its value, we restrict automatic collection, learning, and excerpts by AI bots.', 'gpt-welcomer'),
                        ),
                        '2' => array(
                            'text' => __('Our site content is consistent and original. As the collection by AI bots is an unintended use, we limit their access.', 'gpt-welcomer'),
                        ),
                        '3' => array(
                            'text' => __('Our content is unique, and to avoid it being collected and learned by AI bots without restrictions, we limit their access.', 'gpt-welcomer'),
                        ),
                    ),
                    // Add more subcategories here
                ),
                // Add more categories here
            ),
            __('Bot-information', 'gpt-welcomer') => array(
                __('OpenAI', 'gpt-welcomer') => array(
                    __('chatGPT-Web-Browsing', 'gpt-welcomer') => array(
                        __('default-percentage', 'gpt-welcomer') => 10,
                        __('pattern', 'gpt-welcomer') => array(
                            'ChatGPT-User'
                        ),
                        __('bot_explain', 'gpt-welcomer') => __('Developed by openAI, chatGPT uses the browsing function to access the Internet, collect information, and respond directly to the user.', 'gpt-welcomer'),
                    ),
                    __('chatGPT-plugin-xxx', 'gpt-welcomer') => array(
                        __('default-percentage', 'gpt-welcomer') => 0,
                        __('pattern', 'gpt-welcomer') => array(
                            ''
                        ),
                        __('bot_explain', 'gpt-welcomer') => __('There are plug-ins provided by third parties for chatGPT that access the Internet, collect information, and respond directly to the user; we cannot exclude them at this time because they falsify UserAgent.', 'gpt-welcomer'),

                    ),
                ),
                __('OtherBot', 'gpt-welcomer') => array(
                    __('CommonCrawl', 'gpt-welcomer') => array(
                        __('default-percentage', 'gpt-welcomer') => 0,
                        __('pattern', 'gpt-welcomer') => array(
                            'CCBot'
                        ),
                        __('bot_explain', 'gpt-welcomer') => __('CommonCrawl will access the Internet to collect information and provide it to various AIs, including commercial ones. No data source citations or revenue returns will be provided.', 'gpt-welcomer'),
                    ),
                ),
                __('Microsoft', 'gpt-welcomer') => array(
                    __('Bingbot', 'gpt-welcomer') => array(
                        __('default-percentage', 'gpt-welcomer') => 10,
                        __('pattern', 'gpt-welcomer') => array(
                            'MicrosoftPreview',
                            'bingbot'
                        ),
                        __('bot_explain', 'gpt-welcomer') => __('Microsoft generates advertising revenue by building a system in which AI directly answers user inquiries using website data collected for traditional Bing searches. At present, the only way to prevent unauthorized use of content by AI is to prevent bots for Bing search.', 'gpt-welcomer'),
                    ),
                ),
                __('Google', 'gpt-welcomer') => array(
                    __('Googlebot', 'gpt-welcomer') => array(
                        __('default-percentage', 'gpt-welcomer') => 100,
                        __('pattern', 'gpt-welcomer') => array(
                            'Googlebot'
                        ),
                        __('domain', 'gpt-welcomer') => array(
                            '.googlebot.com'
                        ),
                        __('bot_explain', 'gpt-welcomer') => __('Google\'s Bard is not restricted by default, as it does not have a web search function at this time.', 'gpt-welcomer'),
                    ),
                    __('GoogleOther', 'gpt-welcomer') => array(
                        __('default-percentage', 'gpt-welcomer') => 10,
                        __('pattern', 'gpt-welcomer') => array(
                            'GoogleOther'
                        ),
                        __('bot_explain', 'gpt-welcomer') => __('GoogleOtherBot is a bot for Google products other than Google Search, and is restricted by default because it may be used for AI products.', 'gpt-welcomer'),
                    ),
                ),
            )

            // Add more top level categories here
        );
    }

    public function getItem($topCategory, $category, $subcategory, $id) {
        if(isset($this->data[$topCategory][$category][$subcategory][$id])) {
            return $this->data[$topCategory][$category][$subcategory][$id];
        } else {
            return null; // Or any other error handling
        }
    }

    public function getHush() {
        return $this->data;
    }

    public function getItemsByCategory($topCategory, $category) {
        if(isset($this->data[$topCategory][$category])) {
            return $this->data[$topCategory][$category];
        } else {
            return null; // Or any other error handling
        }
    }

    public function get_bots() {
        $bots = [];
        $bots_data = $this->data[__('Bot-information', 'gpt-welcomer')];

        foreach ($bots_data as $bot_category => $bots_array) {
            foreach ($bots_array as $bot_name => $bot_data){
                $bot = [];
                $bot['bot_category'] = $bot_category;
                $bot['bot_name'] = $bot_name;
                $bot['bot_explain'] = $bot_data[__('bot_explain', 'gpt-welcomer')];
                $bot['default-percentage'] = $bot_data[__('default-percentage', 'gpt-welcomer')];
                $bot['pattern'] = $bot_data[__('pattern', 'gpt-welcomer')];
                $bots[] = $bot;
            }
        }
        return $bots;
    }

    public function get_messages() {
        $bots = [];
        $messages_data = $this->data[__('User-Selectable-Messages', 'gpt-welcomer')][__('Reasons-for-Rejection', 'gpt-welcomer')];

        foreach ($messages_data as $message_category => $messages_array) {
            foreach ($messages_array as $message_no => $message_struct){
                $message= [];
                $message['message_category'] = $message_category;
                $message['message_no'] = $message_no;
                $message['message'] = $message_struct['text'];
                $messages[] = $message;
            }
        }
        return $messages;
    }

}
