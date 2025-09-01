<?php
require_once __DIR__ . '/../core/Model.php';

class Album extends Model {
    public function __construct() {
        parent::__construct("albums", "album_id");
    }

    public function findByArtist($artist_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM albums WHERE artist_id = ?");
        $stmt->execute([$artist_id]);
        return $stmt->fetchAll();
    }
    public function updateStock($id, $stock) {
    $stmt = $this->pdo->prepare("UPDATE albums SET stock = ? WHERE album_id = ?");
    return $stmt->execute([$stock, $id]);

}
public function decreaseStock($album_id, $quantity) {
        $stmt = $this->pdo->prepare("UPDATE albums SET stock = GREATEST(stock - ?, 0) WHERE album_id = ?");
        return $stmt->execute([$quantity, $album_id]);
    }

}


    
