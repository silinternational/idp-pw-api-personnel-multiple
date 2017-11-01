<?php
namespace Sil\IdpPw\Common\Personnel\Multiple;

use InvalidArgumentException;
use Sil\IdpPw\Common\Personnel\NotFoundException;
use Sil\IdpPw\Common\Personnel\PersonnelInterface;
use Sil\IdpPw\Common\Personnel\PersonnelUser;
use yii\base\Component;

/**
 * Class Multiple
 * Query multiple personnel backends and update PersonnelUser response object from each.
 * If user is not found in one of the backends throw a NotFoundException.
 * @package Sil\IdpPw\Personnel\Multiple
 */
class Multiple extends Component implements PersonnelInterface
{
    /** @var  array */
    public $personnelBackendConfig;

    /** @var PersonnelInterface[] */
    public $personnelBackends = [];

    /**
     * Iterate through configured backends to instantiate backend objects and load them into $this->personnelBackends
     */
    public function init()
    {
        parent::init();

        if (empty($this->personnelBackendConfig)) {
            throw new InvalidArgumentException(
                'You must provide config for at least one personnel backend.',
                1509465124
            );
        }

        foreach ($this->personnelBackendConfig as $personnelBackendConfig) {
            $className = $personnelBackendConfig['class'];

            $configForClass = $personnelBackendConfig;
            unset($configForClass['class']);

            $this->personnelBackends[] = new $className($configForClass);
        }
    }

    /**
     * @param mixed $employeeId
     * @return PersonnelUser
     * @throws NotFoundException
     * @throws \Exception
     */
    public function findByEmployeeId($employeeId): PersonnelUser
    {
        $foundUsers = [];
        foreach ($this->personnelBackends as $personnelBackend) {
            $foundUsers[] = $personnelBackend->findByEmployeeId($employeeId);
        }

        return $this->mergePersonnelUsers($foundUsers);
    }

    /**
     * @param mixed $username
     * @return PersonnelUser
     * @throws NotFoundException
     * @throws \Exception
     */
    public function findByUsername($username): PersonnelUser
    {
        $foundUsers = [];
        foreach ($this->personnelBackends as $personnelBackend) {
            $foundUsers[] = $personnelBackend->findByUsername($username);
        }

        return $this->mergePersonnelUsers($foundUsers);
    }

    /**
     * @param mixed $email
     * @return PersonnelUser
     * @throws NotFoundException
     * @throws \Exception
     */
    public function findByEmail($email): PersonnelUser
    {
        $foundUsers = [];
        foreach ($this->personnelBackends as $personnelBackend) {
            $foundUsers[] = $personnelBackend->findByEmail($email);
        }

        return $this->mergePersonnelUsers($foundUsers);
    }

    /**
     * Take array of PersonnelUser objects and merge them together to return a single PersonnelUser
     * Throws exeception if the employee_id does not match for each record.
     * @param array $personnelUsers
     * @return PersonnelUser
     * @throws
     */
    private function mergePersonnelUsers(array $personnelUsers): PersonnelUser
    {
        $personnelUser = new PersonnelUser();
        foreach ($personnelUsers as $user) {
            /** @var PersonnelUser $user */
            // Make sure employee_id matches
            if ($personnelUser->employeeId !== null && $personnelUser->employeeId !== $user->employeeId) {
                throw new \Exception(
                    sprintf(
                        'Employee IDs don\'t match when trying to merge personnel user records. %s != %s',
                        $personnelUser->employeeId,
                        $user->employeeId
                    ),
                    1509479291
                );
            }

            $personnelUser->firstName       = $user->firstName          ?? $personnelUser->firstName;
            $personnelUser->lastName        = $user->lastName           ?? $personnelUser->lastName;
            $personnelUser->email           = $user->email              ?? $personnelUser->email;
            $personnelUser->employeeId      = $user->employeeId         ?? $personnelUser->employeeId;
            $personnelUser->username        = $user->username           ?? $personnelUser->username;
            $personnelUser->supervisorEmail = $user->supervisorEmail    ?? $personnelUser->supervisorEmail;
            $personnelUser->spouseEmail     = $user->spouseEmail        ?? $personnelUser->spouseEmail;
        }

        return $personnelUser;
    }
}