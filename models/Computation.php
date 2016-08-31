<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "computation".
 *
 * @property string $id
 * @property string $title
 * @property string $text
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Code[] $codes
 */
class Computation extends ActiveRecord
{
    public function getCodesString()
    {
        $codesString = '';
        foreach ($this->codes as $code) {
            $codesString .= $code->code . ', ';
        }
        return substr($codesString, 0, -2);
        
    }


    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE,
        ];
    }

    public function setCodes($codes)
    {
        $this->populateRelation('codes', $codes);
    }

    public function setCodesFromIntArray($codesArray)
    {
        $codes = [];
        foreach ($codesArray as $c) {
            $code = new Code();
            $code->code = $c;
            $codes[] = $code;
        }
        $this->setCodes($codes);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Если модель обновляется удаляем старые коды
            if (!$insert) {
                Code::deleteAll('computation_id = ' . $this->id);
            }
            return true;
        }
        return false;
    }


    public function afterSave($insert, $changedAttributes)
    {
        $relatedRecords = $this->getRelatedRecords();
        if (isset($relatedRecords['codes'])) {
            foreach ($relatedRecords['codes'] as $code) {
                $this->link('codes', $code);
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function findCodes()
    {
        $codes = [];
        $textLength = strlen($this->text);
        for ($i = 0; $i < $textLength; $i++) {
            if (($this->text{$i} == '{') && ($this->text{$i+1} != '{')) {
                $i++;
                $str = '';
                if ($this->text{$i} == '-') {
                    $str .= '-';
                    $i++;
                }
                if ($this->text{$i} == '+') {
                    $i++;
                }
                while ($this->text{$i} != '}') {
                    if (($i<$textLength) && is_numeric($this->text{$i})) {
                        $str .= $this->text{$i};
                    } else {
                        $str = '';
                        break; 
                    }
                    $i++;
                }
                if ($str != '') $codes[] = (int)$str;
            }
        }
        return $codes;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'computation';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'text'], 'required'],
            [['text'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название расчета',
            'text' => 'Расчет',
            'created_at' => 'Создан',
            'updated_at' => 'Изменен',
            'codesString' => "Коды",
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCodes()
    {
        return $this->hasMany(Code::className(), ['computation_id' => 'id']);
    }
}
