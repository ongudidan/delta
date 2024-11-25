<?php

namespace app\components;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\db\Exception;

class AuthAssignmentManager extends ActiveRecord
{
    /**
     * Assigns all parent auth items to the 'admin' user.
     * @return bool
     * @throws Exception
     */
    public function assignAdminPermissions()
    {
        // Fetch the 'admin' user's ID
        $adminUserId = (new Query())
            ->select('id')
            ->from('{{%user}}')
            ->where(['username' => 'admin'])
            ->scalar();

        if (!$adminUserId) {
            throw new Exception("Admin user not found.");
        }

        // Fetch all parent auth items
        $parentItems = (new Query())
            ->select('name')
            ->from('{{%auth_item}}')
            ->where(['type' => 1]) // Assuming '1' is the type for parent items
            ->column();

        // Assign each parent item to the admin user
        foreach ($parentItems as $itemName) {
            $assignmentExists = (new Query())
                ->from('{{%auth_assignment}}')
                ->where(['item_name' => $itemName, 'user_id' => $adminUserId])
                ->exists();

            if (!$assignmentExists) {
                Yii::$app->db->createCommand()->insert('{{%auth_assignment}}', [
                    'item_name' => $itemName,
                    'user_id' => $adminUserId,
                    'created_at' => time(),
                ])->execute();
            }
        }

        return true;
    }
}
