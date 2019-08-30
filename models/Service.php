<?php


namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Service
 * @package app\models
 * @property int $ID
 * @property int $user_id
 * @property int $tarif_id
 * @property \DateTimeImmutable $payday
 * @property Tarif $tarif
 */
class Service extends ActiveRecord
{

    public static function tableName()
    {
        return 'services';
    }

    public function getTarif()
    {
        return $this->hasOne(Tarif::class, ['ID' => 'tarif_id']);
    }
}