<?php
declare(strict_types=1);

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\I18n\FrozenTime;
use Migrations\AbstractSeed;

/**
 * Users seed.
 */
class UsersSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        $data = [];

        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'username'  => "user{$i}",
                'email' => "user{$i}@hltech.com",
                'password' =>   (new DefaultPasswordHasher())->hash('password'),
                'created_at'    => FrozenTime::now()->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'updated_at'    => FrozenTime::now()->i18nFormat('yyyy-MM-dd HH:mm:ss'),
            ];
        }

        $this->insert('users', $data);
    }
}
