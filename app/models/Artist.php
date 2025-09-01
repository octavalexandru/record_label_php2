<?php
require_once __DIR__ . '/../core/Model.php';

class Artist extends Model {
    public function __construct() {
        parent::__construct("artists", "artist_id");
    }
}
