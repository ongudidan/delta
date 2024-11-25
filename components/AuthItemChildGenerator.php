<?php

namespace app\components; // Adjust this namespace as needed

use Yii;
use yii\db\Query;

class AuthItemChildGenerator
{
    const TYPE_PERMISSION = 2; // Value for permissions in Yii2 RBAC.
    const TYPE_PARENT = 1; // Value for parent permissions in Yii2 RBAC.

    public function generateAuthItemChildren()
    {
        // Get all models and their corresponding permissions
        $modelPermissions = $this->getModelPermissions();

        foreach ($modelPermissions as $modelName => $permissions) {
            $parentItemName = "{$modelName}-parent";

            // Add a parent item for the model
            $this->addAuthItem($parentItemName, "Parent permission for {$modelName}", self::TYPE_PARENT);

            // Create child-parent relationships
            foreach ($permissions as $childItemName) {
                $this->addAuthItemChild($parentItemName, $childItemName);
            }
        }

        Yii::$app->session->setFlash('success', 'Auth item children generation completed.');
    }

    protected function getModelPermissions()
    {
        $modelPermissions = [];

        // Logic to get all models and their respective permissions
        // For this example, we're hardcoding the models and permissions
        // You should replace this with your dynamic model discovery logic
        $actions = ['create', 'view', 'update', 'delete'];
        $directories = [
            Yii::getAlias('@app/models'), // Main models directory
        ];

        // Add each module's `models` folder
        $modulesPath = Yii::getAlias('@app/modules');
        $moduleFolders = scandir($modulesPath);
        foreach ($moduleFolders as $moduleFolder) {
            if ($moduleFolder === '.' || $moduleFolder === '..') continue;

            $modelsPath = $modulesPath . DIRECTORY_SEPARATOR . $moduleFolder . DIRECTORY_SEPARATOR . 'models';
            if (is_dir($modelsPath)) {
                $directories[] = $modelsPath;
            }
        }

        foreach ($directories as $directory) {
            $files = \yii\helpers\FileHelper::findFiles($directory, ['only' => ['*.php']]);
            foreach ($files as $file) {
                $modelName = pathinfo($file, PATHINFO_FILENAME);
                $permissions = array_map(function ($action) use ($modelName) {
                    return "{$modelName}-{$action}"; // E.g., Activity-create
                }, $actions);

                $modelPermissions[$modelName] = $permissions;
            }
        }

        return $modelPermissions;
    }

    protected function addAuthItem($name, $description, $type)
    {
        $authItem = (new Query())
            ->select('name')
            ->from('{{%auth_item}}')
            ->where(['name' => $name])
            ->scalar();

        if (!$authItem) {
            Yii::$app->db->createCommand()->insert('{{%auth_item}}', [
                'name' => $name,
                'type' => $type,
                'description' => $description,
                'created_at' => time(),
                'updated_at' => time(),
            ])->execute();
        }
    }

    protected function addAuthItemChild($parent, $child)
    {
        // Check if the child-parent relationship already exists
        $existingChild = (new Query())
            ->select('parent')
            ->from('{{%auth_item_child}}')
            ->where(['parent' => $parent, 'child' => $child])
            ->scalar();

        if (!$existingChild) {
            Yii::$app->db->createCommand()->insert('{{%auth_item_child}}', [
                'parent' => $parent,
                'child' => $child,
            ])->execute();
        }
    }
}
