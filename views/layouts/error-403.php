<?php

use yii\helpers\Url;
?>
<section class="section">
    <div class="container mt-5">
        <div class="page-error">
            <div class="page-inner">
                <h1>403</h1>
                <div class="page-description">
                    You do not have access to this page.
                </div>
                <div class="page-search">
                    <div class="mt-3">
                        <a href="<?= Url::to('/') ?>">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>