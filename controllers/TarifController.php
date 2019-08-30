<?php


namespace app\controllers;


use app\models\Service;
use app\models\Tarif;
use yii\web\BadRequestHttpException;
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
        if (empty($request->post())) {
            throw new BadRequestHttpException("Empty data");
        }
        $request_body = json_decode($request->post());
        $tarif_id = $request_body->tarif_id;
        if (empty($tarif_id)) {
            throw new BadRequestHttpException("Tarif_id is empty");
        }
        $tarif = Tarif::findOne(['ID' => $tarif_id]);
        if (is_null($tarif)) {
            throw new BadRequestHttpException("Tarif does not exist");
        }
        $services = Service::find()
            ->with('tarif')
            ->where(['user_id' => $user_id, 'ID' => $service_id])
            ->all();
        if (empty($services)) {
            throw new BadRequestHttpException("Service does not exist");
        }
        foreach ($services as $service) {
            $service->link('tarif', $tarif);
            $service->payday = $tarif->getNewPayday()->format('Y-m-d');
            $service->save();
        }
    }
}