<?php
/**
 * Created by PhpStorm.
 * User: proger
 * Date: 10/22/2018
 * Time: 1:25 PM
 */

/* @var $orders \common\models\Order[] */

use yii\helpers\Html;
use common\models\Location;
use kartik\daterange\DateRangePicker;
use yiister\gentelella\widgets\StatsTile;

$this->title = 'Location Report';

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
            <section class="location-report-section">
                <div class="row">
                    <div class="col-xs-12 col-md-3">
                        <?= StatsTile::widget(
                            [
//                        'icon' => 'list-alt',
                                'header' => 'Total Net',
//                        'text' => 'Average Daily Net Sales',
                                'number' => Yii::$app->formatter->asCurrency($orderTotal - $orderTotalTax),
                            ]
                        ) ?>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?= StatsTile::widget(
                            [
//                        'icon' => 'pie-chart',
                                'header' => 'Total Tax',
//                        'text' => 'Users to orders',
                                'number' => Yii::$app->formatter->asCurrency($orderTotalTax),
                            ]
                        ) ?>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <?= StatsTile::widget(
                            [
//                        'icon' => 'users',
                                'header' => 'Total Gross',
//                        'text' => 'Count of registered users',
                                'number' => Yii::$app->formatter->asCurrency($orderTotal),
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
            </section>
            <section class="location-report-section">
                <div class="row">
                    <div class="col-sm-3">
                        <h3>Location Daily Sales</h3>
                        <div class="location-daily-sales-table"></div>
                    </div>
                    <div class="col-sm-9">
                        <div class="location-daily-sales-chart"></div>
                    </div>
                </div>
            </section>

            <section class="location-report-section">
                <div class="row">
                    <div class="col-sm-3">
                        <h3>Location Payment Methods</h3>
                        <div class="location-daily-sales-table"></div>
                    </div>
                    <div class="col-sm-9">
                        <div class="location-daily-sales-chart"></div>
                    </div>
                </div>
            </section>

            <section class="location-report-section">
                <div class="row">
                    <div class="col-sm-12">
                        <h3>Location Invoices</h3>
                        <div class="location-invoices-table"></div>
                    </div>
                </div>
            </section>
            <section class="location-report-section">
                <div class="row">
                    <div class="col-sm-3">
                        <h3>Location Products Sold</h3>
                        <div class="location-products-sold-table"></div>
                    </div>
                    <div class="col-sm-9">
                        <div class="location-products-sold-chart"></div>
                    </div>
                </div>
            </section>
            <section class="location-report-section">
                <div class="row">
                    <div class="col-sm-3">
                        <h3>Location Employee Sales</h3>
                        <div class="location-employee-sales-table"></div>
                    </div>
                    <div class="col-sm-9">
                        <div class="location-employee-sales-chart"></div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </div>

<?php if ($orders): ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

        google.charts.load('current', {'packages': ['corechart', 'table']});

        google.charts.setOnLoadCallback(drawDaily);
        google.charts.setOnLoadCallback(drawInvoices);
        google.charts.setOnLoadCallback(drawProducts);
        google.charts.setOnLoadCallback(drawEmployee);

        function drawDaily() {

            const dataChart = new google.visualization.DataTable(),
                dataTable = new google.visualization.DataTable();

            dataChart.addColumn('string', 'Date');
            dataChart.addColumn('number', 'Total');

            dataTable.addColumn('date', 'Date');
            dataTable.addColumn('number', 'Sub');
            dataTable.addColumn('number', 'Tax');
            dataTable.addColumn('number', 'Total');

            let $ordersByDays = <?= json_encode($ordersByDays) ?>;
            $.each($ordersByDays, (key, order) => {
                let total = parseFloat(order.total),
                    totalTax = parseFloat(order.total_tax);
                dataChart.addRow([key, total]);
                dataTable.addRow([
                    new Date(key),
                    total - totalTax,
                    totalTax,
                    total,
                ]);
            })

            const formatter = new google.visualization.NumberFormat({prefix: '$'});

            formatter.format(dataChart, 1);

            formatter.format(dataTable, 1);
            formatter.format(dataTable, 1);
            formatter.format(dataTable, 2);

            let options = {
                height: 300,
                vAxis: {
                    format: 'currency'
                }
            };

            const dailyChart = new google.visualization.LineChart(document.querySelector('.location-daily-sales-chart')),
                dailyTable = new google.visualization.Table(document.querySelector('.location-daily-sales-table'));

            dailyChart.draw(dataChart, options);
            dailyTable.draw(dataTable, {width: '100%', height: '250px'});
        }

        function drawInvoices() {

            const dataTable = new google.visualization.DataTable();

            dataTable.addColumn('number', 'Invoice');
            dataTable.addColumn('date', 'Date');
            dataTable.addColumn('number', 'Subtotal');
            dataTable.addColumn('number', 'Tax');
            dataTable.addColumn('number', 'Total');

            let $orders = <?= json_encode(\yii\helpers\ArrayHelper::toArray($orders)) ?>;
            $.each($orders, (key, order) => {
                let total = parseFloat(order.total),
                    totalTax = parseFloat(order.total_tax);
                dataTable.addRow([
                    parseInt(order.id),
                    new Date(order.created_at),
                    total - totalTax,
                    totalTax,
                    total,
                ])
            })

            const formatter = new google.visualization.NumberFormat({prefix: '$'});
            formatter.format(dataTable, 2);
            formatter.format(dataTable, 3);
            formatter.format(dataTable, 4);

            const invoicesTable = new google.visualization.Table(document.querySelector('.location-invoices-table'));

            invoicesTable.draw(dataTable, {width: '100%', height: '250px'});
        }

        function drawProducts() {

            const data = new google.visualization.DataTable();

            data.addColumn('string', 'Product Name');
            data.addColumn('number', 'Quantity');
            data.addColumn('number', 'Average net price');

            let $ordersProducts = <?= json_encode($ordersProducts) ?>;
            $.each($ordersProducts, (key, $orderProduct) => {
                let total = parseFloat($orderProduct.total),
                    quantity = parseInt($orderProduct.quantity);
                data.addRow([
                    $orderProduct.name,
                    quantity,
                    total / quantity,
                ])
            })

            const formatter = new google.visualization.NumberFormat({prefix: '$'});

            formatter.format(data, 2);

            let options = {
                height: 300,
                series: {
                    0: {targetAxisIndex: 0},
                    1: {targetAxisIndex: 1, format: 'currency'}
                },
                focusTarget: 'category',
            };

            const chart = new google.visualization.ColumnChart(document.querySelector('.location-products-sold-chart')),
                table = new google.visualization.Table(document.querySelector('.location-products-sold-table'));

            chart.draw(data, options);
            table.draw(data, {width: '100%', height: '250px'});
        }

        function drawEmployee() {

            const data = new google.visualization.DataTable();

            data.addColumn('string', 'Employee Name');
            data.addColumn('number', 'Total net sales');

            let $ordersEmployees = <?= json_encode($ordersEmployees) ?>;
            $.each($ordersEmployees, (key, orderEmployee) => {
                let total = parseFloat(orderEmployee.total);
                data.addRow([
                    orderEmployee.name,
                    total,
                ])
            })

            const formatter = new google.visualization.NumberFormat({prefix: '$'});

            formatter.format(data, 1);

            let options = {
                height: 300,
                vAxis: {
                    format: 'currency'
                }
            };

            const view = new google.visualization.DataView(data);
            view.setColumns([0, 1]);

            const chart = new google.visualization.ColumnChart(document.querySelector('.location-employee-sales-chart')),
                table = new google.visualization.Table(document.querySelector('.location-employee-sales-table'));

            chart.draw(view, options);
            table.draw(view, {width: '100%', height: '250px'});

            google.visualization.events.addListener(table, 'sort',
                function (event) {
                    data.sort([{column: event.column, desc: !event.ascending}]);
                    chart.draw(view);
                });
        }

    </script>
<?php endif; ?>