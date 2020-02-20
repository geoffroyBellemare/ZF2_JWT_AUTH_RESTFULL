<?php
/**
 * Created by PhpStorm.
 * User: geoffroy
 * Date: 24/07/2018
 * Time: 22:57
 */

namespace User\Service;


use User\Entity\User;

interface UserService
{

    const GROUP_REGULAR = 1;
    /**
     * @param User $user
     * @return void
     */
    public function save(User $user);

    /**
     * @param $id
     * @return void
     */
    public function delete($id);

    /**
     * @param $id
     * @return User $user
     */
    public function fetch($id);

    /**
     * @param $id
     * @return User|null
     */
    public function fetchByGoogleId($id);

    /**
     * @param array $data
     * @return void
     */
    public function update(array $data);

    /**
     * @return \Zend\Authentication\AuthenticationService $authenticationService
     */
    public function getAuthenticationService();

    /**
     * @param $email string
     * @param $password string
     * @return boolean
     */
    public  function login($email, $password);
}