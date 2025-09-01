<?php
require_once __DIR__ . '/../core/Model.php';

class Merchandise extends Model {
    public function __construct() {
        parent::__construct("merchandise", "merchandise_id");
    }

    public function findByArtist($artist_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM merchandise WHERE artist_id = ?");
        $stmt->execute([$artist_id]);
        return $stmt->fetchAll();
    }

    public function updateStock($id, $stock) {
    $stmt = $this->pdo->prepare("UPDATE merchandise SET stock = ? WHERE merchandise_id = ?");
    return $stmt->execute([$stock, $id]);
}
public function decreaseStock($merchandise_id, $quantity) {
        $stmt = $this->pdo->prepare("UPDATE merchandise SET stock = GREATEST(stock - ?, 0) WHERE merchandise_id = ?");
        return $stmt->execute([$quantity, $merchandise_id]);
    }


}

    