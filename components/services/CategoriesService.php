<?php

namespace app\components\services;

use app\models\CreateCategory;
use app\models\Category;
use Yii\web\NotFoundHttpException;
use Yii;

class CategoriesService
{
    /**
     * Returns all categories for a given user.
     *
     * @param int|null $userId The ID of the user to retrieve categories for. If not provided, the current user will be used.
     * @return Category[] An array of categories for the given user.
     */
    public function getAllUserCategories(?int $userId = null): array
    {
        $userId = $userId ?? Yii::$app->user->id;

        return Category::findAll(['user_id' => $userId]);
    }

    /**
     * Retrieves a category by its ID.
     *
     * @param int $categoryId The ID of the category to retrieve.
     * @return Category|null The category object if found, or null if not found.
     */
    public function getCategoryById(int $categoryId): ?Category
    {
        return Category::findOne(['id' => $categoryId]);
    }

    /**
     * Binds a category to a form model based on its ID.
     *
     * @param int $categoryId The ID of the category to bind.
     * @return CreateCategory The form model populated with the category data.
     * @throws NotFoundHttpException If the category is not found.
     */
    public function bindCategoryForm(int $categoryId): CreateCategory
    {
        $category = $this->getCategoryById($categoryId);
        if ($category) {
            $model = new CreateCategory();
            $model->name = $category->name;
            $model->type = $category->type;
            return $model;
        } else {
            throw new NotFoundHttpException('Category not found.');
        }
    }

    /**
     * Creates a new category.
     *
     * @param CreateCategory $model The category data from the user.
     * @return bool Whether the category was successfully saved.
     */
    public function createCategory(CreateCategory $model): bool
    {
        $category = new Category();
        $category->user_id = Yii::$app->user->id;
        $category->name = $model->name;
        $category->type = $model->type;
        $category->created_at = date('Y-m-d H:i:s', time());
        $category->updated_at = date('Y-m-d H:i:s', time());
        return $category->save();
    }

    /**
     * Updates an existing category.
     *
     * @param CreateCategory $categoryModel The updated category data from the user.
     * @return bool Whether the category was successfully updated.
     */
    public function updateCategory($id, CreateCategory $categoryModel): bool
    {
        $category = $this->getCategoryById($id);

        if ($category) {
            $category->name = $categoryModel->name;
            $category->updated_at = date('Y-m-d H:i:s');
        } else {
            throw new NotFoundHttpException('Category not found.');
        }

        return $category->save();
    }

    /**
     * Deletes a category.
     *
     * @param int $categoryId The ID of the category to delete.
     * @return bool Whether the category was successfully deleted.
     */
    public function deleteCategory(int $categoryId): bool
    {
        $category = $this->getCategoryById($categoryId);
        if ($category) {
            return $category->delete();
        } else {
            throw new NotFoundHttpException('Category not found.');
        }
    }
}