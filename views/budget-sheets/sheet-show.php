<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $budget_sheet */

$this->title = $budget_sheet->name;
$this->params['breadcrumbs'][] = $this->title;

// Получаем текущий месяц и год
$currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('m');
$currentYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Определяем предыдущий и следующий месяц
$prevMonth = $currentMonth == 1 ? 12 : $currentMonth - 1;
$prevYear = $currentMonth == 1 ? $currentYear - 1 : $currentYear;

$nextMonth = $currentMonth == 12 ? 1 : $currentMonth + 1;
$nextYear = $currentMonth == 12 ? $currentYear + 1 : $currentYear;

// Массив с названиями месяцев на русском языке
$monthsRu = [
    '01' => 'Январь',
    '02' => 'Февраль',
    '03' => 'Март',
    '04' => 'Апрель',
    '05' => 'Май',
    '06' => 'Июнь',
    '07' => 'Июль',
    '08' => 'Август',
    '09' => 'Сентябрь',
    '10' => 'Октябрь',
    '11' => 'Ноябрь',
    '12' => 'Декабрь',
];

// Массив для хранения расходов и доходов по дням
$expenses = []; // Здесь должны быть ваши данные о расходах
$incomes = [];  // Здесь должны быть ваши данные о доходах

// Получаем количество дней в текущем месяце
$daysInMonth = date('t', mktime(0, 0, 0, $currentMonth, 1, $currentYear));

// Пример данных (замените на ваши реальные данные)
for ($day = 1; $day <= $daysInMonth; $day++) {
    $expenses[$day] = rand(0, 100); // Случайные расходы для примера
    $incomes[$day] = rand(0, 100);   // Случайные доходы для примера
}
?>

<div class="container mt-5">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <div class="d-flex justify-content-start mb-4">
        <?= Html::a('Категории', ['categories/show'], ['class' => 'btn btn-outline-info me-2']) ?>
    </div>

    <div class="d-flex justify-content-between mb-4">
        <?= Html::a('Предыдущий месяц', ['id' => $budget_sheet->id,'budget-sheets/show', 'month' => sprintf('%02d', $prevMonth), 'year' => $prevYear], ['class' => 'btn btn-secondary']) ?>
        <h2 class="text-center"><?= Html::encode($monthsRu[sprintf('%02d', $currentMonth)]) . " " . $currentYear ?></h2>
        <?= Html::a('Следующий месяц', ['id' => $budget_sheet->id, 'budget-sheets/show', 'month' => sprintf('%02d', $nextMonth), 'year' => $nextYear], ['class' => 'btn btn-secondary']) ?>
    </div>

    <div class="calendar">
        <div class="row">
            <?php
            // Отображаем дни текущего месяца
            for ($day = 1; $day <= $daysInMonth; $day++): ?>
                <div class="col-md-2 text-center border p-3">
                    <?=Html::a($day, Url::to(['transaction/show', 'day' => $day, 'month' => $currentMonth, 'year' => $currentYear, 'sheet_id' => $budget_sheet->id]), ['class' => 'h5']) ?>
                    <p>Расходы: <?= Html::encode($expenses[$day]) ?> руб.</p>
                    <p>Доходы: <?= Html::encode($incomes[$day]) ?> руб.</p>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<style>
    .calendar {
        margin-top: 20px;
    }
    .border {
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
</style>