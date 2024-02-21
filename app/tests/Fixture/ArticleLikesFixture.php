<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ArticleLikesFixture
 */
class ArticleLikesFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'article_id' => 1,
                'created_at' => '2024-02-20 14:38:23',
            ],
        ];
        parent::init();
    }
}
