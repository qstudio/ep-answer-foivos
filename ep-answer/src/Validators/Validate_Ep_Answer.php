<?php

namespace App\Validators;

/**
 * Validate and Sanitise Ep Answer User Meta data
 */
class Validate_Ep_Answer extends Abstract_Validator
{
    protected $ep_answer;

    /**
     * Construct Validator and Sanitise ep_answer
     *
     * @param string $ep_answer
     */
    public function __construct(string $ep_answer)
    {
        $this->ep_answer = $this->sanitise($ep_answer);
    }

    /**
     * Get Ep Answer
     *
     * @return string
     */
    public function get_ep_answer()
    {
        return $this->ep_answer;
    }
    /**
     * Sanitise meta data
     *
     * @param string $ep_answer
     * @return void
     */
    protected function sanitise(string $ep_answer)
    {
        return sanitize_text_field($ep_answer);
    }


    /**
     * Validate ep answer
     *
     * @return boolean
     */
    public function validate()
    {
        // required
        if (!isset($this->ep_answer) || empty($this->ep_answer)) {
            $this->errors['required'] = 'Ep Answer cannot be empty';

            return false;
        }

        // alphanum
        if (!ctype_alnum($this->ep_answer)) {
            $this->errors['alnum'] = 'Ep Answer needs to be alpha numerical';

            return false;
        }

        // greater than 3 characters
        if (strlen(trim($this->ep_answer)) < 3) {
            $this->errors['length'] = 'Ep Answer needs to be greater than 3 characters';

            return false;
        }

        return true;
    }
}
