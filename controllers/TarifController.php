<?php


namespace app\controllers;


use app\models\Service;
use app\models\Tarif;
use yii\web\Controller;

class TarifController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionTarifs($user_id, $service_id)
    {
        $services = Service::find()
            ->with('tarif')
            ->where(['user_id' => $user_id, 'ID' => $service_id])
            ->all();
        $result = [];
        foreach ($services as $service) {
            $tarif = $service->tarif;
            $result[] = [
                'title' => $tarif->title,
                'link' => $tarif->link,
                'speed' => $tarif->speed,
                'tarifs' => Tarif::findAll(['tarif_group_id' => $tarif->tarif_group_id])
            ];
        }
        return ['tarifs' => $result];
    }

    public function actionTarif($user_id, $service_id)
    {
        $request = \Yii::$app->request;
        if ($request->isPut) {
            return $request->post();
        }
    }
}