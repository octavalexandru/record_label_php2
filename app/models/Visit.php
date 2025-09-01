<?php
require_once __DIR__ . '/../core/Model.php';

class Visit extends Model {
    public function __construct() {
        parent::__construct("visits", "visit_id");
    }
}
