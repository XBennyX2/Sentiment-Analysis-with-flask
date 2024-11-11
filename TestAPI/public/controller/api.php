<?php
class Api_Training_APIs {

    public function __construct() {
        add_shortcode('my_shortcode', [$this, 'api_training_shortcodes']);
    }

    function api_training_shortcodes() {
        ob_start();

        // checks wheather there is a comment or not
        if (isset($_POST['comment'])) {
            $comment = sanitize_text_field($_POST['comment']);
            
            // request Flask Api is here
            $response = wp_remote_post('http://flask:5000/predict', array(
                'method'    => 'POST',
                'body'      => json_encode(array('text' => $comment)),
                'headers'   => array('Content-Type' => 'application/json')
            ));
            
            // is the response valid or not
            if (is_wp_error($response)) {
                echo 'Error processing your request';
            } else {
                $body = wp_remote_retrieve_body($response);
                $data = json_decode($body, true);

                // it displays the result else the error message
                if (isset($data['sentiment'])) {
                    echo '<p>Sentiment: ' . esc_html($data['sentiment']) . '</p>';
                } else {
                    echo '<p>Error retrieving sentiment data</p>';
                }
            }
        }

        // this is the part where we can modifiy how the form is going to be presented 
        echo '<form method="post">';
        echo '<label for="comment">Enter your comment:</label>';
        echo '<input type="text" id="comment" name="comment" required>';
        echo '<input type="submit" value="Comment">';
        echo '</form>';

        return ob_get_clean();
    }
}

new Api_Training_APIs();
