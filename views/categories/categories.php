<?php

/** @var array $categories */

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\enums\CategoryType;

$this->title = 'Мои категории';
$this->params['breadcrumbs'][] = $this->title;
$income =[];
$expense =[];
foreach ($categories as $category) {
    if ($category['type'] == CategoryType::Income->value) {
        $income[] = $category;
    }else{
        $expense[] = $category;
    }
}
?>

<div class="container mt-5">
    <div class="row">
        <h2>Категории доходов</h2>
        <?php foreach ($income as $category): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <?=$category['name']?>
                        <div class="d-flex justify-content-between">
                            <?=Html::a('Переименовать', Url::to(['categories/update', 'id' => Html::encode($category['id']), 'type' => CategoryType::Income->value ]), ['class' => 'btn btn-warning'])?>
                            <?=Html::a('Удалить', Url::to(['categories/delete', 'id' => Html::encode($category['id']) ]), ['class' => 'btn btn-danger'])?>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
        <?=Html::a('Добавить категорию дохода', Url::to(['categories/create', 'type' => CategoryType::Income->value]), ['class' => 'btn btn-primary'])?>
    </div>
    <div class="row">
        <h2>Категории расходов</h2>
        <?php foreach ($expense as $category): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <?=$category['name']?>
                        <div class="d-flex justify-content-between">
                            <?=Html::a('Переименовать', Url::to(['categories/update', 'id' => Html::encode($category['id']), 'type' => CategoryType::Expense->value]), ['class' => 'btn btn-warning'])?>
                            <?=Html::a('Удалить', Url::to(['categories/delete', 'id' => Html::encode($category['id']) ]), ['class' => 'btn btn-danger'])?>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        <?php endforeach; ?>
    </div>
    <div class="text-center mt-4">
        <?=Html::a('Добавить категорию расхода', Url::to(['categories/create', 'type' => CategoryType::Expense->value]), ['class' => 'btn btn-primary'])?>
    </div>
</div>