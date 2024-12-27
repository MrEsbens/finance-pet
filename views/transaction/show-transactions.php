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
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <div class="d-flex justify-content-end mb-4">
        <?= Html::a('Добавить доход/расход', Url::to(['transaction/create', 'date' => $date, 'sheet_id' => $sheet_id]), ['class' => 'btn btn-primary']) ?>
    </div>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $transactions,
                'pagination' => false, // Отключаем пагинацию, так как это один день
            ]),
            'columns' => [
                [
                    'label' => 'Дата',
                    'value' => function($model) {
                        return date('d-m-Y', strtotime($model['data']['transaction_date'])); // Замените 'date_column' на имя вашего столбца с датой
                    },
                ],
                [
                    'label' => 'Сумма',
                    'value' => function($model) {
                        return number_format($model['data']['amount'], 2, ',', ' ') . ' руб.'; // Замените 'amount' на имя вашего поля с суммой
                    },
                ],
                [
                    'label' => 'Тип',
                    'value' => function($model) {
                        return $model['category']['type'] === CategoryType::Income->value ? 'Доход' : 'Расход'; // Замените на вашу логику определения типа
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
                        return Html::encode($model['data']['description']); // Замените на имя вашего поля с описанием
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
            // Сообщение при отсутствии данных
            'emptyText' => '<div class="alert alert-info text-center">Нет транзакций за этот день.</div>',
        ]); ?>
    </div>
</div>

<style>
    .table-responsive {
        margin-top: 20px;
    }
</style>
