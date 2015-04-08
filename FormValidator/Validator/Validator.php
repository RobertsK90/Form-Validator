<?php namespace FormValidator\Validator;

use FormValidator\DatabaseWrapper\Database;
use FormValidator\ErrorHandler\ErrorHandler;


class Validator {

    protected $db;
    protected $errorHandler;
    public $fields = [];



    public function __construct(Database $db, ErrorHandler $errorHandler) {
        $this->errorHandler = $errorHandler;
        $this->db = $db;
    }

    /**
     * @param $formData
     * @param array $rules
     * @return $this
     */
    public function validate($formData, Array $rules) {
        $this->fields = $formData;


        foreach($rules as $formField => $rule) {
            $callbacks = explode('|', $rule);

            foreach($callbacks as $callback) {
                if (strpos($callback, ':') !== false) {
                    $parts = explode(':', $callback);
                    $callback = $parts[0];
                    $satisfier = $parts[1];
                } else {
                    $satisfier = 'true';
                }

               if (is_callable([$this, $callback])) {
                    if (!$this->$callback($formField, $formData[$formField], $satisfier)) {
                        $this->errorHandler->addError(
                            str_replace(['@formField', '@satisfier'],[$formField, $satisfier], $this->errorHandler->errorMessages[$callback]) , $formField );
                    }
                }


            }

        }

        return $this;

    }


    /**
     * @return ErrorHandler
     */
    public function errors() {
        return $this->errorHandler;
    }

    /**
     * @return bool
     */
    public function fails() {
        return $this->errorHandler->hasErrors();
    }

    /**
     * @return bool
     */
    public function passes() {
        return !$this->errorHandler->hasErrors();
    }

    /**
     * @param $field
     * @param $value
     * @param $satisfier
     * @return bool
     */
    private function required($field, $value, $satisfier) {
        return !empty(trim($value));
    }

    /**
     * @param $field
     * @param $value
     * @param $satisfier
     * @return bool
     */
    private function maxlength($field, $value, $satisfier) {
        return mb_strlen($value) <= $satisfier;
    }

    /**
     * @param $field
     * @param $value
     * @param $satisfier
     * @return bool
     */
    private function minlength($field, $value, $satisfier) {
        return mb_strlen($value) >= $satisfier;
    }

    /**
     * @param $field
     * @param $value
     * @param $satisfier
     * @return mixed
     */
    private function email($field, $value, $satisfier) {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param $field
     * @param $value
     * @param $satisfier
     * @return bool
     */
    private function alphanum($field, $value, $satisfier) {
        return ctype_alnum($value);
    }

    /**
     * @param $field
     * @param $value
     * @param $satisfier
     * @return bool
     */
    private function confirmed($field, $value, $satisfier) {
        return $value === $this->fields[$field.'_confirmation'];
    }

    /**
     * @param $field
     * @param $value
     * @param $satisfier
     * @return bool
     */
    private function unique($field, $value, $satisfier) {
        return !$this->db->setTable($satisfier)->exists([$field => $value]);
    }

}