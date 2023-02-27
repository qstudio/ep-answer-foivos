<?php

// Ref: https://www.macarthur.me/posts/simpler-unit-testing-for-wordpress
class EpPluginTest extends \PHPUnit\Framework\TestCase
{
    protected $user_id = null;

    /**
     * Test hooked functions are registered and plugin is activated
     *
     * @return void
     */
    public function test_hooked_functions()
    {
        // ep-answer url hook
        $this->assertTrue(function_exists('ep_answer_url_handler'));

        // Admin edit and view field hooks
        $this->assertTrue(function_exists('ep_answer_user_profile_field'));
        $this->assertTrue(function_exists('edit_user_profile_ep_answer'));
    }

    /**
     * Test Url ep answer on guest
     *
     * @return void
     */
    public function test_url_ep_answer_on_guest()
    {
        $url = get_site_url() . '/ep-answer';
        $response = wp_remote_get($url);
        $body = wp_remote_retrieve_body($response);

        // Not logged in, need to login
        $contains = strpos($body, 'Please log in to access the form');

        $this->assertTrue($contains !== false);
    }

    /**
     * Login user and access url
     *
     * @return void
     */
    public function test_url_ep_answer_on_user()
    {
        $this->createUser();

        // Headers
        $headers = [
            'method' => 'GET',
            'cookies' => $this->getCookie()
        ];
        $url = get_site_url() . '/ep-answer';
        $response = wp_remote_request($url, $headers);
        $body = wp_remote_retrieve_body($response);

        $contains = strpos($body, 'Please log in to access the form');

        $this->assertFalse($contains);

        $contains = strpos($body, 'EP Answer Form');
        $this->assertTrue($contains !== false);

        $this->deleteUser();
    }

    public function test_posting_data_to_ep_form()
    {
        $this->createUser();

        $headers = [
            'body'        => [
                'ep_answer' => 'PokoPoko',
            ],
            'method' => 'POST',
            'cookies' => $this->getCookie()
        ];

        // create user
        $url = get_site_url() . '/update-ep-answer';
        $response = wp_remote_request($url, $headers);
        $body = wp_remote_retrieve_body($response);

        $contains = strpos($body, 'Succesfully updated ep answer to: PokoPoko');

        $this->assertTrue($contains !== false);

        $this->deleteUser();
    }

    /**
     * Create User
     * Ref: https://developer.wordpress.org/reference/functions/wp_create_user/
     *
     * @return void
     */
    protected function createUser()
    {
        // assume user does not exist
        $user = false;

        // if user id is not set, find user
        if ($this->user_id === null) {
            $user = get_user_by('email', 'test@gmail.com');
        }

        // Get user's id if it exists
        if ($user instanceof WP_User) {
            $this->user_id = $user->ID;

            return;
        }

        // create user
        $this->user_id = wp_create_user('test_user', 'wZNld1gS', 'test@gmail.com');
    }

    /**
     * Delete User
     * Ref: https://developer.wordpress.org/reference/functions/wp_delete_user/
     *
     * @return void
     */
    protected function deleteUser()
    {
        require_once(ABSPATH . 'wp-admin/includes/user.php');
        wp_delete_user($this->user_id);
    }

    /**
     * Cookie used to authenticate user on request
     * TODO: Generate cookie
     *
     * @return void
     */
    protected function getCookie()
    {
        return [
            'wordpress_logged_in_e7792b574d9d2e2dad8c5814fe47e5ce' => 'leventis%7C1677617267%7C7RUp7cD8TZNBUJ4vcKsyDbGav69LFDI5pW6fjS6g80o%7C1d120c9b73f57f7fe1eec7fe9f3ff34690c1a1d38f5729471a969eec53f2bd94'
        ];
    }
}
