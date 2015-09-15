<?php
namespace OCA\Passwords\Db;

use JsonSerializable;

use OCP\AppFramework\Db\Entity;

class Password extends Entity implements JsonSerializable {

	public $id;
	protected $loginname;
	protected $website;
	protected $address;
	protected $pass;
	protected $notes;
	protected $userId;
	protected $creationDate;
	protected $deleted;

	public function jsonSerialize() {
		return [
			'id' => $this->id,
			'user_id' => $this->userId,
			'loginname' => $this->loginname,
			'website' => $this->website,
			'address' => $this->address,
			'pass' => $this->pass,
			'notes' => $this->notes,
			'creation_date' => $this->creationDate,
			'deleted' => $this->deleted
		];
	}
}
