<?php

use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\models\enums\CategoryType;

/* @var $this yii\web\View */
/* @var $date */
/* @var $transactions array */
/* @var $sheet_id */

$this->title = "Транзакции за ".date('d-m-Y',strtotime($date));
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container mt-5">
    <h1 class="text-center"><?= Html::encode($this->title)?></h1>

    <div class="d-flex justify-content-end mb-4">
        <?= Html::a('Добавить доход/расход', Url::to(['transaction/create', 'date' => $date, 'sheet_id' => $sheet_id]), ['class' => 'btn btn-primary'])?>
    </div>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $transactions,
                'pagination' => false,
            ]),
            'columns' => [
                [
                    'label' => 'Дата',
                    'value' => function($model) {
                        return date('d-m-Y', strtotime($model['data']['transaction_date']));
                    },
                ],
                [
                    'label' => 'Сумма',
                    'value' => function($model) {
                        return number_format($model['data']['amount'], 2, ',', ' ') . ' руб.';
                    },
                ],
                [
                    'label' => 'Тип',
                    'value' => function($model) {
                        return $model['category']['type'] === CategoryType::Income->value ? 'Доход' : 'Расход';
                    },
                ],
                [
                    'label' => 'Категория',
                    'value' => function($model) {
                        return Html::encode($model['category']['name']);
                    }
                ],
                [
                    'label' => 'Описание',
                    'value' => function($model) {
                        return Html::encode($model['data']['description']);
                    },
                ],
                [
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::a('&#9999', Url::to(['transaction/update', 'id' => $model['data']['id'], 'date' => $model['data']['transaction_date'], 'sheet_id' => $model['data']['sheet_id']]));
                    },
                ],
                [
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::a('&#10060', Url::to(['transaction/delete', 'id' => $model['data']['id'], 'date' => $model['data']['transaction_date'] ]));
                    }
                ]
            ],
            'showHeader' => true,

            'emptyText' => '<div class="alert alert-info text-center">Нет транзакций за этот день.</div>',
        ]);?>
    </div>

<?php
$totalIncome = 0;
$totalExpense = 0;

foreach($transactions as $transaction) {
    if($transaction['category']['type'] === CategoryType::Income->value) {
        $totalIncome += (float)$transaction['data']['amount'];
    } else if($transaction['category']['type'] === CategoryType::Expense->value) {
        $totalExpense += (float)$transaction['data']['amount'];
    }
}?>

    <div class="row mt-4">
        <div class="col-lg-5 offset-lg-7">
            <h4>Итоговые суммы</h4>
            <p><strong>Общий доход:</strong> <?= number_format($totalIncome, 2, ',', ' ') ?> руб.</p>
            <p><strong>Общий расход:</strong> <?= number_format($totalExpense, 2, ',', ' ') ?> руб.</p>
            <p><strong>Остаток:</strong> <?= number_format($totalIncome - $totalExpense, 2, ',', ' ') ?> руб.</p>
        </div>
    </div>
</div>

<style>
    .table-responsive {
        margin-top: 20px;
    }
</style>
