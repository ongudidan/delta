<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%seed_categories}}`.
 */
class m240813_115623_create_seed_categories_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Insert categories
        $this->batchInsert('{{%categories}}', ['category_name', 'description', 'created_at', 'updated_at'], [
            ['Skincare Products', 'Products related to skincare including creams, lotions, and serums.', time(), time()],
            ['Hair Care Products', 'Products related to hair care such as shampoos, conditioners, and styling products.', time(), time()],
            ['Makeup Products', 'Cosmetic products for facial makeup including foundations, lipsticks, and powders.', time(), time()],
            ['Fragrance and Deodorants', 'Perfumes, body sprays, and deodorants.', time(), time()],
            ['Nail Care Products', 'Products for nail care including nail polish, buffers, and nail glue.', time(), time()],
            ['Jewelry and Accessories', 'Various jewelry and fashion accessories.', time(), time()],
            ['Body Care Products', 'Products for body care including shower gels, scrubs, and lotions.', time(), time()],
            ['Miscellaneous', 'Various other products such as makeup brushes, manicure sets, and mirrors.', time(), time()],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Remove the inserted categories
        $this->delete('{{%categories}}', ['category_name' => [
            'Skincare Products', 
            'Hair Care Products', 
            'Makeup Products', 
            'Fragrance and Deodorants', 
            'Nail Care Products', 
            'Jewelry and Accessories', 
            'Body Care Products', 
            'Miscellaneous'
        ]]);
    }
}
