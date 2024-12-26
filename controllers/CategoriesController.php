<?php

namespace app\controllers;

use app\models\CreateCategory;
use yii\web\Controller;
use app\models\Category;
use Yii;
use yii\web\NotFoundHttpException;

class CategoriesController extends Controller{
    public function actionShow(){
        $categories = Category::find()->where(['user_id' => Yii::$app->user->id])->all();
        return $this->render('categories', ['categories' => $categories]);
    }
    public function actionCreate(){
        $model = new CreateCategory();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $category = new Category();
            $category->user_id = Yii::$app->user->id;
            $category->name = $model->name;
            $category->type = $model->type;
            $category->created_at = date('Y-m-d H:i:s', time());
            $category->updated_at = date('Y-m-d H:i:s', time());
            if ($category->save()) {
                $this->redirect(['categories/show']);
            }
        }
        return $this->render('create-category', ['category' => $model, 'action' => 'create', 'type' => Yii::$app->request->get('type')]);
    }
    public function actionUpdate(){
        $model = new CreateCategory();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $category = Category::findOne(Yii::$app->request->get('id'));
            if($category){
                $category->name = $model->name;
                $category->updated_at = date('Y-m-d H:i:s', time());
                if ($category->save()) {
                    $this->redirect(['categories/show']);
                }
            }else {
                throw new NotFoundHttpException('Category not found');
            }
        }
        return $this->render('create-category', ['category' => $model, 'action' => 'update', 'type' => Yii::$app->request->get('type')]);
    }
    public function actionDelete(){
        $category = Category::findOne(Yii::$app->request->get('id'));
        if($category){
            $category->delete();
            $this->redirect(['categories/show']);
        }else{
            throw new NotFoundHttpException('Category not found');
        }
    }
}