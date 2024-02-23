<?php

namespace App\Controller\Api;

use App\Controller\AppController;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Utility\Security;
use Cake\View\JsonView;
use Firebase\JWT\JWT;

/**
 * Auth Controller
 *
 */
class AuthController extends AppController
{

    const TOKEN_HOUR_LIVE = (HOUR * 2);

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
        $this->Authentication->addUnauthenticatedActions(['login', 'register']);
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
            $entity = $result->getData();
            $tokenJwt = JWT::encode([
                'sub' => $entity->id,
                'exp' => time() + 604800,
            ], Security::getSalt(), 'HS256');

            $this->set([
                'token_type' => 'bearer',
                'token' => $tokenJwt,
                'token_expire' => time() + 604800,
                'data' => $entity
            ]);

            $this->viewBuilder()->setOption('serialize', ['token_type', 'token', 'token_expire', 'data']);
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

    public function logout()
    {
        $json = [
            'status'    => true,
            'message'   => 'Logout successfully'
        ];

        $this->Authentication->logout();

        $this->set(compact('json'));
        $this->viewBuilder()->setOption('serialize', 'json');
    }

    public function register()
    {
        $this->request->allowMethod(['post']);

        $user = $this->Users->newEmptyEntity();
        $user = $this->Users->patchEntity($user, $this->request->getData());

        if ($this->Users->save($user)) {
            $this->set([
                'message' => 'Registration successful',
                'data' => $user,
            ]);
        } else {
            $this->set([
                'message' => 'Registration failed',
                'errors' => $user->getErrors(),
            ]);
        }

        $this->viewBuilder()->setOption('serialize', ['message', 'token', 'data', 'errors']);
    }
}
