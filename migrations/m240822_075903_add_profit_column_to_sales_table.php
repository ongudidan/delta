<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%sales}}`.
 */
class m240822_075903_add_profit_column_to_sales_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sales}}', 'profit', $this->float()->after('total_amount'));
    }
    
    public function safeDown()
    {
        $this->dropColumn('{{%sales}}', 'profit');
    }
    
}
