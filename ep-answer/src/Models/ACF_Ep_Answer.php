<?php

namespace App\Models;

/**
 * Wrapper class for ep_answer user meta filed using ACF
 */
class ACF_Ep_Answer extends Meta_Field
{
    public function __construct($ep_answer = '')
    {
        if (!function_exists('the_field')) {
            die("Unable to run plugin without ACF Installed");
        }

        $this->ep_answer = $ep_answer;
        $this->user_id = 'user_' . get_current_user_id();
    }

    /**
     * Get value of ep_answer using ACF
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
     * Update value of ep_answer using ACF
     * Ref: https://developer.wordpress.org/reference/functions/update_user_meta/
     *
     * @param string $meta_value
     * @return void
     */
    public function update(string $meta_value)
    {
        return $this->ep_answer = update_field($this->meta_key, $meta_value, $this->user_id);
    }

    /**
     * Delete user meta field using ACF
     * Ref: https://developer.wordpress.org/reference/functions/delete_user_meta/
     *
     * @return mixed
     */
    public function delete()
    {
        return delete_field($this->meta_key, $this->user_id);
    }
}
