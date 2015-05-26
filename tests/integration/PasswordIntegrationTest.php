<?php
namespace OCA\Passwords\Controller;

use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\App;
use Test\TestCase;

use OCA\Passwords\Db\Password;

class PasswordIntregrationTest extends TestCase {

    private $controller;
    private $mapper;
    private $userId = 'john';

    public function setUp() {
        parent::setUp();
        $app = new App('passwords');
        $container = $app->getContainer();

        // only replace the user id
        $container->registerService('UserId', function($c) {
            return $this->userId;
        });

        $this->controller = $container->query(
            'OCA\Passwords\Controller\PasswordController'
        );

        $this->mapper = $container->query(
            'OCA\Passwords\Db\PasswordMapper'
        );
    }

    public function testUpdate() {
        // create a new password that should be updated
        $password = new Password();
        $password->setTitle('old_title');
        $password->setContent('old_content');
        $password->setUserId($this->userId);

        $id = $this->mapper->insert($password)->getId();

        // fromRow does not set the fields as updated
        $updatedPassword = Password::fromRow([
            'id' => $id,
            'user_id' => $this->userId
        ]);
        $updatedPassword->setContent('content');
        $updatedPassword->setTitle('title');

        $result = $this->controller->update($id, 'title', 'content');

        $this->assertEquals($updatedPassword, $result->getData());

        // clean up
        $this->mapper->delete($result->getData());
    }

}
