<?php
/**
 * Created by PhpStorm.
 * User: geoffroy
 * Date: 24/07/2018
 * Time: 22:34
 */

namespace User\Repository;


use User\Entity\User;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;

interface UserRepository extends RepositoryInterface
{

    /**
     * Set db adapter
     *
     * @param Adapter $adapter
     * @return AdapterAwareInterface
     */
    /**
     * @param User $user
     * @return void
     */
    public function save(User $user);

    /**
     * @param int $id
     * @return void
     */
    public function delete($id);

    /**
     * @param array $data
     * @return void
     */
    public function  update(array $data);

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
     * @return \Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter $CallbackCheckAdapter
     */
    public function getAuthenticationAdapter();



}