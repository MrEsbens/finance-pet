<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\enums\CategoryType;
use app\models\enums\RecurringExpencePeriod;

/** @var  $model */
/** @var  $categories */

$category_types_enum = CategoryType::cases();
$category_types = array();
$categories_rus = [
    'income' => 'Доход',
    'expense' => 'Расход'
];

foreach ($category_types_enum as $category_type) {
    $category_types[$category_type->value] = $categories_rus[$category_type->value];
}

$incomes = [];
$expenses = [];
foreach ($categories as $category) {
    if ($category['type'] == CategoryType::Income->value) {
        $incomes[$category['id']] = $category['name'];
    } else if ($category['type'] == CategoryType::Expense->value) {
        $expenses[$category['id']] = $category['name'];
    }
}

$incomesJson = json_encode($incomes);
$expensesJson = json_encode($expenses);

$period_types_enum = RecurringExpencePeriod::cases();
$period_types = array();
$period_rus = [
    'daily' => 'Ежедневно',
    'weekly' => 'Еженедельно',
    'monthly' => 'Ежемесячно',
    'yearly' => 'Ежегодно'
];

foreach ($period_types_enum as $period_type) {
    $period_types[$period_type->value] = $period_rus[$period_type->value];
}

$this->title = 'Создать периодическую транзакцию';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="create-model">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'amount')->label("Сумма")->textInput(['type' => 'number', 'step' => '0.01']); ?>
            <?= $form->field($model, 'description')->label('Дополнительное описание')->textInput(); ?>
            <?= $form->field($model, 'transaction_date')->label('Дата первой транзакции')->input('date'); ?> 
            <?= $form->field($model, 'category_id')->label('Тип')->dropDownList($category_types, ['prompt' => 'Выберите тип транзакции', 'id' => 'category-type-dropdown']); ?>
            <?= $form->field($model, 'category_id')->label('Категория')->dropDownList([], ['prompt' => 'Выберите категорию', 'id' => 'dynamic-category-dropdown']); ?>
            <?= $form->field($model, 'period')->label('Период')->dropDownList($period_types, ['prompt' => 'Выберите период']); ?>
            <?= $form->field($model, 'quantity')->label('Количество транзакций')->textInput(['type' => 'number', 'min' => 0]); ?>
            <?= Html::activeHiddenInput($model, 'sheet_id', ['value' => $sheet_id]); ?>

            <div class="form-group">
                <div>
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
$script = <<< JS
$(document).ready(function() {
    let incomes = $incomesJson;
    let expenses = $expensesJson;

    $('#category-type-dropdown').change(function() {
        let selectedType = $(this).val();
        let categoryDropdown = $('#dynamic-category-dropdown');

        // Очистка второго выпадающего списка
        categoryDropdown.empty();
        
        // Заполнение второго выпадающего списка в зависимости от выбранного типа
        if (selectedType === 'income') {
            $.each(incomes, function(id, name) {
                categoryDropdown.append(new Option(name, id));
            });
        } else if (selectedType === 'expense') {
            $.each(expenses, function(id, name) {
                categoryDropdown.append(new Option(name, id));
            });
        }
        // Добавить пустой вариант
        categoryDropdown.prepend(new Option('Выберите категорию', '', true, true));
    });
});
JS;

$this->registerJs($script);
?>
