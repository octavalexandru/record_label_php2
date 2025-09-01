<?php
require_once __DIR__ . '/../core/Model.php';

class Role extends Model {
    public function __construct() {
        parent::__construct("roles", "role_id");
    }
}
