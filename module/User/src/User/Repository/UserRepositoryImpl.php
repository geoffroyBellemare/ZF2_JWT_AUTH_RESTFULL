<?php
/**
 * Created by PhpStorm.
 * User: geoffroy
 * Date: 24/07/2018
 * Time: 22:47
 */

namespace User\Repository;


use User\Entity\User;
use User\Service\UserService;
use Zend\Crypt\Password\Bcrypt;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Db\Sql\Sql;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Db\ResultSet\HydratingResultSet;

class UserRepositoryImpl implements UserRepository
{
    use AdapterAwareTrait;
    /**
     * @param User $user
     * @return void
     */
    public function save(User $user)
    {

//
//USERS
//
//
        $sql = new Sql($this->adapter);
        $insert = $sql->insert('users');
        $insert->values([
            'user_name' => $user->getUsername(),
//            'first_name' => $user->getFirstName(),
//            'last_name' => $user->getLastName(),
            'email' => $user->getEmail(),
            'password' => $this->generatePassword($user->getPassword()),
            //'created' => time(),
            //'user_group' => $user->getUserGroup(),
            //'google_id' => ($user->getGoogleId() != null )? $user->getGoogleId(): null,
            //'credits'   => $user->getCredits(),
        ]);
        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }

    /**
     * @param int $id
     * @return void
     */
    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param array $data
     * @return void
     */
    public function update(array $data)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $update = $sql->update('user');
        $update->set($data)
            ->where(array('id'=> $data['id'] ));
        $statement = $sql->prepareStatementForSqlObject($update);
        $statement->execute($statement);

 /*       $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->update('post')
            ->set(array(
                'title' => $post->getTitle(),
                'slug' => $post->getSlug(),
                'content' => $post->getContent(),
                'category_id' => $post->getCategory()->getId(),
            ))
            ->where(array(
                'id' => $post->getId(),
            ));

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();*/
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
     * @param string $visiblePassword
     * @return string
     */
    protected function generatePassword($visiblePassword) {
        $bcrypt = new Bcrypt();
        $bcrypt->setCost(12);
        return $bcrypt->create($visiblePassword);
    }

    /**
     * @return void|\Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter
     */
    public function getAuthenticationAdapter()
    {
        // TODO: Implement getAuthenticationAdapter() method.

        $callBack = function ($hashPassword, $clearPaaaword) {
//            var_dump($hashPassword);
//            var_dump($clearPaaaword);

            $bcrypt = new Bcrypt();
            $bcrypt->setCost(12);

            return $bcrypt->verify($clearPaaaword, $hashPassword);
        };
        return new \Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter(
            $this->adapter,
            'users',
            'email',
            'password',
            $callBack
        );
    }


    public function fetchByGoogleId($id)
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $select
            ->columns(
                array(
                    '*'
                )
            )
            ->from('user')
            ->where(array(
                'google_id' => $id
            ));
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $resultSet = new HydratingResultSet(new ClassMethods(), new User());
        $resultSet->initialize($results);

        return ($resultSet->count() > 0 ? $resultSet->current() : null);

    }
}