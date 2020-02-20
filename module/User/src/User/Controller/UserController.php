<?php
/**
 * Created by PhpStorm.
 * User: geoffroy
 * Date: 17/02/2020
 * Time: 15:49
 */
namespace User\Controller;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Keychain; // just to make our life simpler
use Lcobucci\JWT\Signer\Rsa\Sha256;

use User\Entity\User;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class UserController extends AbstractRestfulController
{
    protected $collectionMethod = array('GET');
    protected $ressourceMethod = array('GET', 'POST', 'PUT', 'DELETE');

    public function get($id)
    {
        $variables = array();
        $this->options();
        $variables['data'] = "get test";
        return new JsonModel($variables);
    }

    public function getList()
    {
        //$this->params()->fromRoute('slug');
        $this->options();
        $variables = array();
        $variables['data'] = 'test';
        return new JsonModel($variables);
    }


    public function create($data) {

        $this->options();
        //$this->response->setStatusCode(400);
        $userService = $this->getServiceLocator()->get('User\Service\UserService');
        $action = $this->params()->fromRoute('slug');
        $variables = array();
        $user = new User();
        $user->setEmail($data["email"]);
        $user->setUsername($data["username"]);
        $user->setPassword($data["password"]);
        $user->setCredits(4);
        $user->setUserGroup(1);



        switch($action) {
            case "login":
                $variables['data'] = $this->login($user, $userService);
                break;
            case "logout":
/*                $signer = new Sha256();
                $token = (new Builder())->setIssuer('http://example.com') // Configures the issuer (iss claim)
                ->setAudience('http://example.org') // Configures the audience (aud claim)
                ->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
                ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                ->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
                ->setExpiration(time() + 3600) // Configures the expiration time of the token (nbf claim)
                ->set('uid', 1) // Configures a new claim, called "uid"
                ->sign($signer, 'testing') // creates a signature using "testing" as key
                ->getToken(); // Retrieves the generated token


                var_dump($token->verify($signer, 'testing 1')); // false, because the key is different
                var_dump($token->verify($signer, 'testing'));*/
                /*                $token = (new Builder())->setIssuer('http://example.com') // Configures the issuer (iss claim)
                                ->setAudience('http://example.org') // Configures the audience (aud claim)
                                ->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
                                ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                                ->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
                                ->setExpiration(time() + 3600) // Configures the expiration time of the token (nbf claim)
                                ->set('uid', 1) // Configures a new claim, called "uid"
                                ->set('username', "geoff")
                                ->getToken(); // Retrieves the generated token*/
                //var_dump($token->__toString());

//                $signer = new Sha256();
//
//                $keychain = new Keychain();
//
//                $token = (new Builder())->setIssuer('http://example.com') // Configures the issuer (iss claim)
//                ->setAudience('http://example.org') // Configures the audience (aud claim)
//                ->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
//                ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
//                ->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
//                ->setExpiration(time() + 3600) // Configures the expiration time of the token (nbf claim)
//                ->set('uid', 1) // Configures a new claim, called "uid"
//                ->sign($signer,  $keychain->getPrivateKey('file://config/jwt/private.pem')) // creates a signature using your private key
//                ->getToken(); // Retrieves the generated token
//                var_dump($token->verify($signer, $keychain->getPublicKey('file://config/jwt/public.pem')));
                $tokenString = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE1ODIxMDc0NDUsImV4cCI6MTU4MjE2NzQ0NSwic2Vzc2lvbi1kYXRhIjp7InVzZXJfaWQiOiI1IiwiZW1haWwiOiJyaXdhbEBtYWlsLmNvbSIsInVzZXJfbmFtZSI6InJpd2FsIn19.426tqQfUCbY9ilfK3PlBXOR4regwlgKG0O7s28zLGRM";
                $token = (new Parser())->parse($tokenString);
                //var_dump($token->getClaims());
                $variables['data'] = getcwd();// $token->getClaims();
                $userService->logout($user);

                break;
            case "create":
                $userService->save($user);
                $variables['data'] = $user->getEmail();
        }
        return new JsonModel($variables);
    }

    /**
     * @param User $user
     * @return JsonModel
     */
    public function login($user, $service)
    {

//       var_dump( $this->identity());
        if( $this->identity() != null ) {
            //$this->response->setStatusCode(400);
            //throw new \Exception('User already login');


        }
        $userService = $this->getServiceLocator()->get('User\Service\UserService');
        $loginToken = $userService->login($user->getEmail(), $user->getPassword());

        if(!$loginToken) {
            $this->response->setStatusCode(401);
            return 'Wrong Credentiel try Again';
        }
        return $loginToken;

//        $form = new LoginForm();
//        /**
//         * @var UserService $userService
//         */
//        $userService = $this->getServiceLocator()->get('User\Service\UserService');
//
//        if( $this->request->isPost()) {
//            $user = new User();
//            $form->bind($user);
//            $form->setData($this->request->getPost());
//            if ($form->isValid()) {
//                $loginResult = $userService->login($user->getEmail(), $user->getPassword());
//                var_dump($loginResult);
//                if ($loginResult) {
//                    $this->flashMessenger()->addSuccessMessage('You have been logged in.');
//                    $this->redirect()->toRoute('blog');
//                } else {
//                    $this->flashMessenger()->addWarningMessage('Invalid login credentials!');
//                    $this->redirect()->toRoute('login');
//                }
//
//            }
//
//        }
//        return new ViewModel(array(
//            'form' => $form,
//            'googleAuthUrl' => $this->getGoogleClient()->createAuthUrl()
//        ));
    }
    public function update($id, $data) {

        $this->options();
        $action = $this->params()->fromRoute('slug');
        $variables = array();

        switch($action) {
            default:
                $variables['data'] = $data;
        }
        return new JsonModel($variables['data']);
    }

    public function setEventManager(EventManagerInterface $events) {
        parent::setEventManager($events);

        $events->attach('dispatch', array($this, 'checkMethod'), 10);
    }

    protected function _getMethod() {
        if ($this->params()->fromRoute('slug', false)){
            return $this->ressourceMethod;
        }
        return $this->collectionMethod;
    }

    protected function methodNotAllowed()
    {
        $this->response->setStatusCode(405);
        throw new \Exception('Method Not Allowed');
    }

    public function checkMethod($e) {
        if (in_array($e->getRequest()->getMethod(), $this->_getMethod())){
            return;
        }
        $response = $this->getResponse();
        $response->setStatusCode(405);
        return $response;
    }

    public function options() {
        $response = $this->getResponse();
        $response->getHeaders()
            ->addHeaderLine('Allow', implode(',', $this->_getMethod()))
            ->addHeaderLine('Access-Control-Allow-Origin','*')
            //set allow methods
            ->addHeaderLine('Access-Control-Allow-Methods',$this->_getMethod());
        return $response;
    }
}