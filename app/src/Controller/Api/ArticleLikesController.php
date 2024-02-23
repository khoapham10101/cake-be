<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\View\JsonView;

/**
 * ArticleLikes Controller
 *
 * @property \App\Model\Table\ArticleLikesTable $ArticleLikes
 * @method \App\Model\Entity\ArticleLike[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticleLikesController extends AppController
{

    public function viewClasses(): array
    {
        return [JsonView::class];
    }

   /**
     * Add or remove favorite for the authenticated user.
     *
     * @param int $articleId
     * @param string $action 'add' to add to favorites, 'remove' to remove from favorites
     * @return \Cake\Http\Response
     */
    public function favorite()
    {
        $data = $this->request->getData();
        $this->loadModel('Articles');
        $userId = $this->Authentication->getIdentity()->get('id');

        $article = $this->Articles->find()
        ->where(['id' => $data['article_id']])
        ->first();

        if (!$article) {
            $this->set([
                'success' => false,
                'message' => sprintf('article %s not found', $data['article_id'])
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'message']);
        }

        // Check if the record already exists
        $exists = $this->ArticleLikes->exists(['article_id' => $article->id, 'user_id' => $userId]);

        if ($exists) {
            $result = $this->ArticleLikes->deleteAll(['article_id' => $article->id, 'user_id' => $userId]);

            if ($result) {
                $message = 'Article removed from favorites.';
            } else {
                $message = 'Error removing article from favorites.';
            }
        } else {

            $articleUser = $this->ArticleLikes->newEntity(['article_id' => $article->id, 'user_id' => $userId]);

            if ($this->ArticleLikes->save($articleUser)) {
                $message = 'Article added to favorites.';
            } else {
                $errors = $articleUser->getErrors();
                debug($errors);
                $message = 'Error adding article to favorites.';
            }

        }

        $this->set([
            'success' => true,
            'message' => $message,
        ]);

        $this->viewBuilder()->setOption('serialize', ['success', 'message']);
    }

    public function getFavoriteArticles()
    {
        $this->loadModel('Articles');
        $userId = $this->Authentication->getIdentity()->get('id');

        $favorites = $this->ArticleLikes->find('all', [
            'conditions' => ['ArticleLikes.user_id' => $userId],
            'contain' => ['Articles'],
        ])->toArray();

        $this->set([
            'success' => true,
            'data' => $favorites
        ]);

        $this->viewBuilder()->setOption('serialize', ['success', 'data']);
    }
}
