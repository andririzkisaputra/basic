<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "{{%anggota}}".
 *
 * @property int $anggota_id
 * @property string|null $nomor_anggota
 * @property string|null $nama
 * @property string|null $gambar
 * @property string $is_active 0 suspend 1 aktif
 * @property int|null $created_by
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property User $createdBy
 * @property Kehadiran[] $kehadirans
 */
class Anggota extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%anggota}}';
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
          'updatedByAttribute'    => false
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
            [['is_active'], 'string'],
            [['created_by'], 'integer'],
            [['nama'], 'string', 'max' => 255],
            [['nomor_anggota'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'user_id']],
            ['gambar', 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
     public function scenarios() {
       $scenarios = parent::scenarios();
       $scenarios['create'] = ['nomor_anggota', 'nama', 'gambar', 'created_by', 'created_at', 'updated_at'];
       return $scenarios;
     }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'anggota_id' => 'Anggota ID',
            'nomor_anggota' => 'Nomor Anggota',
            'nama' => 'Nama',
            'gambar' => 'Gambar',
            'is_active' => 'Is Active',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
     * Gets query for [[Kehadirans]].
     *
     * @return \yii\db\ActiveQuery|\app\models\query\KehadiranQuery
     */
    public function getKehadirans()
    {
        return $this->hasMany(Kehadiran::className(), ['nomor_anggota' => 'nomor_anggota']);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\query\AnggotaQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\query\AnggotaQuery(get_called_class());
    }
}
