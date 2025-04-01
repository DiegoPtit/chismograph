<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pay_historic".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $pago_fecha
 * @property float|null $monto
 * @property string|null $metodo
 *
 * @property Usuarios $user
 */
class PayHistoric extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pay_historic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['metodo'], 'default', 'value' => null],
            [['monto'], 'default', 'value' => 0.00],
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['pago_fecha'], 'safe'],
            [['monto'], 'number'],
            [['metodo'], 'string', 'max' => 50],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'pago_fecha' => Yii::t('app', 'Pago Fecha'),
            'monto' => Yii::t('app', 'Monto'),
            'metodo' => Yii::t('app', 'Metodo'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Usuarios::class, ['id' => 'user_id']);
    }

}
