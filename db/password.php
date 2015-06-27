<?php
namespace OCA\Passwords\Db;

use JsonSerializable;

use OCP\AppFramework\Db\Entity;

class Password extends Entity implements JsonSerializable {

    protected $loginname;
    protected $website;
    protected $pass;
    protected $userId;
    protected $creationDate;

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'loginname' => $this->loginname,
            'website' => $this->website,
            'pass' => $this->pass,
            'creation_date' => $this->creationDate
        ];
    }
}