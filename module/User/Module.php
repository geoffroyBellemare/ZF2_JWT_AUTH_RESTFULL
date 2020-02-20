<?php

namespace User;

use Zend\Http\Request as HttpRequest;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ModelInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\ModuleRouteListener;
use User\Form\FieldSet\RegisterFieldSet;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\FormElementProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

class Module implements ConfigProviderInterface, ServiceProviderInterface, AutoloaderProviderInterface
{

    public function onBootstrap(MvcEvent $e)
    {
//        $eventManager = $e->getApplication()->getEventManager();
//        $moduleRouteListener = new ModuleRouteListener();
//        $moduleRouteListener->attach($eventManager);
//
//        $sharedManager = $e->getApplication()->getEventManager()->getSharedManager();
//        $sm = $e->getApplication()->getServiceManager();
//        $sharedManager->attach(
//            'Zend\Mvc\Application',
//            'dispatch.error',
//            array($this, 'renderError')
//        );
        $app      = $e->getTarget();
        $locator  = $app->getServiceManager();
        $view     = $locator->get('ZendViewView');
        $strategy = $locator->get('ViewJsonStrategy');
        $view->getEventManager()->attach($strategy, 100);

        // attach a listener to check for errors
        $events = $e->getTarget()->getEventManager();
        $events->attach(MvcEvent::EVENT_RENDER, array($this, 'renderError'));
    }
    public function renderError($e)
    {

        if (!$e->isError()) {
            return;
        }

        $request = $e->getRequest();
        if (!$request instanceof HttpRequest) {
            return;
        }

        $headers = $request->getHeaders();


        if (!$headers->has('Accept')) {
            return;
        }

        $accept = $headers->get('Accept');

        $match  = $accept->match('application/json');
        if (!$match || $match->getTypeString() == '*/*') {
            // not application/json
            //var_dump('allo pas json appi');
           // return;
        }

        $currentModel = $e->getResult();
        if ($currentModel instanceof JsonModel) {
           // return;
        }
        $exception  = $currentModel->getVariable('exception');
        $response = $e->getResponse();
        $model = new JsonModel(array(
            "httpStatus" => $exception->getCode(),
            "title" => $response->getReasonPhrase(),
        ));


        if ($currentModel instanceof ModelInterface && $currentModel->reason) {
            switch ($currentModel->reason) {
                case 'error-controller-cannot-dispatch':
                    $model->detail = 'The requested controller was unable to dispatch the request.';
                    break;
                case 'error-controller-not-found':
                    $model->detail = 'The requested controller could not be mapped to an existing controller class.';
                    break;
                case 'error-controller-invalid':
                    $model->detail = 'The requested controller was not dispatchable.';
                    break;
                case 'error-router-no-match':
                    $model->detail = 'The requested URL could not be matched by routing.';
                    break;
                default:
                    $model->detail = $currentModel->message;
                    break;
            }
        }

        if ($exception) {
            if ($exception->getCode()) {
                $e->getResponse()->setStatusCode($exception->getCode());
            }
            $model->detail = $exception->getMessage();

            // find the previous exceptions
            $messages = array();
            while ($exception = $exception->getPrevious()) {
                $messages[] = "* " . $exception->getMessage();
            };
            if (count($messages)) {
                $exceptionString = implode("n", $messages);
                $model->messages = $exceptionString;
            }
        }

        // set our new view model
        $model->setTerminal(true);
        $e->setResult($model);
        $e->setViewModel($model);
        //$e->stopPropagation();
        //$routeMatch = $e->getRouteMatch();
        //$routeParams = $routeMatch->getParams();

    }
    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getServiceConfig()
    {
        return include __DIR__ . '/config/service.config.php';
    }

}