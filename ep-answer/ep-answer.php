<?php

/*
Plugin Name: EP Answer
Description: Demonstrate coding practices
Version: 1.0
Author: Foivos Apostolidis
*/

// Block direct call
if (!defined('ABSPATH')) exit;


function ep_answer_url_handler()
{
    if ($_SERVER["REQUEST_URI"] == '/ep-answer') {

        if (!is_user_logged_in()) {
            echo "Please log in to access the form.";
            exit;
        }

        echo "<h1>EP Answer Form</h1>";
        $ep_answer
            = get_user_meta(get_current_user_id(), 'ep_answer', true);
        if (empty($ep_answer)) {
            echo "EP Answer not set.";
        }
?>
        <form method="POST" action="/update-ep-answer">
            <label>EP Answer</label>
            <input name="ep-answer" type="text" value="<?php echo $ep_answer ?>" />
            <button type="submit">Save</button>
        </form>
<?php
        exit();
    }

    // Update post
    if ($_SERVER["REQUEST_URI"] == '/update-ep-answer' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $ep_answer = htmlentities($_POST["ep-answer"]);

        echo "Updating ep answer to: " . $ep_answer;
        update_user_meta(get_current_user_id(), 'ep_answer', $ep_answer);
        echo "<br><a href='/ep-answer'>View form</a>";
        exit;
    }
}
add_action('parse_request', 'ep_answer_url_handler');