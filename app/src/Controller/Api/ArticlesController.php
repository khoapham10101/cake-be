<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\View\JsonView;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController
{

    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    public function index()
    {
        $articles = $this->Articles->find('all');
        $this->set([
            'success' => true,
            'data' => $articles
        ]);
        $this->viewBuilder()->setOption('serialize', ['success', 'data']);
    }

    public function view($id)
    {
        $article = $this->Articles->find()
            ->where(['id' => $id])
            ->first();

        if ($article) {
            $this->set([
                'success' => true,
                'data' => $article
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'data']);
        } else {
            $this->set([
                'success' => false,
                'message' => sprintf('article %s not found', $id)
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
        }
    }

    public function add()
    {
        $article = $this->Articles->newEntity($this->request->getData());

        if ($article->hasErrors()) {
            $this->response = $this->response->withStatus(400);
            $this->set([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $article->getErrors()
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message', 'errors']);
        } elseif ($this->Articles->save($article)) {
            $this->set([
                'success' => true,
                'data' => $article,
                'message' => 'Creation successful'
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'data']);
        } else {
            $this->set([
                'success' => false,
                'message' => 'Failed to add article'
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
        }
    }

    public function edit($id)
    {
        $article = $this->Articles->get($id);

        if (!$article) {
            $this->set([
                'success' => false,
                'message' => 'Artiles not found'
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message', 'errors']);
        }

        if ($this->request->is(['patch', 'put'])) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            if ($article->hasErrors()) {
                $this->response = $this->response->withStatus(400);
                $this->set([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $article->getErrors()
                ]);
                $this->viewBuilder()->setOption('serialize', ['success', 'message', 'errors']);
            } elseif ($this->Articles->save($article)) {
                $this->set([
                    'success' => true,
                    'message' => 'Update successful',
                    'data' => $article
                ]);
                $this->viewBuilder()->setOption('serialize', ['success', 'message', 'data']);
            } else {
                $this->set([
                    'success' => false,
                    'message' => 'Failed to update article'
                ]);
                $this->viewBuilder()->setOption('serialize', ['success', 'message']);
            }
        }
    }

    public function delete($id)
    {
        $this->request->allowMethod(['delete']);
        $article = $this->Articles->get($id);
        if ($this->Articles->delete($article)) {
            $this->set([
                'success' => true,
                'message' => 'article deleted'
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
        } else {
            $this->set([
                'success' => false,
                'message' => 'Failed to delete article'
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
        }
    }
}
