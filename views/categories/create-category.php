<?php

/** @var string $action */
/** @var string $type */

/** @var $category */


use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\enums\CategoryType;

if($action === 'create') {
    $this->title = 'Создать категорию';
}else if ($action === 'update'){
    $this->title = 'Переименовать категорию';
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-form">
    <h1><?= Html::encode($this->title) ?></h1>
<div class="row">
    <div class="col-lg-5">

        <?php $form = ActiveForm::begin(); ?>

        <?=$form->field($category, 'name')->label("Название категории")->textInput(['autofocus' => true]) ?>
        <?=Html::activeHiddenInput($category, 'type', ['value' => $type]);?>
        <?php
        if ($action === 'update') {
            echo Html::activeHiddenInput($category, 'id', ['value' => Yii::$app->request->get('id')]);
        }
        ?>

        <div class="form-group">
            <div>
                <?php
                if ($type === CategoryType::Income->value){
                    if ($action === 'create') {
                        echo Html::submitButton('Создать категорию дохода', ['class' => 'btn btn-primary']);
                    }else if ($action === 'update'){
                        echo Html::submitButton('Переименовать категорию дохода', ['class' => 'btn btn-primary']);
                    }
                }else if ($type === CategoryType::Expense->value){
                    if ($action === 'create') {
                        echo Html::submitButton('Создать категорию расхода', ['class' => 'btn btn-primary']);
                    }else if ($action === 'update'){
                        echo Html::submitButton('Переименовать категорию расхода', ['class' => 'btn btn-primary']);
                    }
                }
                ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
</div>
