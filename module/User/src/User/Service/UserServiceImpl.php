<?php
/**
 * Created by PhpStorm.
 * User: geoffroy
 * Date: 24/07/2018
 * Time: 23:04
 */

namespace User\Service;


use User\Entity\User;
use User\Repository\UserRepository;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use Zend\Authentication\AuthenticationService;


class UserServiceImpl implements UserService
{
    /**
     * @var UserRepository $userRepository
     */
    public $userRepository;
    /**
     * @var \User\Authentication\Storage\Jwt
     */
    private $jwtStorage;
    //Storage\Jwt::class
    /**
     * @param $id
     * @return null|User
     */
    public function fetchByGoogleId($id)
    {
        return $this->userRepository->fetchByGoogleId($id);
    }

    /**
     * @return \User\Authentication\Storage\Jwt
     */
    public function getJwtStorage()
    {
        return $this->jwtStorage;
    }

    /**
     * @param \User\Authentication\Storage\Jwt $jwtStorage
     */
    public function setJwtStorage($jwtStorage)
    {
        $this->jwtStorage = $jwtStorage;
    }




    /**
     * @param UserRepository $userRepository
     */
    public function setUserRepository($userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param User $user
     * @return void
     */
    public function save(User $user)
    {

        $this->userRepository->save($user);
    }

    /**
     * @param $id
     * @return void
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param $id
     * @return User $user
     */
    public function fetch($id)
    {
        // TODO: Implement fetch() method.
    }

    /**
     * @param array $data
     * @return void
     */
    public function update(array $data)
    {
        $this->userRepository->update($data);
        $this->getAuthenticationService()->getStorage()->write($data);
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthenticationService()
    {
        $authAdapter = $this->userRepository->getAuthenticationAdapter();

        return new AuthenticationService($this->jwtStorage,$authAdapter);

    }

    /**
     * @param string $email
     * @param string $password
     * @return string|null
     */
    public function login($email, $password)
    {
        $authService = $this->getAuthenticationService();

        /**
         * @var \Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter $authAdapter
         */
        $authAdapter = $authService->getAdapter();
        $authAdapter->setIdentity($email);
        $authAdapter->setCredential($password);
        $result = $authAdapter->authenticate();
        //$result = $authService->authenticate($authAdapter);
        //var_dump($authService->getStorage()->read());
        if( $result->isValid() ) {
            $identityObject = $authAdapter->getResultRowObject(null,array('password'));
            $authService->getStorage()->write($identityObject);
            return $authService->getStorage()->token->__toString();
            //return true;
        }

        return null;

    }

    public function logout()
    {
        $authService = $this->getAuthenticationService();
        //var_dump('Alors-------: ');
        //var_dump($authService->getStorage()->wrapped->read());

        var_dump($authService->getStorage()->read());
        //var_dump('au dessus-------: ');
        //$authService->clearIdentity();

    }

}