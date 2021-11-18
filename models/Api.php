<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Command;
use yii\db\Query;
use yii\data\Pagination;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;

class Api extends Model {

  public function count_select($tabel, $where = false, $andWhere = false) {
    $count = new Query;
    $count->from($tabel);
    if ($where) {
      $count->where($where);
      if ($andWhere) {
        $count->andWhere($andWhere);
      }
    }
    return $count->count();
  }

  public function select_join($select, $tabel, $tabelJoin, $join, $where = false, $andWhere = false, $group_by = false, $pages = true) {
    $semua = new Query;
    $semua->select($select)->from($tabel)->leftJoin($tabelJoin, $join);
    if ($where) {
      $semua->where($where);
      if ($andWhere) {
        $semua->andWhere($andWhere);
      }
    }
    if ($group_by) {
      $semua->groupBy($group_by);
    }
    $semua->orderBy(['kehadiran.created_at' => SORT_DESC]);
    if ($pages) {
      $countQuery = clone $semua;
      $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 10]);
      $semua->offset($pages->offset)->limit($pages->limit);
    }
    if ($semua->all()) {
      $response = [
        'isStatus' => true,
        'status'   => 'success',
        'pages'    => $pages,
        'data'     => $semua->all(),
        'message'  => 'data berhasil diambil'
      ];
    } else {
      $response = [
        'isStatus' => false,
        'status'   => 'error',
        'pages'    => 'success',
        'data'     => [],
        'message'  => 'gagal mengambil data'
      ];
    }
    return $response;
  }

  public function select_tabel($select, $tabel, $where = false, $group_by = false, $pages = true) {
    $semua = new Query;
    $semua->select($select)->from($tabel);
    if ($where) {
      $semua->where($where);
    }
    if ($group_by) {
      $semua->groupBy($group_by);
    }
    $semua->orderBy(['created_at' => SORT_DESC]);
    if ($pages) {
      $countQuery = clone $semua;
      $pages = new Pagination(['totalCount' => $countQuery->count(), 'defaultPageSize' => 5]);
      $semua->offset($pages->offset)->limit($pages->limit);
    }
    if ($semua->all()) {
      $response = [
        'kode'     => 200,
        'status'   => 'success',
        'pages'    => $pages,
        'data'     => $semua->all(),
        'message'  => 'data ditemukan'
      ];
    } else {
      $response = [
        'kode'     => 404,
        'status'   => 'error',
        'pages'    => '',
        'data'     => [],
        'message'  => 'tidak menemukan data'
      ];
    }
    return $response;
  }

  public function select_tabel_row($select, $tabel, $where = false) {
    $semua = new Query;
    $semua->select($select)->from($tabel);
    if ($where) {
      $semua->where($where);
    }
    if ($semua->one()) {
      $response = [
        'kode'     => 200,
        'status'   => 'success',
        'data'     => $semua->one(),
        'message'  => 'data berhasil diambil'
      ];
    } else {
      $response = [
        'kode'     => 404,
        'status'   => 'error',
        'data'     => [],
        'message'  => 'gagal mengambil data'
      ];
    }
    return $response;
  }

  public function simpan_kehadiran($anggota_id, $user_id = false) {
    if ($anggota_id) {
      $insert = new Kehadiran;
      $insert->anggota_id = $anggota_id;
      if ($user_id) {
        $insert->created_by    = $user_id;
      }
      $insert->created_at    = date('Y-m-d H:s:i');
      $insert->save();
      if ($insert) {
        $response = [
          'kode'     => 200,
          'status'   => 'sukses',
          'data'     => $insert,
          'message'  => 'Berhasil'
        ];
      } else {
        $response = [
          'kode'     => 404,
          'status'   => 'error',
          'data'     => [],
          'message'  => 'Gagal'
        ];
      }
    } else {
      $response = [
        'kode'     => 405,
        'status'   => 'error',
        'data'     => [],
        'message'  => 'belum terdaftar anggota'
      ];
    }
    return $response;
  }

  public function simpan_anggota($model, $post) {
    $model->gambar = UploadedFile::getInstance($model, 'gambar');
    $nomor_anggota = rand(10000, 99999);
    // $nama_format   = strtolower($post['nama'].' '.$nomor_anggota.' '.date('Y-m-d'));
    // if ($model->gambar) {
    //   $nama_format = str_replace(" ", "-", $nama_format).'.'.$model->gambar->extension;
    //   $model->gambar->saveAs('uploads/'.$nama_format);
    //
    //   $imagine = Image::getImagine();
    //   $image = $imagine->open('uploads/'.$nama_format);
    //   $image->resize(new Box(300, 400))->save('uploads/thumb_'.$nama_format, ['quality' => 70]);
    // }

    $nama_format = strtolower($post['nama']);
    $nama_format = str_replace(" ", "-", $nama_format).'.jpg';
    $model->attributes    = Yii::$app->request->post('Anggota');
    $model->nama          = $post['nama'];
    $model->nomor_anggota = $nomor_anggota;
    $model->gambar        = $nama_format;
    if (Yii::$app->user->identity->user_id) {
      $model->created_by  = Yii::$app->user->identity->user_id;
    }
    $model->created_at    = date('Y-m-d h:i:s');
    if ($model->save()) {
      $response = [
        'kode'     => 201 ,
        'status'   => 'success',
        'data'     => $model,
        'message'  => 'Berhasil',
      ];
    } else {
      $response = [
        'kode'     => 404,
        'status'   => 'error',
        'data'     => [],
        'message'  => 'Gagal'
      ];
    }
    return $response;;
  }

  public function update_anggota($anggota_id, $data, $user_id) {
    $semua = new Query;
    $cek_data = $semua->from('anggota')->where(['=', 'anggota_id', $anggota_id])->andWhere(['=', 'created_by', $user_id])->one();
    if ($cek_data) {
      if (isset($data['gambar'])) {
        $baseUrl = Yii::$app->getBasePath();
        @unlink($baseUrl.'/uploads/'.$cek_data['gambar']);
        @unlink($baseUrl.'/uploads/thumb_'.$cek_data['gambar']);
        $update = Anggota::findOne([
          'anggota_id' => $anggota_id,
        ]);
        $update->nama        = $data['nama'];
        $update->gambar      = $data['gambar'];
        $update->updated_at  = date('Y-m-d H:s:i');
      } else {
        $update = Anggota::findOne([
          'anggota_id' => $anggota_id,
        ]);
        $update->nama        = $data['nama'];
        $update->updated_at  = date('Y-m-d H:s:i');
      }
      $update->save();
      if ($update) {
        $response = [
          'kode'     => 200,
          'status'   => 'success',
          'data'     => $update,
          'message'  => 'data berhasil diubah'
        ];
      } else {
        $response = [
          'kode'     => 404,
          'status'   => 'error',
          'data'     => [],
          'message'  => 'gagal mengubah data'
        ];
      }
    } else {
      $response = [
        'kode'     => 405,
        'status'   => 'error',
        'data'     => [],
        'message'  => 'data tidak bisa diubah'
      ];
    }
    return $response;
  }

  public function delete_anggota($anggota_id, $created_by = false, $gambar = false, $auth_key = false) {
    $data = [
      'anggota_id' => $anggota_id,
    ];
    if ($auth_key) {
      $user     = User::find()->select('user_id')->where(['auth_key' => $auth_key])->one();
      $cek_data = Anggota::find()->where($data)->count();
    } else {
      $cek_data      = Anggota::find()->where($data)->count();
      $cek_kehadiran = Kehadiran::find()->where($data)->count();
    }

    if ($cek_data) {
      if ($cek_kehadiran) {
        $this->delete_kehadiran($data);
      }
      $delete = Anggota::findOne($data);
      if ($gambar) {
        $baseUrl = Yii::$app->getBasePath();
        @unlink($baseUrl.'/uploads/'.$gambar);
        @unlink($baseUrl.'/uploads/thumb_'.$gambar);
      }
      $delete->delete();
      if ($delete) {
        $response = [
          'kode'     => 200,
          'status'   => 'sukses',
          'data'     => [],
          'message'  => 'Berhasil'
        ];
      } else {
        $response = [
          'kode'     => 404,
          'status'   => 'error',
          'data'     => [],
          'message'  => 'Gagal'
        ];
      }
    } else {
      $response = [
        'kode'     => 405,
        'status'   => 'error',
        'data'     => [],
        'message'  => 'data tidak bisa dihapus'
      ];
    }
    return $response;
  }

  public function delete_kehadiran($data) {
    $delete = Kehadiran::findOne($data);
    return $delete->delete();
  }

}
