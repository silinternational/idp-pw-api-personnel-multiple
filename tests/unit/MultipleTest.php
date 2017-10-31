<?php

use PHPUnit\Framework\TestCase;
use Sil\IdpPw\Common\Personnel\Multiple\Multiple;
use tests\mock\BackendOne;
use tests\mock\BackendTwo;

class MultipleTest extends TestCase
{
    public function testSuccessfulMerge()
    {
        $multipleBackend = new Multiple([
            'personnelBackendConfig' => [
                [
                    'class' => BackendOne::class
                ],
                [
                    'class' => BackendTwo::class
                ]
            ]
        ]);

        $mergedUser = $multipleBackend->findByEmployeeId('12345');
        $this->assertEquals('12345', $mergedUser->employeeId);
        $this->assertEquals('John2', $mergedUser->firstName);
        $this->assertEquals('Smith2', $mergedUser->lastName);
        $this->assertEquals('john_smith2', $mergedUser->username);
        $this->assertEquals('john_smith2@example.com', $mergedUser->email);
        $this->assertEquals('john_supervisor2@example.com', $mergedUser->supervisorEmail);
        $this->assertEquals('john_spouse2@example.com', $mergedUser->spouseEmail);
    }

    public function testConflictException()
    {
        $multipleBackend = new Multiple([
            'personnelBackendConfig' => [
                [
                    'class' => BackendOne::class
                ],
                [
                    'class' => BackendTwo::class
                ]
            ]
        ]);

        $this->expectExceptionCode(1509479291);
        $multipleBackend->findByUsername('conflict_user');
    }

    public function testMissingInFirst()
    {
        $multipleBackend = new Multiple([
            'personnelBackendConfig' => [
                [
                    'class' => BackendOne::class
                ],
                [
                    'class' => BackendTwo::class
                ]
            ]
        ]);

        $this->expectException('\Sil\IdpPw\Common\Personnel\NotFoundException');
        $multipleBackend->findByEmail('only_in2@example.com');
    }

    public function testMissingInSecond()
    {
        $multipleBackend = new Multiple([
            'personnelBackendConfig' => [
                [
                    'class' => BackendOne::class
                ],
                [
                    'class' => BackendTwo::class
                ]
            ]
        ]);

        $this->expectException('\Sil\IdpPw\Common\Personnel\NotFoundException');
        $multipleBackend->findByEmail('only_in1@example.com');
    }
}