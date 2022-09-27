<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
require_once __DIR__ . '/../../../rb.php';
use R as R;

class PatientController extends BaseController
{
  public function UpdatePatient($HN, $data) {
    R::setup(env('DB_URL'), env('DB_USERNAME'), env('DB_PASSWORD'));
    $pt = R::findOne('patient', ' HN = ? ', [$HN]);
    if ($pt == null) {
      $pt = R::dispense('patient');
      $pt['HN'] = $HN;
    }
    $pt->name = $data['name'];
    $pt->surname = $data['surname'];
    $pt->gender = $data['gender'];
    $pt->prefix = $data['prefix'];
    $pt->dob = $data['dob'];
    $id = R::store($pt);
  }
  public function GetPatient($HN) {
    R::setup(env('DB_URL'), env('DB_USERNAME'), env('DB_PASSWORD'));
    
    $pt = R::findOne('patient', ' HN = ? ', [$HN]);
    if ($pt == null) {
      $pt = array();
    } else {
      $pt = $pt->export();
    }
    return $pt;
  }
}

