<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ArticleLikes Controller
 *
 * @property \App\Model\Table\ArticleLikesTable $ArticleLikes
 * @method \App\Model\Entity\ArticleLike[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticleLikesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Users', 'Articles'],
        ];
        $articleLikes = $this->paginate($this->ArticleLikes);

        $this->set(compact('articleLikes'));
    }

    /**
     * View method
     *
     * @param string|null $id Article Like id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $articleLike = $this->ArticleLikes->get($id, [
            'contain' => ['Users', 'Articles'],
        ]);

        $this->set(compact('articleLike'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $articleLike = $this->ArticleLikes->newEmptyEntity();
        if ($this->request->is('post')) {
            $articleLike = $this->ArticleLikes->patchEntity($articleLike, $this->request->getData());
            if ($this->ArticleLikes->save($articleLike)) {
                $this->Flash->success(__('The article like has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The article like could not be saved. Please, try again.'));
        }
        $users = $this->ArticleLikes->Users->find('list', ['limit' => 200])->all();
        $articles = $this->ArticleLikes->Articles->find('list', ['limit' => 200])->all();
        $this->set(compact('articleLike', 'users', 'articles'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Article Like id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $articleLike = $this->ArticleLikes->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $articleLike = $this->ArticleLikes->patchEntity($articleLike, $this->request->getData());
            if ($this->ArticleLikes->save($articleLike)) {
                $this->Flash->success(__('The article like has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The article like could not be saved. Please, try again.'));
        }
        $users = $this->ArticleLikes->Users->find('list', ['limit' => 200])->all();
        $articles = $this->ArticleLikes->Articles->find('list', ['limit' => 200])->all();
        $this->set(compact('articleLike', 'users', 'articles'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Article Like id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $articleLike = $this->ArticleLikes->get($id);
        if ($this->ArticleLikes->delete($articleLike)) {
            $this->Flash->success(__('The article like has been deleted.'));
        } else {
            $this->Flash->error(__('The article like could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
