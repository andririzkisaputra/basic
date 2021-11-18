<?php

namespace app\models\query;

/**
 * This is the ActiveQuery class for [[\app\models\Anggota]].
 *
 * @see \app\models\Anggota
 */
class AnggotaQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\models\Anggota[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\models\Anggota|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
