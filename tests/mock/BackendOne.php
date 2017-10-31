<?php
namespace tests\mock;

use Sil\IdpPw\Common\Personnel\NotFoundException;
use Sil\IdpPw\Common\Personnel\PersonnelInterface;
use Sil\IdpPw\Common\Personnel\PersonnelUser;


class BackendOne implements PersonnelInterface
{
    public $data = [
        'exists' => [
            'employee_id' => '12345',
            'first_name' => 'John',
            'last_name' => 'Smith',
            'display_name' => 'John Smith',
            'username' => 'john_smith',
            'email' => 'john_smith@example.com',
        ],
        'conflict' => [
            'employee_id' => '121212',
            'first_name' => 'Conflict',
            'last_name' => 'User',
            'display_name' => 'Conflict User',
            'username' => 'conflict_user',
            'email' => 'conflict_user@example.com',
        ],
        'onlyInBackendOne' => [
            'employee_id' => '222222',
            'first_name' => 'Only',
            'last_name' => 'In1',
            'username' => 'only_in1',
            'email' => 'only_in1@example.com',
        ],
    ];

    /**
     * @param mixed $employeeId
     * @return PersonnelUser
     * @throws NotFoundException
     * @throws \Exception
     */
    public function findByEmployeeId($employeeId)
    {
        return $this->findByAttribute('employee_id', $employeeId);
    }

    /**
     * @param mixed $username
     * @return PersonnelUser
     * @throws NotFoundException
     * @throws \Exception
     */
    public function findByUsername($username)
    {
        return $this->findByAttribute('username', $username);
    }

    /**
     * @param mixed $email
     * @return PersonnelUser
     * @throws NotFoundException
     * @throws \Exception
     */
    public function findByEmail($email)
    {
        return $this->findByAttribute('email', $email);
    }


    private function findByAttribute($attribute, $value): PersonnelUser
    {
        foreach ($this->data as $user) {
            if ($user[$attribute] == $value) {
                $personnelUser = new PersonnelUser();
                $personnelUser->employeeId = $user['employee_id'];
                $personnelUser->firstName = $user['first_name'];
                $personnelUser->lastName = $user['last_name'];
                $personnelUser->username = $user['username'];
                $personnelUser->email = $user['email'];
                $personnelUser->supervisorEmail = $user['supervisor_email'] ?? null;
                $personnelUser->spouseEmail = $user['spouse_email'] ?? null;
                return $personnelUser;
            }
        }

        throw new NotFoundException();
    }
}