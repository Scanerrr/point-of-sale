<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 10/22/2018
 * Time: 1:25 PM
 */

use yii\helpers\Html;
use common\models\Location;
use kartik\daterange\DateRangePicker;
use yiister\gentelella\widgets\StatsTile;

$this->title = 'Location Report';


$orderTotal = $orderTotalTax = 0;
if ($orders) {
    foreach ($orders as $order) { // TODO: get rid of this foreach
        $orderTotal += $order->total;
        $orderTotalTax += $order->total_tax;
    }
}
?>

    <div class="location-reports">
        <h1><?= Html::encode($this->title) ?></h1>

        <?= Html::beginForm(['/location/report'], 'post', ['class' => 'filter-form']) ?>

        <div class="location-select">
            <label>Please select a location:</label>
            <?= Html::dropDownList('location', $location, Location::find()
                ->select('name')
                ->orderBy('name')
                ->indexBy('id')
                ->column(), ['class' => 'form-control']) ?>
        </div>

        <div class="range-select">
            <label>Choose date</label>

            <?= DateRangePicker::widget([
                'name' => 'range',
                'value' => $startDate . ' - ' . $endDate,
                'convertFormat' => true,
                'startAttribute' => 'from',
                'endAttribute' => 'to',
                'pluginOptions' => [
                    'locale' => ['format' => $dateFormat],
                    'maxDate' => $endDate,

                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ]
            ]) ?>
        </div>

        <?= Html::submitButton('Search', ['class' => 'btn btn-sm btn-default']) ?>

        <?php Html::endForm() ?>

        <?php if ($orders): ?>
            <div class="location-stats">
                <div class="row">
                    <div class="col-xs-12 col-md-3">
                        <?= StatsTile::widget(
                            [
//                        'icon' => 'list-alt',
                                'header' => 'Total Net',
//                        'text' => 'Average Daily Net Sales',
                                'number' => $orderTotal - $orderTotalTax,
                            ]
                        ) ?>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?= StatsTile::widget(
                            [
//                        'icon' => 'pie-chart',
                                'header' => 'Total Tax',
//                        'text' => 'Users to orders',
                                'number' => $orderTotalTax,
                            ]
                        ) ?>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?= StatsTile::widget(
                            [
//                        'icon' => 'users',
                                'header' => 'Total Gross',
//                        'text' => 'Count of registered users',
                                'number' => $orderTotal,
                            ]
                        ) ?>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?= StatsTile::widget(
                            [
//                        'icon' => 'comments-o',
                                'header' => 'Total Net Profit',
//                        'text' => 'The next reviews are not approved',
                                'number' => '31',
                            ]
                        ) ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3">
                    <h3>Location Daily Sales</h3>
                    <div class="location-daily-sales"></div>
                </div>
                <div class="col-sm-9">
                    <div class="location-chart"></div>
                </div>
            </div>

<!--        <div class="row">-->
<!--            <div class="col-sm-12">-->
<!--                <h3>Location Payment Methods</h3>-->
<!-- TODO-->
<!--            </div>-->
<!--        </div>-->

        <div class="row">
            <div class="col-sm-12">
                <h3>Location Invoices</h3>
                <div class="location-invoices"></div>
            </div>
        </div>
        <?php endif; ?>
    </div>

<?php if ($orders): ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

        google.charts.load('current', {'packages': ['corechart', 'table']});

        google.charts.setOnLoadCallback(draw);

        function draw() {

            let dataChart = new google.visualization.DataTable();
            let dataTable = new google.visualization.DataTable();

            dataChart.addColumn('string', 'Date');
            dataChart.addColumn('number', 'Total');

            dataTable.addColumn('number', 'Invoice');
            dataTable.addColumn('date', 'Date');
            dataTable.addColumn('number', 'Subtotal');
            dataTable.addColumn('number', 'Tax');
            dataTable.addColumn('number', 'Total');

            <?php foreach ($orders as $order): ?>
            dataChart.addRow([
                {
                    v: '<?= $order->created_at ?>',
                    f: '<?= date('M d, Y', strtotime($order->created_at)) ?>'
                },
                {
                    v: <?= $order->total ?>,
                    f: '<?= Yii::$app->formatter->asCurrency($order->total) ?>'
                }
            ]);

            dataTable.addRow([
                <?= $order->id ?>,

                new Date('<?= $order->created_at ?>'),
                {
                    v: <?= $order->total - $order->total_tax ?>,
                    f: '<?= Yii::$app->formatter->asCurrency($order->total - $order->total_tax) ?>'
                },
                {
                    v: <?= $order->total_tax ?>,
                    f: '<?= Yii::$app->formatter->asCurrency($order->total_tax) ?>'
                },
                {
                    v: <?= $order->total ?>,
                    f: '<?= Yii::$app->formatter->asCurrency($order->total) ?>'
                }
            ]);
            <?php endforeach; ?>

            let options = {
                height: 300,
                vAxis: {
                    format: 'currency'
                },
                theme: 'material'
            };

            let chart = new google.visualization.LineChart(document.querySelector('.location-chart')),
                table = new google.visualization.Table(document.querySelector('.location-invoices'));

            chart.draw(dataChart, options);
            table.draw(dataTable, {width: '100%', height: '100%'});
        }
    </script>
<?php endif; ?>