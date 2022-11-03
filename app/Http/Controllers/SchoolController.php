<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Schools\StoreRequest;
use App\Http\Requests\Schools\UpdateRequest;

use App\Models\School;
use App\Models\UserSchool;
use App\Models\QuestionnaireApplication;
use App\Models\Answer;

class SchoolController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            $schools = School::with('users.user')->find($request->id);

            foreach ($schools['users'] as $userSchool) {
                if (isset($userSchool)) {
                    $lastApplication = QuestionnaireApplication::where('teacher_id', $userSchool['user']['id'])
                    ->where("status", '!=', 'PENDING_RESPONSE')
                    ->orderBy('id', 'desc')
                    ->first();
                    
                    if ($lastApplication) {
                        $userSchool['user']['answers'] = Answer::with('option.question.competence')->where('questionnaire_application_id', $lastApplication->id)->get();
                    }
                }
            }
            

            return $schools;
        }

        $search = School::select('*');
        
        if ($request->coach_id) {
            $search->whereRaw("schools.id in (select us.school_id from user_schools us where us.user_id = ".$request->coach_id.")");
        }
        if ($request->name) {
            $search->whereRaw("name like '%".$request->name."%'");
        }
        if ($request->project_id) {
            $search->where("project_id", $request->project_id);
        }
        if ($request->pagination) {
            return $search->paginate($request->pagination);
        }
        return $search->get();
    }

    public function getCoords($address)
    {
        // Create a cURL handle
        $ch = curl_init();

        // define options
        $optArray = array(
            CURLOPT_URL => 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBJR-Qm19jraWkc52MXazoQfMp5uBnZkUg&address='.str_replace(' ', '%20', $address),
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

        $location = json_decode($response)->results[0]->geometry->location;

        return [$location->lat, $location->lng];

    }

    public function save(StoreRequest $request)
    {
        \DB::beginTransaction();

        try {
            $school = new School();
            $school->fill($request->all());
            $school->save();

            try {
                $coords = $this->getCoords($school->address);
                $school->latitude = $coords[0];
                $school->longitude = $coords[1];
                $school->update();
            } catch (\Exception $e) {
                abort(500, $e);
            }

            $userSchool = new UserSchool();
            $userSchool->user_id = \Auth::user()->id;
            $userSchool->school_id = $school->id;
            $userSchool->save();

            \DB::commit();
            return $school->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function update(UpdateRequest $request)
    {
        \DB::beginTransaction();

        try {

            $school = School::find($request->id);
            $school->fill($request->all());
            $school->update();

            try {
                $coords = $this->getCoords($school->address);
                $school->latitude = $coords[0];
                $school->longitude = $coords[1];
                $school->update();
            } catch (\Exception $e) {
                abort(500, $e);
            }

            \DB::commit();
            return $request->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function delete($id)
    {
        \DB::beginTransaction();

        try {
            if(UserSchool::where('school_id', $id)->exists()) {
                abort(500, 'This school has linked users');
            }
            
            School::destroy($id);

            \DB::commit();
            return $id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
