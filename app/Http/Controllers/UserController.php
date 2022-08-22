<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\Users\StoreRequest;
use App\Http\Requests\Users\StoreCoachRequest;
use App\Http\Requests\Users\StoreTeacherRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Http\Requests\Users\LastAnswerRequest;
use App\Http\Requests\Users\LastFeedbackRequest;
use App\Http\Requests\Users\LastApplicationRequest;
use App\Http\Requests\Users\ChangePasswordRequest;

use App\Models\User;
use App\Models\UserSchool;
use App\Models\Profile;
use App\Models\QuestionnaireApplication;
use App\Models\Answer;
use App\Models\Feedback;
use App\Models\ProjectUser;

use Illuminate\Support\Str;

class UserController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return User::find($request->id);
        }

        $search = User::select('*');
        if ($request->name) {
            $search->whereRaw("name like '%".$request->name."%'");
        }
        if ($request->email) {
            $search->whereRaw("email like '%".$request->email."%'");
        }
        if ($request->profile_id) {
            $search->where("profile_id", $request->profile_id);
        }
        if ($request->pagination) {
            return $search->paginate($request->pagination);
        }
        return $search->get();
    }

    public function lastAnswers(LastAnswerRequest $request)
    {
        $lastApplication = QuestionnaireApplication::where('teacher_id', $request->teacher_id)
        ->where("status", '!=', 'PENDING_RESPONSE')
        ->orderBy('id', 'desc')
        ->first();
        if (!$lastApplication) {
            abort(500, 'Questionnaire Application not found from this Teacher');
        }
        return Answer::with('option.question.competence')->where('questionnaire_application_id', $lastApplication->id)->get();
    }

    public function lastFeedbacks(LastFeedbackRequest $request)
    {
        return Feedback::with('competence')
        -> whereRaw("questionnaire_application_id in (select qa.id 
                                                        from questionnaire_applications qa 
                                                       where qa.status != 'DONE' 
                                                         and qa.teacher_id = ".$request->teacher_id.")")
        -> get();
    }

    public function save(StoreRequest $request)
    {
        \DB::beginTransaction();

        try {
            $user = new User();
            $user->fill($request->all());
            $user->password = bcrypt($request->password);
            $user->api_token = Str::random(80);
            $user->save();

            \DB::commit();
            return $user->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function createCoach(StoreCoachRequest $request)
    {
        \DB::beginTransaction();

        try {
            $profile = Profile::where('name', 'COACH')->first();

            $user = new User();
            $user->fill($request->all());
            $user->password = bcrypt($request->password);
            $user->profile_id = $profile->id;
            $user->api_token = Str::random(80);
            $user->save();

            if ($request->project_id) {
                $pu = new ProjectUser();
                $pu->user_id = $user->id;
                $pu->project_id = $request->project_id;
                $pu->save();
            }

            \DB::commit();
            return $user->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function createTeacher(StoreTeacherRequest $request)
    {
        \DB::beginTransaction();

        try {
            $profile = Profile::where('name', 'TEACHER')->first();

            $user = new User();
            $user->fill($request->all());
            $user->password = bcrypt('pass123');
            $user->profile_id = $profile->id;
            $user->api_token = Str::random(80);
            $user->save();

            $userSchool = new UserSchool();
            $userSchool->school_id = $request->school_id;
            $userSchool->user_id = $user->id;
            $userSchool->save();

            // TODO: pegar o project id do usuario que esta cadastrando e criar o vinculo

            \DB::commit();
            return $user->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function update(UpdateRequest $request)
    {
        \DB::beginTransaction();

        try {

            $user = User::find($request->id);
            $user->fill($request->all());
            if ($request->password) {
                $user->password = bcrypt($request->password);
            }
            $user->update();

            \DB::commit();
            return $request->id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        \DB::beginTransaction();

        try {

            if (\Auth::attempt(['email' => \Auth::guard('api')->user()->email, 'password' => $request->old_password])) {
                $user = User::find(\Auth::guard('api')->user()->id);
                $user->password = bcrypt($request->new_password);
                $user->update();
            }

            \DB::commit();

            return \Auth::user();
        } catch (\Exception $e) {
            \DB::rollback();
            dd($e);
            abort(500, $e);
        }
    }

    public function delete($id)
    {
        \DB::beginTransaction();

        try {
            User::find($id)->update(['deleted_at' => date('Y-m-d H:i:s')]);
            \DB::commit();
            return $id;
        } catch (\Exception $e) {
            \DB::rollback();
            abort(500, $e);
        }
    }
}
