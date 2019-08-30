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
            $result[] = $this->prepareTarifBlock($service);
        }
        return ['tarifs' => $result];
    }

    private function prepareTarifBlock(Service $service)
    {
        $tarif = $service->tarif;
        $tarif->scenario = Tarif::SCENARIO_SHORT_VIEW;
        $same_group_tarifs = Tarif::findAll(['tarif_group_id' => $tarif->tarif_group_id]);
        array_walk($same_group_tarifs, function (Tarif $tarif) {
            $tarif->scenario = Tarif::SCENARIO_EXTENDED_VIEW;
        });
        $block = $tarif->toArray();
        return array_merge($block, ['tarifs' => $same_group_tarifs]);
    }

    public function actionTarif($user_id, $service_id)
    {
        $request = \Yii::$app->request;
        if ($request->isPut) {
            return $request->post();
        }
    }
}