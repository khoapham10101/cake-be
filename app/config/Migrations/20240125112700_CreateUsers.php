<?php
use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration
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
        $table = $this->table('users');
        $table
            ->addColumn('username', 'string', ['limit' => 255])
            ->addColumn('email', 'string', ['limit' => 255])
              ->addColumn('password', 'string', ['limit' => 255])
              ->addColumn('created_at', 'datetime')
              ->addColumn('updated_at', 'datetime')
              ->create();
    }
}
