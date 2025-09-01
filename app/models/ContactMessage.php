<?php
require_once __DIR__ . '/../core/Model.php';

class ContactMessage extends Model {
    public function __construct() {
        parent::__construct("contact_messages", "message_id");
    }
}
