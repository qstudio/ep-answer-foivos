<?php

namespace Validators;

/**
 * AbstractValidator validating functions
 */
abstract class AbstractValidator
{
    /**
     * @var mixed
     */
    protected $errors = [];

    /**
     * Validate Form
     *
     * @return boolean
     */
    abstract public function validate();

    /**
     * Is it Valid
     *
     * @return boolean Valid form
     */
    public function isValid()
    {
        return  !$this->hasErrors();
    }

    /**
     * Get the errors
     *
     * @return array Errors
     */
    public function getErrors()
    {
        if (!$this->hasErrors()) {
            return null;
        }

        return $this->errors;
    }

    /**
     * Does form have errors
     *
     * @return boolean
     */
    public function hasErrors()
    {
        return  !empty($this->errors);
    }
}
