<?php
class Model {
    protected $pdo;
    protected $table;
    protected $primaryKey = "id";

    public function __construct($table, $primaryKey = "id") {
        $this->pdo = db($GLOBALS['config']['db']);
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function insert($data) {
        $cols = array_keys($data);
        $sql = "INSERT INTO {$this->table} (" . implode(',', $cols) . ") VALUES (" . str_repeat('?,', count($cols)-1) . "?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(array_values($data));
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        foreach ($data as $k => $v) {
            $fields[] = "$k = ?";
        }
        $sql = "UPDATE {$this->table} SET " . implode(", ", $fields) . " WHERE {$this->primaryKey} = ?";
        $stmt = $this->pdo->prepare($sql);
        $data[] = $id;
        return $stmt->execute(array_values($data));
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function queryOne($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function execute($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
