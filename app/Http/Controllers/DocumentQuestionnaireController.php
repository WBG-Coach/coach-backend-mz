<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\QuestionnaireApplication;
use App\Models\Answer;
use App\Models\AnswerFile;

class DocumentQuestionnaireController extends Controller
{

    public function getCity($lat, $long)
    {
        // Create a cURL handle
        $ch = curl_init();

        // define options
        $optArray = array(
            CURLOPT_URL => 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$long.'&key=AIzaSyBJR-Qm19jraWkc52MXazoQfMp5uBnZkUg',
            CURLOPT_RETURNTRANSFER => true
        );

        // apply those options
        curl_setopt_array($ch, $optArray);

        // Execute
        $response = curl_exec($ch);

        // Check HTTP status code
        if (!curl_errno($ch)) {
        switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
            case 200:  # OK

            break;
            default:
            // Close handle
            curl_close($ch);
            abort(500, 'Unexpected HTTP code: '.$http_code);
        }
        }

        // Close handle
        curl_close($ch);

        $city = "";
        foreach (json_decode($response)->results[0]->address_components as $addressComponent) {
            foreach ($addressComponent->types as $type) {
                if ($type == 'administrative_area_level_2') {
                    $city = $addressComponent->long_name;
                }
            }
        }

        return $city;
    }

    public function save(Request $request)
    {
        \DB::beginTransaction();

        try {
            if ($request->questionnaire_application_id && $request->answers) {
                $application = QuestionnaireApplication::find($request->questionnaire_application_id);

                foreach ($request->answers as $answer) {
                    $model = new Answer();
                    $model->fill($answer);
                    if (isset($answer['latitude']) && isset($answer['longitude'])) {
                        $model->city = $this->getCity($answer['latitude'], $answer['longitude']);
                    }
                    $model->questionnaire_id = $application->doc_questionnaire_id;
                    $model->questionnaire_application_id = $application->id;
                    $model->save();

                    if (isset($answer['files'])) {

                        foreach ($answer['files'] as $answerFile) {
                            $file = new AnswerFile();
                            $file->answer_id = $model->id;
                            $file->name = $answerFile['name'];
                            $file->url = $answerFile['url'];
                            $file->save();
                        }

                    }
                }

                $application->status = 'DONE';
                $application->update();
                
            }


            \DB::commit();
            return $application;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
