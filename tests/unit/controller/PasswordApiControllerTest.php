<?php
namespace OCA\Passwords\Controller;

require_once __DIR__ . '/PasswordControllerTest.php';

class PasswordApiControllerTest extends PasswordControllerTest {

    public function setUp() {
        parent::setUp();
        $this->controller = new PasswordApiController(
            'passwords', $this->request, $this->service, $this->userId
        );
    }

}