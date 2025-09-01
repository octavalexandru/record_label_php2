<?php
require_once __DIR__ . '/../core/Model.php';

class Analytics extends Model
{
    protected $table = 'analytics';
    protected $primaryKey = 'id';

    public function __construct()
    {
        parent::__construct('analytics');
    }


    public function log($action, $path = null, $user_id = null)
    {
        $data = [
            'action'     => $action,
            'path'       => $path ?? ($_SERVER['REQUEST_URI'] ?? null),
            'user_id'    => $user_id,
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->insert($data);
    }
    public function logVisit($path, $user_id = null)
    {
        return $this->log('visit', $path, $user_id);
    }

    public function getRecent($limit = 50)
    {
        return $this->query("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ?", [$limit]);
    }

    public function getByUser($user_id)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC", [$user_id]);
    }
}
