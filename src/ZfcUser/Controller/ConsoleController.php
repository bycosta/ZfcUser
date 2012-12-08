<?php

namespace ZfcUser\Controller;

use Zend\Console\Request as ConsoleRequest;
use Zend\Math\Rand;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Stdlib\Hydrator\ClassMethods;
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
     * Registers a new user in the database
     *
     * @return String
     */
    public function registerAction()
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
        $params = Array(
            'email'        => $request->getParam('email'),
            'password'     => $request->getParam('password', Rand::getString(16)),
            'username'     => $request->getParam('username'),
            'display_name' => $request->getParam('displayname'),
            'state'        => $request->getParam('state'),
        );


        /**
         * Handle passwordVerify field
         */
        $params['passwordVerify'] = $params['password'];



        /**
         * Validate against form
         */
        $service = $this->getUserService();
        $class   = $this->getOptions()->getUserEntityClass();
        $user    = new $class;
        $form    = $service->getRegisterForm();
        $form->setHydrator(new ClassMethods());
        $form->bind($user);
        $form->setData($params);


        /**
         * Handle invalid data
         */
        if (!$form->isValid()) {

            $errors   = Array();

            foreach ($form->getMessages() as $field => $messages) {
                $errors[] = $field.": ".implode(", ", $messages);
            }

            $pre = "\n - ";
            return "ERROR: Unable to register new user!\n"
                  .$pre.implode($pre, $errors)."\n";
        }


        /**
         * Attempt to register
         */
        $user = $service->register($params);

        $password = (!$request->getParam('password')) ? "Generated password: {$params['password']}\n" : '';
        return "User successfully registered for {$params['email']}.\n".$password;
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
