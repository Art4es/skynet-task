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

    const SCENARIO_SHORT_VIEW = 'short_view';
    const SCENARIO_EXTENDED_VIEW = 'extended_view';

    public static function tableName()
    {
        return 'tarifs';
    }

    public function fields()
    {
        switch ($this->scenario) {
            case self::SCENARIO_SHORT_VIEW:
                return [
                    'title' => $this->format('title'),
                    'link' => $this->format('link'),
                    'speed' => $this->format('speed'),
                ];
            case self::SCENARIO_EXTENDED_VIEW:
                return [
                    'ID' => $this->format('ID'),
                    'title' => $this->format('title'),
                    'price' => $this->format('price'),
                    'speed' => $this->format('speed'),
                    'pay_period' => $this->format('pay_period'),
                    'new_payday' => $this->format('new_payday'),
                ];
            default:
                return parent::fields();
        }
    }

    private function formats()
    {
        return [
            'ID' => function () {
                return intval($this->ID);
            },
            'title' => function () {
                return strval($this->title);
            },
            'price' => function () {
                return floatval($this->price);
            },
            'link' => function () {
                return strval($this->link);
            },
            'speed' => function () {
                return intval($this->speed);
            },
            'pay_period' => function () {
                return intval($this->pay_period);
            },
            'tarif_group_id' => function () {
                return intval($this->tarif_group_id);
            },
            'new_payday' => function () {
                $new_date = $this->getNewPayday();
                return $new_date->getTimestamp() . $new_date->format('O');
            }
        ];
    }

    private function format($property_name)
    {
        return $this->formats()[$property_name];
    }

    /**
     * @return \DateTimeImmutable
     * @throws \Exception
     */
    public function getNewPayday()
    {
        $current_date = new \DateTimeImmutable();
        $current_midnight = $current_date->setTime(0, 0, 0, 0);
        return $current_midnight->add(new \DateInterval("P{$this->pay_period}M"));
    }
}