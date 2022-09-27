<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
require __DIR__ . '/../../../rb.php';
use R as R;
use App\Http\Controllers\JWTController;
// use App\Http\Controllers\PatientController;

class ConsultController extends Controller
{

  public function addConsult(Request $request)
  {
    $patientData = [
      'hn', 'name', 'surname', 'gender', 'prefix', 'dob'
    ];
    $consultData = [
      'hn', 'name', 'ward', 'cover', 'an',
      'urgency', 'consult_from', 'sub', 'consult_to', 'detail', 'dx',
      'consult', 'consultee', 'tel'
    ];
    foreach ($consultData as $el) {
      if ($el != 'sub' && $request->input($el) == NULL) {
        return [
          'result' => false,
          'error' => 'Data is not complete'
        ];
      }
    }
    $Patient = new PatientController();
    $PatientArray = array();
    foreach ($patientData as $el) {
      $PatientArray[$el] = $request->input($el);
    }
    $PatientArray['dob'] = date('Y-m-d', $PatientArray['dob']/1000);
    var_dump($PatientArray['dob']);
    $Patient->UpdatePatient($PatientArray['hn'], $PatientArray);

    // R::setup(env('DB_URL'), env('DB_USERNAME'), env('DB_PASSWORD'));

    $newConsult = R::dispense('consultation');
    foreach ($consultData as $el) {
      $newConsult[$el] = $request->input($el);
    }
    $newConsult['name'] .= ' '.$request->input('surname');
    $newId = R::store($newConsult);
    return [
      'result' => true,
      'data' => $newId
    ];
  }

  public function getConsult(Request $request)
  {
    R::setup(env('DB_URL'), env('DB_USERNAME'), env('DB_PASSWORD'));
    $consults = R::findAll( 'consultation' , ' ORDER BY id DESC LIMIT ? ', [$request->input('limit')] );
    return $consults;
  }

  // Return object with key=id if found
  // return [] if not found
  public function getConsultByHN(Request $request) {
    R::setup(env('DB_URL'), env('DB_USERNAME'), env('DB_PASSWORD'));
    $consults = R::findAll( 'consultation' , ' hn = ? ', [$request->input('HN')] );
    return $consults;
  }
}
