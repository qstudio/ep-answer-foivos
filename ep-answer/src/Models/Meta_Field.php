<?php

namespace App\Models;

abstract class Meta_Field
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

    abstract public function get();
    abstract public function update(string $meta_value);
    abstract public function delete();
}
