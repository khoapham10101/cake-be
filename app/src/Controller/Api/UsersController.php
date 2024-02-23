<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\View\JsonView;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    public function index()
    {
        $users = $this->Users->find('all');
        $this->set([
            'success' => true,
            'data' => $users
        ]);
        $this->viewBuilder()->setOption('serialize', ['success', 'data']);
    }

    public function view($id)
    {
        $user = $this->Users->find()
            ->where(['id' => $id])
            ->first();

        if ($user) {
            $this->set([
                'success' => true,
                'data' => $user
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'data']);
        } else {
            $this->set([
                'success' => false,
                'message' => sprintf('User %s not found', $id)
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
        }
    }

    public function add()
    {
        $user = $this->Users->newEntity($this->request->getData());

        if ($user->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $user->getErrors()
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message', 'errors']);
        } elseif ($this->Users->save($user)) {
            $this->set([
                'success' => true,
                'data' => $user,
                'message' => 'Creation successful'
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'data']);
        } else {
            $this->set([
                'success' => false,
                'message' => 'Failed to add user'
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
        }
    }

    public function edit($id)
    {
        $user = $this->Users->get($id);

        if ($this->request->is(['patch', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());

            if ($user->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $user->getErrors()
                ]);
                $this->viewBuilder()->setOption('serialize', ['success', 'message', 'errors']);
            } elseif ($this->Users->save($user)) {
                $this->set([
                    'success' => true,
                    'message' => 'Update successful',
                    'data' => $user
                ]);
                $this->viewBuilder()->setOption('serialize', ['success', 'message', 'data']);
            } else {
                $this->set([
                    'success' => false,
                    'message' => 'Failed to update user'
                ]);
                $this->viewBuilder()->setOption('serialize', ['success', 'message']);
            }
        }
    }

    public function delete($id)
    {
        $this->request->allowMethod(['delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->set([
                'success' => true,
                'message' => 'User deleted'
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
        } else {
            $this->set([
                'success' => false,
                'message' => 'Failed to delete User'
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
        }
    }
}
