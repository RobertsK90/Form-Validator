<?php


namespace FormValidator\DatabaseWrapper;


use PDOException;

class Database {
    private $host = 'localhost';
    private $db = 'validation';
    private $username = 'root';
    private $password = 'q1w2e3r4t5';

    protected $table;
    protected $stmt;

    public $pdo;

    public function __construct() {

        try {
            $this->pdo = new \PDO("mysql:host={$this->host};dbname={$this->db}", $this->username, $this->password);
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * @param $table
     * @return $this
     */
    public function setTable($table) {
        $this->table = $table;
        return $this;
    }


    /**
     * @param $data
     * @return bool
     */

    public function exists($data) {
        $field = array_keys($data)[0];
        return $this->where($field,'=', $data[$field])->count() ? true : false;
    }

    /**
     * @param $field
     * @param $operator
     * @param $value
     * @return $this
     */
    public function where($field, $operator, $value) {
        $sql = "SELECT * FROM {$this->table} WHERE {$field} {$operator} ?";
        $this->stmt = $this->pdo->prepare($sql);

        $this->stmt->execute([$value]);

        return $this;

    }

    /**
     * @return mixed
     */
    public function count() {
        return $this->stmt->rowCount();
    }

} 