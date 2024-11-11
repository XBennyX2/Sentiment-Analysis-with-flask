<?php
class Api_Training_APIs {

    public function __construct() {
        add_shortcode('my_shortcode', [$this, 'api_training_shortcodes']);
    }

    function api_training_shortcodes() {
        ob_start();

        // Check if a comment has been submitted
        if (isset($_POST['comment'])) {
            $comment = sanitize_text_field($_POST['comment']);
            
            // Prepare the request to Flask API
            $response = wp_remote_post('http://flask:5000/predict', array(
                'method'    => 'POST',
                'body'      => json_encode(array('text' => $comment)),
                'headers'   => array('Content-Type' => 'application/json')
            ));
            
            // Check if the response is valid
            if (is_wp_error($response)) {
                echo 'Error processing your request';
            } else {
                $body = wp_remote_retrieve_body($response);
                $data = json_decode($body, true);

                // Display sentiment result
                if (isset($data['sentiment'])) {
                    echo '<p>Sentiment: ' . esc_html($data['sentiment']) . '</p>';
                } else {
                    echo '<p>Error retrieving sentiment data</p>';
                }
            }
        }

        // Display the form
        echo '<form method="post">';
        echo '<label for="comment">Enter your comment:</label>';
        echo '<input type="text" id="comment" name="comment" required>';
        echo '<input type="submit" value="Analyze Sentiment">';
        echo '</form>';

        return ob_get_clean();
    }
}

new Api_Training_APIs();
