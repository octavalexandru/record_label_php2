<?php
require_once __DIR__ . '/../core/Model.php';

class UserRole extends Model {
    public function __construct() {
        parent::__construct("user_roles");
    }
}
