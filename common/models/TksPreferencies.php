<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tks_preferencies".
 *
 * @property int $ID
 * @property string $ParamName
 * @property string|null $Value
 * @property string|null $Min
 * @property string|null $Max
 */
class TksPreferencies extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tks_preferencies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['PreferenceName'], 'required'],
            [['PreferenceName', 'Value', 'Min', 'Max'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'PreferenceName' => 'Preference',
            'Value' => 'Value',
            'Min' => 'Min',
            'Max' => 'Max',
        ];
    }

    public static function findByPreferenceName ($PreferenceName)  {
        if (($Preference = SELF::find()->where(['PreferenceName' => $PreferenceName])->one()) !== null) {
            return $Preference;
        }
        throw new NotFoundHttpException('В базе нет свойства '. $PreferenceName);
    }
}
