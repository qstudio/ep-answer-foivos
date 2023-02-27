<?php

/*
Plugin Name: EP Answer
Description: Demonstrate coding practices
Version: 1.0
Author: Foivos Apostolidis
*/

// Block direct call
if (!defined('ABSPATH')) exit;

require 'vendor/autoload.php';

use App\Models\ACF_Ep_Answer;
use App\Validators\Validate_Ep_Answer;

/**
 * Add Ep Answer meta field and update using ACF
 */
class Ep_Answer_Plugin
{
    public function __construct()
    {
        // Register Routes
        add_action('parse_request', [$this, 'register_ep_answer_url']);
        add_action('parse_request', [$this, 'register_update_ep_answer_url']);

        // editing meta in profile
        add_action('show_user_profile', [$this, 'show_ep_answer_in_profile']);
        add_action('edit_user_profile', [$this, 'show_ep_answer_in_profile']);

        // update ep answer meta from profile
        add_action('personal_options_update', [$this, 'update_ep_answer_in_profile']);
        add_action('edit_user_profile_update', [$this, 'update_ep_answer_in_profile']);

        // expose meta to API
        $this->expose_ep_answer_to_api();
    }

    /**
     * Register GET /ep-answer route
     *
     * @return void
     */
    public function register_ep_answer_url()
    {
        if ($_SERVER["REQUEST_URI"] == '/ep-answer') {
            if (!is_user_logged_in()) {
                echo "Please log in to access the form.";
                exit;
            }

            echo "<h1>EP Answer Form</h1>";

            $ep_answer = (new ACF_Ep_Answer())->get();
            if (empty($ep_answer)) {
                echo "EP Answer not set.";
            }

            echo $this->get_ep_answer_form($ep_answer);
            exit();
        }
    }

    /**
     * Get form for Ep_answer
     *
     * @param string $ep_answer
     * @return string
     */
    protected function get_ep_answer_form(string $ep_answer)
    {
        $form = <<<EOD
            <form method="POST" action="/update-ep-answer">
                <label>EP Answer</label>
                <input name="ep_answer" type="text" value="{$ep_answer}" />
                <button type="submit">Save</button>
            </form>
        EOD;

        return $form;
    }

    /**
     * Register POST /update-ep-answer route
     *
     * @return void
     */
    public function register_update_ep_answer_url()
    {
        if ($_SERVER["REQUEST_URI"] == '/update-ep-answer' && $_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!is_user_logged_in()) {
                echo "Please log in to update ep answer";
                exit;
            }

            // Validate
            $validator = new Validate_Ep_Answer($_POST["ep_answer"]);
            $validator->validate();

            if (!$validator->isValid()) {
                // TODO: Manage error or throw new Exception
                var_dump($validator->getErrors());
                die;
            }

            // Update
            $ep_answer = $validator->get_ep_answer();
            $acf = new ACF_Ep_Answer();
            $updated = $acf->update($ep_answer);

            if ($acf->get() == $ep_answer || $updated) {
                echo "Succesfully updated ep answer to: " . $ep_answer;
            } else {
                echo "Unable to update";
            }

            echo "<br><a href='/ep-answer'>View form</a>";
            exit;
        }
    }

    /**
     * Show Ep Answer meta data in user profile
     *
     * Ref: https://developer.wordpress.org/reference/hooks/show_user_profile/
     * Ref2: https://developer.wordpress.org/reference/hooks/edit_user_profile/
     *
     * @return void
     */
    public function show_ep_answer_in_profile()
    {
        $ep_answer = (new ACF_Ep_Answer())->get();

        echo $this->get_ep_answer_profile_form($ep_answer);
    }

    /**
     * Get form for Ep_answer in profile
     *
     * @param string $ep_answer
     * @return string
     */
    protected function get_ep_answer_profile_form(string $ep_answer)
    {
        $form = <<<EOD
           <h2>Ep Answer</h2>
        <table class="form-table">
            <tr>
                <th>
                    <label>Ep Answer: </label>
                </th>
                <td>
                    <input type="text" name="ep_answer" id="ep_answer" value="{$ep_answer}" class="regular-text" />
                </td>
            </tr>
        </table>
        EOD;

        return $form;
    }

    /**
     * Update EP answer in profile
     *
     * Ref: https://developer.wordpress.org/reference/hooks/personal_options_update/
     *
     * @param int $user_id
     * @return void
     */
    public function update_ep_answer_in_profile($user_id)
    {
        if (!current_user_can('edit_user', $user_id)) {
            return;
        }

        // Validate
        $validator = new Validate_Ep_Answer($_REQUEST['ep_answer']);
        $validator->validate();

        if (!$validator->isValid()) {
            // TODO: Manage error or throw new Exception
            var_dump($validator->getErrors());
            die;
        }

        // Update
        $ep_answer = $validator->get_ep_answer();
        (new ACF_Ep_Answer())->update($ep_answer);
    }

    /**
     * Expose user meta data to API
     * Ref: https://developer.wordpress.org/rest-api/extending-the-rest-api/modifying-responses/#read-and-write-a-post-meta-field-in-post-responses
     * Test: /wp-json/wp/v2/users/5
     *
     * @return void
     */
    protected function expose_ep_answer_to_api()
    {
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
    }
}

$ep_answer_plugin = new Ep_Answer_Plugin();
