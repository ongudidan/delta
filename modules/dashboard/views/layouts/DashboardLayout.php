<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\DashboardAsset;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;


DashboardAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('/web/img/logo-small.png')]);

$headerTitle = '';

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&display=swap"
        rel="stylesheet">
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>

<body class="small-text">
    <?php $this->beginBody() ?>

    <div class="main-wrapper">
        <?php \yii\widgets\Pjax::begin(['id' => 'pjax-container1']); ?>

        <?= $this->render('components/_header') ?>
        <?= $this->render('components/_sidebar') ?>


        <main id="main" class="flex-shrink-0" role="main">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <?= $this->render('components/_page-header') ?>

                    <div class="row">
                        <div class="col-sm-12">

                            <!-- Full-Screen Spinner Overlay -->
                            <div id="loading-overlay">
                                <div class="sk-chase">
                                    <div class="sk-chase-dot"></div>
                                    <div class="sk-chase-dot"></div>
                                    <div class="sk-chase-dot"></div>
                                    <div class="sk-chase-dot"></div>
                                    <div class="sk-chase-dot"></div>
                                    <div class="sk-chase-dot"></div>
                                </div>
                            </div>


                            <?= $content ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php \yii\widgets\Pjax::end(); ?>


    </div>

    <?php $this->endBody() ?>

    <?php
    // Display flash messages as Toastr notifications if any are set
    foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
        // Set toastr type based on the session flash key
        $type = 'info'; // Default type

        switch ($key) {
            case 'success':
                $type = 'success';
                break;
            case 'error':
                $type = 'error';
                break;
            case 'warning':
                $type = 'warning';
                break;
            case 'info':
                $type = 'info';
                break;
        }

        // Output the toastr notification using the session message
        $this->registerJs("
              toastr.options = {
            'closeButton': true,  // Enable the close button
            'debug': false,
            'newestOnTop': true,
            'progressBar': true,  // Show progress bar
            'preventDuplicates': true,
            'showDuration': '300',
            'hideDuration': '1000',
            'timeOut': '5000',  // Timeout duration in ms
            'extendedTimeOut': '1000',
            'showEasing': 'swing',
            'hideEasing': 'linear',
            'showMethod': 'fadeIn',
            'hideMethod': 'fadeOut'
        };
        toastr.$type('$message');
    ");
    }
    ?>


    <?php
//     $this->registerJs(<<<JS
//     $(document).on('pjax:send', function() {
//         $('#loading-overlay').addClass('show'); // Show the spinner overlay
//         $('#pjax-container').css('opacity', '0.5'); // Optional fade effect
//     });

//     $(document).on('pjax:complete', function() {
//         $('#loading-overlay').removeClass('show'); // Hide the spinner overlay
//         $('#pjax-container').css('opacity', '1'); // Restore opacity
//     });
    
//     // Optional fade effect for other containers
//     $(document).on('pjax:send', function() {
//         $('#pjax-container1').css('opacity', '0.5'); // Optional fade effect
//     });

//     $(document).on('pjax:complete', function() {
//         $('#pjax-container1').css('opacity', '1'); // Restore opacity
//     });
// JS);
    ?>



    <?php

    // Check if 'modalSize' is set in params; default to Modal::SIZE_LARGE if not set
    $modalSize = isset($this->params['modalSize']) ? $this->params['modalSize'] : Modal::SIZE_LARGE;

    // Add custom check for 'extra-large' modal size
    if ($modalSize == 'modal-xl') {
        $modalSize = 'modal-xl'; // Use custom extra-large size if set in params
    } else {
        $modalSize = Modal::SIZE_LARGE; // Default to large size
    }

    Modal::begin([
        'title' => '<span id="modal-title">Modal</span>',
        'id' => 'custom-modal',
        'size' => $modalSize, // Use the size from params or default size
    ]);

    echo '<div id="modal-content"></div>';

    Modal::end();
    ?>


</body>

</html>
<?php $this->endPage() ?>