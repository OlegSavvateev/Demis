<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "code".
 *
 * @property string $id
 * @property string $computation_id
 * @property integer $code
 *
 * @property Computation $computation
 */
class Code extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'code';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['computation_id', 'code'], 'required'],
            [['computation_id', 'code'], 'integer'],
            [['computation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Computation::className(), 'targetAttribute' => ['computation_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'computation_id' => 'Computation ID',
            'code' => 'Code',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComputation()
    {
        return $this->hasOne(Computation::className(), ['id' => 'computation_id']);
    }
}
