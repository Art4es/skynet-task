<?php


namespace app\models;


use yii\db\ActiveRecord;

/**
 * Class Tarif
 * @package app\models
 * @property int $ID
 * @property string $title
 * @property double $price
 * @property string $link
 * @property int $speed
 * @property int $pay_period
 * @property int $tarif_group_id
 */
class Tarif extends ActiveRecord
{

    public static function tableName()
    {
        return 'tarifs';
    }
}