<?php


namespace FormValidator\ErrorHandler;


class ErrorHandler {

    public $errorMessages = [
        'required' => 'The @formField is required',
        'minlength' => 'The @formField must be a minimum of @satisfier characters long',
        'maxlength' => 'The @formField must not be longer than @satisfier characters',
        'email' => 'Valid email address must be provided',
        'alphanum' => 'The @formField can contain only letters and digits',
        'confirmed' => 'The @formFields must match',
        'unique' => 'The @formField must be unique'


    ];



    protected $errors = [];

    /**
     * @param $error
     * @param null $field
     * @return mixed
     */
    public function addError($error, $field = null) {
        return isset($field) ? $this->errors[$field][] = $error : $this->errors[] = $error;
    }

    /**
     * @param null $field
     * @return array
     */
    public function all($field = null) {
        return isset($this->errors[$field]) ? $this->errors[$field] : $this->errors;
    }

    /**
     * @return bool
     */
    public function hasErrors($field = null) {
        return (bool)count($this->all($field));
    }

    /**
     * @param $field
     * @return bool
     */
    public function first($field) {
        return isset($this->errors[$field][0]) ?  $this->errors[$field][0] : false ;
    }


} 