<?php

namespace ZfcUser\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\Request as ConsoleRequest;
use ZfcUser\Service\User as UserService;
use ZfcUser\Options\UserControllerOptionsInterface;

class ConsoleController extends AbstractActionController
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var UserControllerOptionsInterface
     */
    protected $options;


    /**
     * List users in database
     *
     * @return String
     */
    public function listAction()
    {
        /**
         * Enforce valid console request
         */
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest){
            throw new \RuntimeException('You can only use this action from a console!');
        }


        /**
         * Retrieve parameters
         */
        $state  = $request->getParam('state', false);
        $fields = $request->getParam('fields');


        return "Reached ConsoleController::listAction();\n";
    }


    /**
     * View specific user in db
     *
     * @return String
     */
    public function viewAction()
    {
        /**
         * Enforce valid console request
         */
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest){
            throw new \RuntimeException('You can only use this action from a console!');
        }


        /**
         * Retrieve parameters
         */
        $identity = $request->getParam('id');


        return "Reached ConsoleController::viewAction();\n";
    }


    /**
     * Add new user into database
     *
     * @return String
     */
    public function addAction()
    {
        /**
         * Enforce valid console request
         */
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest){
            throw new \RuntimeException('You can only use this action from a console!');
        }


        /**
         * Retrieve parameters
         */
        $email       = $request->getParam('email');
        $password    = $request->getParam('password');
        $username    = $request->getParam('username');
        $displayname = $request->getParam('displayname');
        $state       = $request->getParam('state');


        return "Reached ConsoleController::addAction();\n";
    }


    /**
     * Getters/setters for DI stuff
     */

    public function getUserService()
    {
        if (!$this->userService) {
            $this->userService = $this->getServiceLocator()->get('zfcuser_user_service');
        }
        return $this->userService;
    }

    public function setUserService(UserService $userService)
    {
        $this->userService = $userService;
        return $this;
    }


    /**
     * set options
     *
     * @param UserControllerOptionsInterface $options
     * @return UserController
     */
    public function setOptions(UserControllerOptionsInterface $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * get options
     *
     * @return UserControllerOptionsInterface
     */
    public function getOptions()
    {
        if (!$this->options instanceof UserControllerOptionsInterface) {
            $this->setOptions($this->getServiceLocator()->get('zfcuser_module_options'));
        }
        return $this->options;
    }
}
