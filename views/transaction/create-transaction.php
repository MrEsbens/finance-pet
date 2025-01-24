<?php


use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\enums\CategoryType;

/** @var  $action */
/** @var  $transactionForm */
/** @var  $date */
/** @var  $sheetId */
/** @var  $categories */


$category_types_enum = CategoryType::cases();
$category_types = array();
$categories_rus =[
        'income' => 'Доход',
        'expense' => 'Расход'
];
foreach($category_types_enum as $category_type) {
    $category_types[$category_type->value] = $categories_rus[$category_type->value];
}

$incomes = [];
$expenses = [];
foreach($categories as $category) {
    if($category['type'] == CategoryType::Income->value) {
        $incomes[$category['id']] = $category['name'];
    } else if($category['type'] == CategoryType::Expense->value) {
        $expenses[$category['id']] = $category['name'];
    }
}

$incomesJson = json_encode($incomes);
$expensesJson = json_encode($expenses);

if($action === 'create') {
    $this->title = 'Создать транзакцию';
} else if($action === 'update') {
    $this->title = 'Изменить транзакцию';
}?>

<div class="create-transaction">
    <h1><?= Html::encode($this->title)?></h1>
<div class="row">
    <div class="col-lg-5">

        <?php $form = ActiveForm::begin();?>

        <?=$form->field($transactionForm, 'amount')->label("Сумма")->textInput(['type' => 'number', 'step' => '0.01']);?>
        <?=$form->field($transactionForm, 'description')->label('Дополнительное описание')->textInput();?>
        <?= Html::label('Тип', 'category-type-dropdown') ?>
        <?= Html::dropDownList('category_type', null, $category_types, [
            'prompt' => 'Выберите тип транзакции',
            'id' => 'category-type-dropdown',
            'class' => 'form-control',
            'name' => '',
        ]) ?>        
        <?=$form->field($transactionForm, 'category_id')->label('Категория')->dropDownList([], ['prompt' => 'Выберите категорию', 'id' => 'dynamic-category-dropdown']);?>
        <?=Html::activeHiddenInput($transactionForm, 'transaction_date', ['value' => $date]);?>
        <?=Html::activeHiddenInput($transactionForm, 'sheet_id', ['value' => $sheetId]);?>

        <div class="form-group">
            <div>
                <?=Html::submitButton('Сохранить', ['class' => 'btn btn-primary']);?>
            </div>
        </div>
        <?php ActiveForm::end();?>
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