<?php

namespace App\Models;

/**
 * Wrapper class for ep_answer user meta filed
 */
class ACF_Ep_Answer
{
    /**
     * Ep Answer meta field
     *
     * @var string
     */
    protected $ep_answer;

    /**
     * User Id
     *
     * @var int
     */
    protected $user_id;

    /**
     * Meta Key value eg. ep_answer;
     *
     * @var string
     */
    protected $meta_key = 'ep_answer';

    public function __construct($ep_answer = '')
    {
        if (!function_exists('the_field')) {
            die("Unable to run plugin without AFC Installed");
        }

        $this->ep_answer = $ep_answer;
        $this->user_id = 'user_' . get_current_user_id();
    }

    /**
     * Get value of ep_answer
     * Ref: https://developer.wordpress.org/reference/functions/get_user_meta/
     *
     * @return string
     */
    public function get()
    {
        return $this->ep_answer =
            get_field($this->meta_key, $this->user_id);
    }

    /**
     * Update value of ep_answer
     * Ref: https://developer.wordpress.org/reference/functions/update_user_meta/
     *
     * @param string $ep_answer
     * @return void
     */
    public function update($ep_answer)
    {
        return $this->ep_answer = update_field($this->meta_key, $ep_answer, $this->user_id);
    }

    /**
     * Delete user meta field
     * Ref: https://developer.wordpress.org/reference/functions/delete_user_meta/
     *
     * @return mixed
     */
    public function delete()
    {
        return delete_field($this->meta_key, $this->user_id);
    }
}
