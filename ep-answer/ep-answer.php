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
        require_once("src/EpAnswer.php");

        if (!is_user_logged_in()) {
            echo "Please log in to access the form.";
            exit;
        }

        echo "<h1>EP Answer Form</h1>";
        $ep_answer = (new EpAnswer())->get();

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
        require_once("src/EpAnswer.php");

        $ep_answer = htmlentities($_POST["ep-answer"]);
        $update = (new EpAnswer())->update($ep_answer);

        if (!$update) {
            echo "Unable to update";
        } else {
            echo "Succesfully updated ep answer to: " . $ep_answer;
        }

        echo "<br><a href='/ep-answer'>View form</a>";
        exit;
    }
}
add_action('parse_request', 'ep_answer_url_handler');
