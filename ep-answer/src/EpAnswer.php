<?php

/**
 * Wrapper class for ep_answer user meta filed
 */
class EpAnswer
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
        $this->ep_answer = $ep_answer;
        $this->user_id = get_current_user_id();
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
            get_user_meta($this->user_id, $this->meta_key, true);
    }

    /**
     * Update value of ep_answer
     * Ref: https://developer.wordpress.org/reference/functions/update_user_meta/
     *
     * @param string $meta_value
     * @return void
     */
    public function update($meta_value)
    {
        return $this->ep_answer = update_user_meta($this->user_id, $this->meta_key, $meta_value);
    }

    /**
     * Delete user meta field
     * Ref: https://developer.wordpress.org/reference/functions/delete_user_meta/
     *
     * @return mixed
     */
    public function delete()
    {
        return delete_user_meta($this->user_id, $this->meta_key);
    }
}
