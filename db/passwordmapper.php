<?php
namespace OCA\Passwords\Db;

use OCP\IDb;
use OCP\AppFramework\Db\Mapper;

class PasswordMapper extends Mapper {

	public function __construct(IDb $db) {
		parent::__construct($db, 'passwords', '\OCA\Passwords\Db\Password');
	}

	public function find($id, $userId) {
		$sql = 'SELECT * FROM *PREFIX*passwords WHERE id = ? AND user_id = ?';
		return $this->findEntity($sql, [$id, $userId]);
	}

	public function findAll($userId) {
		$sql = 'SELECT * FROM *PREFIX*passwords WHERE user_id = ? ORDER BY LOWER(website) ASC';
		return $this->findEntities($sql, [$userId]);
		// Now, search for shared too on 'oc_share':
		// $sql = 'SELECT * FROM *PREFIX*passwords WHERE user_id = ? OR id IN (SELECT item_source FROM *PREFIX*share WHERE share_with = ? AND item_type = "password")';
		// return $this->findEntities($sql, [$userId, $userId]);
	}

	// public function sharedWith($id) {
	// 	// @id - id of passwords
	// 	// @uid - uid of user shared with
	// 	$sql = 'SELECT item_source AS "id", share_with AS "uid" FROM *PREFIX*share WHERE item_source = ? AND item_type = "password"';
	// 	return $this->findEntities($sql, [$id]);
	// }

}