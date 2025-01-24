<?php

namespace app\controllers;

use app\models\CreateCategory;
use yii\web\Controller;
use Yii;
use Yii\db\Exception;
use app\components\services\CategoriesService;
use app\components\services\UserService;

class CategoriesController extends Controller
{
    private CategoriesService $categoriesService;
    private UserService $userService;
    
    public function __construct(
        string $id,
        $module,
        CategoriesService $categoriesService,
        UserService $userService,
        array $config = []
    ) {
        $this->categoriesService = $categoriesService;
        $this->userService = $userService;
        parent::__construct($id, $module, $config);
    }
    public function actionShow()
    {
        if($this->userService->isGuest()) {
            return $this->goHome();
        }

        $userCategories = $this->categoriesService->getAllUserCategories();

        return $this->render('categories', [
            'categories' => $userCategories,
        ]);
    }
    public function actionCreate()
    {
        $createCategoryModel = new CreateCategory();

        if ($createCategoryModel->load(Yii::$app->request->post()) && $createCategoryModel->validate()) {
            if ($this->categoriesService->createCategory($createCategoryModel)) {
                return $this->redirect(['categories/show']);
            }
        }

        return $this->render('create-category', [
            'model' => $createCategoryModel,
            'action' => 'create',
            'type' => Yii::$app->request->get('type'),
        ]);
    }
    public function actionUpdate()
    {
        $categoryId = Yii::$app->request->get('id');
        $categoryModel = $this->categoriesService->bindCategoryForm($categoryId);

        if ($categoryModel->load(Yii::$app->request->post()) && $categoryModel->validate()) {
            if ($this->categoriesService->updateCategory($categoryId, $categoryModel)) {
                return $this->redirect(['categories/show']);
            } else {
                throw new Exception('Failed to update category.');
            }
        }

        return $this->render('create-category', [
            'model' => $categoryModel,
            'action' => 'update',
            'type' => Yii::$app->request->get('type')
        ]);
    }
    public function actionDelete()
    {
        $categoryId = (int)Yii::$app->request->get('id');

        if ($this->categoriesService->deleteCategory($categoryId)) {
            $this->redirect(['categories/show']);
        } else {
            throw new \yii\db\Exception('Failed to delete category.');
        }
    }
}