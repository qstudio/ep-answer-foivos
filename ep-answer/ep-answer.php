<?php

/*
Plugin Name: EP Answer
Description: Demonstrate coding practices
Version: 1.0
Author: Foivos Apostolidis
*/

// Block direct call
if (!defined('ABSPATH')) exit;

use Validators\ValidateEpAnswer;


function ep_answer_url_handler()
{
    if ($_SERVER["REQUEST_URI"] == '/ep-answer') {
        require_once("src/AfcEpAnswer.php");

        if (!is_user_logged_in()) {
            echo "Please log in to access the form.";
            exit;
        }

        echo "<h1>EP Answer Form</h1>";
        $ep_answer = (new AfcEpAnswer())->get();

        if (empty($ep_answer)) {
            echo "EP Answer not set.";
        }
?>
        <form method="POST" action="/update-ep-answer">
            <label>EP Answer</label>
            <input name="ep_answer" type="text" value="<?php echo $ep_answer ?>" />
            <button type="submit">Save</button>
        </form>
    <?php
        exit();
    }

    // Update post
    if ($_SERVER["REQUEST_URI"] == '/update-ep-answer' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once("src/AfcEpAnswer.php");

        // TODO: Check Logged in

        // Validate
        $validator = new ValidateEpAnswer($_POST["ep_answer"]);
        $validator->validate();

        if (!$validator->isValid()) {
            // TODO: Manage error or throw new Exception
            var_dump($validator->getErrors());
            die;
        }

        // Update
        $ep_answer = $validator->getEpAnswer();
        $update = (new AfcEpAnswer())->update($ep_answer);

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



/** Show in user profile */
// Ref: https://developer.wordpress.org/reference/hooks/show_user_profile/
// Ref2: https://developer.wordpress.org/reference/hooks/edit_user_profile/
function ep_answer_user_profile_field($user)
{
    require_once('src/AfcEpAnswer.php');
    $ep_answer = (new AfcEpAnswer())->get();
    ?>
    <h2>Ep Answer</h2>
    <table class="form-table">
        <tr>
            <th>
                <label>Ep Answer: </label>
            </th>
            <td>
                <input type="text" name="ep_answer" id="ep_answer" value="<?php echo $ep_answer ?>" class="regular-text" />
            </td>
        </tr>
    </table>
<?php
}

// editing your own profile
add_action('show_user_profile', 'ep_answer_user_profile_field');
// edi another user
// add_action('edit_user_profile', 'ep_answer_user_profile_field');

// Save meta data on user profile
// Ref: https://developer.wordpress.org/reference/hooks/personal_options_update/
function edit_user_profile_ep_answer($user_id)
{
    if (!current_user_can('edit_user', $user_id)) {
        return;
    }

    require_once('src/AfcEpAnswer.php');

    // Validate
    $validator = new ValidateEpAnswer($_REQUEST['ep_answer']);
    $validator->validate();

    if (!$validator->isValid()) {
        // TODO: Manage error or throw new Exception
        var_dump($validator->getErrors());
        die;
    }

    // Update
    $ep_answer = $validator->getEpAnswer();
    (new AfcEpAnswer())->update($ep_answer);
}
add_action('personal_options_update', 'edit_user_profile_ep_answer');
// save another user's profile
// add_action('edit_user_profile_update', 'edit_user_profile_ep_answer');


/** Expose user meta data to api */
// https://oj.test/wp-json/wp/v2/users/5
// Ref: https://developer.wordpress.org/rest-api/extending-the-rest-api/modifying-responses/#read-and-write-a-post-meta-field-in-post-responses

$object_type = 'user';
$meta_args = array(
    // validate and sanitise
    'type'         => 'string',
    // Shown in the schema for the meta key.
    'description'  => 'EP Answer customer user meta field',
    // Return the string.
    'single'       => true,
    // Show in the WP REST API response. Default: false.
    'show_in_rest' => true,
);
register_meta($object_type, 'ep_answer', $meta_args);
