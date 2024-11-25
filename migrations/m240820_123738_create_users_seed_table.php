<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users_seed}}`.
 */
class m240820_123738_create_users_seed_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insertFakeUsers();
    }

    private function insertFakeUsers()
    {
        $faker = \Faker\Factory::create();

        $users = [
            [
                'username' => 'admin',
                'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
                'status'=> 10,
                'auth_key'=>'admin',
                'email'=>'admin@gmail.com',
                'created_at' => $faker->date,
                'updated_at' => (int)$faker->date,
            ],
            [
                'username' => 'dan',
                'password_hash' => Yii::$app->security->generatePasswordHash('dan'),
                'status'=> 10,
                'auth_key'=>'dan',
                'email'=>'dan@gmail.com',
                'created_at' => $faker->date,
                'updated_at' => (int)$faker->date,
            ],
        ];

        foreach ($users as $user) {
            $this->insert('user', $user);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('users', ['username' => 'admin']);
        $this->delete('users', ['username' => 'dan']);
    }
}
