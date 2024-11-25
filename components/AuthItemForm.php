<?php

namespace app\components;

use app\modules\dashboard\models\AuthItem;
use yii\base\Model;

class AuthItemForm extends Model
{
    public $name; // Parent item name
    public $description;
    public $children = []; // Array of child item names

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['children'], 'each', 'rule' => ['string']],
        ];
    }

    // Static method to find an auth item by name
    public static function findOne($name)
    {
        // Fetch the auth item from the database using the AuthItem model
        return AuthItem::findOne($name);
    }
    
}

