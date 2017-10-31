<?php
namespace tests\mock;

use tests\mock\BackendOne;

class BackendTwo extends BackendOne
{
    public $data = [
        'exists' => [
            'employee_id' => '12345',
            'first_name' => 'John2',
            'last_name' => 'Smith2',
            'username' => 'john_smith2',
            'email' => 'john_smith2@example.com',
            'supervisor_email' => 'john_supervisor2@example.com',
            'spouse_email' => 'john_spouse2@example.com',
        ],
        'conflict' => [
            'employee_id' => '131313',
            'first_name' => 'Conflict',
            'last_name' => 'User',
            'username' => 'conflict_user',
            'email' => 'conflict_user@example.com',
        ],
        'onlyInBackendTwo' => [
            'employee_id' => '333333',
            'first_name' => 'Only',
            'last_name' => 'In2',
            'username' => 'only_in2',
            'email' => 'only_in2@example.com',
        ],
    ];
}