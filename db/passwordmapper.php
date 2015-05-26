<?php
namespace OCA\Passwords\Db;

use OCP\IDb;
use OCP\AppFramework\Db\Mapper;

class PasswordMapper extends Mapper {

    public function __construct(IDb $db) {
        parent::__construct($db, 'passwords_passwords', '\OCA\Passwords\Db\Password');
    }

    public function find($id, $userId) {
        $sql = 'SELECT * FROM *PREFIX*passwords_passwords WHERE id = ? AND user_id = ?';
        return $this->findEntity($sql, [$id, $userId]);
    }

    public function findAll($userId) {
        $sql = 'SELECT * FROM *PREFIX*passwords_passwords WHERE user_id = ?';
        return $this->findEntities($sql, [$userId]);
    }

}