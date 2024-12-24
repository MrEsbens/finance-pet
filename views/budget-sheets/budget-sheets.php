<?php

/** @var array $budget_sheets */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Мои листы бюджета';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container mt-5">
    <h1 class="text-center">Листы бюджета</h1>
    <div class="row">
        <?php foreach ($budget_sheets as $sheet): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?=  Html::encode($sheet['name']); ?></h5>
                        <div class="d-flex justify-content-between">
                            <?=Html::a('Переименовать', Url::to(['budget-sheets/update', 'id' => Html::encode($sheet['id']) ]), ['class' => 'btn btn-warning'])?>
                            <?=Html::a('Удалить', Url::to(['budget-sheets/delete', 'id' => Html::encode($sheet['id']) ]), ['class' => 'btn btn-danger'])?>
                        </div>
                    </div>
                </div>
            </div>
            <hr> <!-- Разделительная линия -->
        <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
        <a href="./?r=budget-sheets/create" class="btn btn-primary">Добавить новый лист бюджета</a>
    </div>
</div>