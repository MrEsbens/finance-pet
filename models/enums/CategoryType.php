<?php
namespace app\models\enums;

enum CategoryType: string{
    case Income = 'income';
    case Expense = 'expense';
}