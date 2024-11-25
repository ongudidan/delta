<?php

/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/web/css/site.css',
        "/web/otika/assets/css/app.min.css",
        "/web/otika/assets/bundles/datatables/datatables.min.css",
        "/web/otika/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css",
        "/web/otika/assets/css/style.css",
        "/web/otika/assets/css/components.css",
        "/web/otika/assets/css/custom.css",
        "/web/otika/assets/bundles/bootstrap-social/bootstrap-social.css",
        "/web/otika/assets/bundles/summernote/summernote-bs4.css",
        'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css',

        "/web/otika/assets/bundles/bootstrap-daterangepicker/daterangepicker.css",
        "/web/otika/assets/bundles/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css",
        "/web/otika/assets/bundles/select2/dist/css/select2.min.css",
        "/web/otika/assets/bundles/jquery-selectric/selectric.css",
        "/web/otika/assets/bundles/bootstrap-timepicker/css/bootstrap-timepicker.min.css",
        "/web/otika/assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.css",

        "/web/otika/assets/bundles/jqvmap/dist/jqvmap.min.css",
        "/web/otika/assets/bundles/flag-icon-css/css/flag-icon.min.css",

        "/web/otika/assets/bundles/fullcalendar/fullcalendar.min.css"
    ];
    public $js = [
        // "/web/otika/assets/js/app.min.js",
        // "/web/otika/assets/bundles/chartjs/chart.min.js",
        // "/web/otika/assets/bundles/jquery.sparkline.min.js",
        // "/web/otika/assets/bundles/apexcharts/apexcharts.min.js",
        // "/web/otika/assets/bundles/jqvmap/dist/jquery.vmap.min.js",
        // "/web/otika/assets/bundles/jqvmap/dist/maps/jquery.vmap.world.js",
        // "/web/otika/assets/bundles/jqvmap/dist/maps/jquery.vmap.indonesia.js",
        // "/web/otika/assets/js/page/widget-chart.js",

        // "/web/otika/assets/js/page/index.js",
        "/web/script.js",

        "/web/otika/assets/bundles/fullcalendar/fullcalendar.min.js",
        "/web/otika/assets/js/page/calendar.js",

        // "/web/otika/assets/bundles/cleave-js/dist/cleave.min.js",
        "/web/otika/assets/bundles/cleave-js/dist/addons/cleave-phone.us.js",
        "/web/otika/assets/bundles/jquery-pwstrength/jquery.pwstrength.min.js",
        "/web/otika/assets/bundles/bootstrap-daterangepicker/daterangepicker.js",
        // "/web/otika/assets/bundles/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js",/
        "/web/otika/assets/bundles/bootstrap-timepicker/js/bootstrap-timepicker.min.js",
        "/web/otika/assets/bundles/select2/dist/js/select2.full.min.js",
        "/web/otika/assets/bundles/jquery-selectric/jquery.selectric.min.js",
        // "/web/otika/assets/js/page/forms-advanced-forms.js",
        "/web/js/main.js",

        // "/web/otika/assets/bundles/chartjs/chart.min.js",
        // "/web/otika/assets/bundles/jquery.sparkline.min.js",
        // "/web/otika/assets/bundles/apexcharts/apexcharts.min.js",
        // "/web/otika/assets/bundles/jqvmap/dist/jquery.vmap.min.js",
        // "/web/otika/assets/bundles/jqvmap/dist/maps/jquery.vmap.world.js",
        // "/web/otika/assets/bundles/jqvmap/dist/maps/jquery.vmap.indonesia.js",
        // "/web/otika/assets/js/page/widget-chart.js",
        "/web/js/canvasjs.min.js",
        "/web/otika/assets/js/scripts.js",
        "/web/otika/assets/js/custom.js",
        "/web/otika/assets/bundles/datatables/datatables.min.js",
        "/web/otika/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js",
        "/web/otika/assets/js/page/datatables.js",


    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}
