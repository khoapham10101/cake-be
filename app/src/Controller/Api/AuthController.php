<?php

namespace App\Controller\Api;

use App\Controller\AppController;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\View\JsonView;

/**
 * Auth Controller
 *
 */
class AuthController extends AppController
{

    public function initialize(): void {
        parent::initialize();
        $this->loadModel('Users');
    }


    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        // Configure the login action to not require authentication, preventing
        // the infinite redirect loop issue
        $this->Authentication->addUnauthenticatedActions(['login']);
    }

    public function login()
    {
        $data =  $this->request->getData();
        $this->request->allowMethod(['get', 'post']);
        $userEntity = $this->Users->find()->where(['email' => $data['email']])->first();

        if ($userEntity && (new DefaultPasswordHasher())->check($data['password'], $userEntity->password)) {
            $this->Authentication->setIdentity($userEntity);
        }

        $result = $this->Authentication->getResult();

        // regardless of POST or GET, redirect if user is logged in
        if ($result && $result->isValid()) {
            // redirect to /articles after login success
            $this->set([
                'success' => false,
                'message' => 'Login successfully',
                'data'    => $result->getData()
            ]);

            $this->viewBuilder()->setOption('serialize', ['success', 'message', 'data']);
        }
        // display error if user submitted and authentication failed
        if ($this->request->is('post') && !$result->isValid()) {
            $this->set([
                'success' => false,
                'message' => 'Invalid username or password'
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
        }
    }
}
