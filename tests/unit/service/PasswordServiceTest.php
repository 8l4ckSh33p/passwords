<?php
namespace OCA\Passwords\Service;

use PHPUnit_Framework_TestCase;

use OCP\AppFramework\Db\DoesNotExistException;

use OCA\Passwords\Db\Password;

class PasswordServiceTest extends PHPUnit_Framework_TestCase {

    private $service;
    private $mapper;
    private $userId = 'john';

    public function setUp() {
        $this->mapper = $this->getMockBuilder('OCA\Passwords\Db\PasswordMapper')
            ->disableOriginalConstructor()
            ->getMock();
        $this->service = new PasswordService($this->mapper);
    }

    public function testUpdate() {
        // the existing password
        $password = Password::fromRow([
            'id' => 3,
            'title' => 'yo',
            'content' => 'nope'
        ]);
        $this->mapper->expects($this->once())
            ->method('find')
            ->with($this->equalTo(3))
            ->will($this->returnValue($password));

        // the password when updated
        $updatedPassword = Password::fromRow(['id' => 3]);
        $updatedPassword->setTitle('title');
        $updatedPassword->setContent('content');
        $this->mapper->expects($this->once())
            ->method('update')
            ->with($this->equalTo($updatedPassword))
            ->will($this->returnValue($updatedPassword));

        $result = $this->service->update(3, 'title', 'content', $this->userId);

        $this->assertEquals($updatedPassword, $result);
    }


    /**
     * @expectedException OCA\Passwords\Service\NotFoundException
     */
    public function testUpdateNotFound() {
        // test the correct status code if no password is found
        $this->mapper->expects($this->once())
            ->method('find')
            ->with($this->equalTo(3))
            ->will($this->throwException(new DoesNotExistException('')));

        $this->service->update(3, 'title', 'content', $this->userId);
    }

}