<?php

namespace app\models;

use Yii;

use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
/**
 * This is the model class for table "{{%kehadiran}}".
 *
 * @property int $kehadiran_id
 * @property string|null $anggota_id
 * @property int|null $created_by
 * @property string|null $created_at
 * @property string $updated_at
 *
 * @property User $createdBy
 * @property Anggota $nomorAnggota
 */
class Kehadiran extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%kehadiran}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
      return [
        TimestampBehavior::class,
        [
          'class' => BlameableBehavior::class,
          'updatedByAttribute' => false
        ],
        'timestamp' => [
            'class' => 'yii\behaviors\TimestampBehavior',
            'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
            ],
            'value' => new Expression('NOW()'),
        ],
      ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_by'], 'integer'],
            [['created_at'], 'string', 'max' => 45],
            [['updated_at'], 'string', 'max' => 25],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'user_id']],
            [['anggota_id'], 'exist', 'skipOnError' => true, 'targetClass' => Anggota::className(), 'targetAttribute' => ['anggota_id' => 'anggota_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'kehadiran_id' => 'Kehadiran ID',
            'anggota_id' => 'Anggota ID',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['user_id' => 'created_by']);
    }

    /**
     * Gets query for [[NomorAnggota]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\AnggotaQuery
     */
    public function getNomorAnggota()
    {
        return $this->hasOne(Anggota::className(), ['anggota_id' => 'anggota_id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\KehadiranQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\KehadiranQuery(get_called_class());
    }
}
