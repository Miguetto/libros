<?php

namespace app\controllers;

use app\models\Autores;
use app\models\Libros;
use app\models\LibrosSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class LibrosController extends Controller
{
    public function actionCreate()
    {
        $libro = new Libros();

        if ($libro->load(Yii::$app->request->post()) && $libro->save()) {
            return $this->redirect(['libros/index']);
        }

        return $this->render('create', [
            'libro' => $libro,
            'listaAutores' => $this->listaAutores(),
        ]);
    }

    public function actionUpdate($id)
    {
        $libro = $this->findLibro($id);

        if ($libro->load(Yii::$app->request->post()) && $libro->save()) {
            return $this->redirect(['libros/index']);
        }

        return $this->render('update', [
            'libro' => $libro,
            'listaAutores' => $this->listaAutores(),
        ]);
    }

    public function actionIndex()
    {
        $filterModel = new LibrosSearch();
        $dataProvider = $filterModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'filterModel' => $filterModel,
        ]);
    }

    public function actionView($id)
    {
        $libro = $this->findLibro($id);

        return $this->render('view', [
            'libro' => $libro,
        ]);
    }

    public function actionDelete($id)
    {
        if ($this->findLibro($id)->delete()) {
            Yii::$app->session->setFlash('success', 'El libro se ha borrado correctamente.');
        }
    }
    
    private function findLibro($id)
    {
        $autor = Libros::findOne($id);

        if ($autor === null) {
            throw new NotFoundHttpException('Ese libro no existe.');
        }

        return $autor;
    }

    private function listaAutores()
    {
        return Autores::find()->select('nombre')->indexBy('id')->column();
    }

}
