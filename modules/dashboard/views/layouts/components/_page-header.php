<?php

use yii\widgets\Breadcrumbs;
?>
<div class="page-header">
    <div class="row">
        <div class="col">
            <h3 class="page-title"><?= $this->title?></h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'itemTemplate' => "<li class=\"breadcrumb-item\">{link}</li>\n", // Template for links
                'activeItemTemplate' => "<li class=\"breadcrumb-item active\">{link}</li>", // Template for the active item
            ]) ?>
        </div>
    </div>
</div>