<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%seed_payment_methods}}`.
 */
class m240815_095051_create_seed_payment_methods_table extends Migration
{
   /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('{{%payment_methods}}', ['name', 'created_at', 'updated_at'], [
            ['Cash', time(), time()],
            ['M-PESA', time(), time()],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%payment_methods}}', ['name' => [ 'Cash','M-PESA']]);
    }
}
