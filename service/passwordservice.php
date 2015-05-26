<?php
namespace OCA\Passwords\Service;

use Exception;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

use OCA\Passwords\Db\Password;
use OCA\Passwords\Db\PasswordMapper;


class PasswordService {

    private $mapper;

    public function __construct(PasswordMapper $mapper){
        $this->mapper = $mapper;
    }

    public function findAll($userId) {
        return $this->mapper->findAll($userId);
    }

    private function handleException ($e) {
        if ($e instanceof DoesNotExistException ||
            $e instanceof MultipleObjectsReturnedException) {
            throw new NotFoundException($e->getMessage());
        } else {
            throw $e;
        }
    }

    public function find($id, $userId) {
        try {
            return $this->mapper->find($id, $userId);

        // in order to be able to plug in different storage backends like files
        // for instance it is a good idea to turn storage related exceptions
        // into service related exceptions so controllers and service users
        // have to deal with only one type of exception
        } catch(Exception $e) {
            $this->handleException($e);
        }
    }

    public function create($title, $content, $userId) {
        $password = new Password();
        $password->setTitle($title);
        $password->setContent($content);
        $password->setUserId($userId);
        return $this->mapper->insert($password);
    }

    public function update($id, $title, $content, $userId) {
        try {
            $password = $this->mapper->find($id, $userId);
            $password->setTitle($title);
            $password->setContent($content);
            return $this->mapper->update($password);
        } catch(Exception $e) {
            $this->handleException($e);
        }
    }

    public function delete($id, $userId) {
        try {
            $password = $this->mapper->find($id, $userId);
            $this->mapper->delete($password);
            return $password;
        } catch(Exception $e) {
            $this->handleException($e);
        }
    }

}