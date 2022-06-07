<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OptionApiTest extends TestCase
{

    /**
     *  Test Search Options
     *
     * @return void
     */
    public function test_search_without_params()
    {
        $response = $this->post('/api/options/search', []);
        $response->assertStatus(200);
    }

    public function createMatrix()
    {
        $model = new \App\Models\Matrix();
        $model->name = 'Matrix Test';
        $model->save();
        
        return $model->id;
    }

    public function createCompetency()
    {
        $matrixId = $this->createMatrix();

        $model = new \App\Models\Competence();
        $model->matrix_id = $matrixId;
        $model->name = 'Competence Test';
        $model->description = 'Competence Test Description 2';
        $model->save();

        return $model->id;
    }

    public function createQuestion()
    {
        $competencyId = $this->createCompetency();

        try {
            $model = new \App\Models\Question();
            $model->text = 'Question Test?';
            $model->competency_id = $competencyId;
            $model->save();

            return $model->id;
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    /**
     * Test Create Option
     *
     * @return void
     */
    public function test_create_option()
    {
        \DB::beginTransaction();

        $questionId = $this->createQuestion();
        $text = 'test_text '.date('Ymdhis');
        
        $response = $this->post('/api/options', [
            'question_id' => $questionId,
            'text' => $text
        ]);

        $response->assertStatus(200);
        $this->assertEquals(\App\Models\Option::where('text', $text)->count(), 1);

        \DB::rollback();
    }

    /**
     * Test Update Option
     *
     * @return void
     */
    public function test_update_option()
    {
        \DB::beginTransaction();

        $questionId = $this->createQuestion();
        $text = 'test_text '.date('Ymdhis');

        $model = new \App\Models\Option();
        $model->question_id = $questionId;
        $model->text = $text;
        $model->save();
        
        $response = $this->put('/api/options', [
            'id' => $model->id,
            'selected_color' => '#33CC5A',
            'selected_icon' => 'thumbs-up',
            'text' => $text.' test'
        ]);

        $response->assertStatus(200);
        $this->assertEquals(\App\Models\Option::where('text', $text.' test')->where('question_id', $questionId)->where('selected_color', '#33CC5A')->where('selected_icon', 'thumbs-up')->count(), 1);

        \DB::rollback();
    }
}
