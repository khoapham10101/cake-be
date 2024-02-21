<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateArticleLikes extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $this->table('article_likes')
            ->addColumn('user_id', 'integer', ['null' => false])
            ->addColumn('article_id', 'integer', ['null' => false])
            ->addColumn('created_at', 'datetime', ['null' => false])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('article_id', 'articles', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addIndex(['user_id', 'article_id'], ['unique' => true])
            ->create();
    }
}
