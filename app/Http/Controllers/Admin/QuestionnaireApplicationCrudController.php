<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\QuestionnaireApplicationRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class QuestionnaireApplicationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class QuestionnaireApplicationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\QuestionnaireApplication::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/questionnaire-application');
        CRUD::setEntityNameStrings('questionnaire application', 'questionnaire applications');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('name');
        CRUD::column('questionnaire_id');
        CRUD::addColumn(['label' => 'Coach', 'name' => 'coach_id', 'type' => 'select', 'model' => "App\Models\User", 'attribute' => 'name', 'entity' => 'coach']);
        CRUD::addColumn(['label' => 'Teacher', 'name' => 'teacher_id', 'type' => 'select', 'model' => "App\Models\User", 'attribute' => 'name', 'entity' => 'teacher']);
        CRUD::column('school_id');
        CRUD::column('application_date');
        CRUD::column('status');
        CRUD::addColumn(['label' => 'Feedback Questionnaire', 'name' => 'feedback_questionnaire_id', 'type' => 'select', 'model' => "App\Models\Questionnaire", 'attribute' => 'title', 'entity' => 'feedbackQuestionnaire']);
        CRUD::addColumn(['label' => 'Documentation Questionnaire', 'name' => 'doc_questionnaire_id', 'type' => 'select', 'model' => "App\Models\Questionnaire", 'attribute' => 'title', 'entity' => 'docQuestionnaire']);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(QuestionnaireApplicationRequest::class);

        CRUD::field('name');
        CRUD::field('questionnaire_id');
        CRUD::addField(['label' => 'Coach', 'name' => 'coach_id', 'type' => 'select', 'model' => "App\Models\User", 'attribute' => 'name']);
        CRUD::addField(['label' => 'Teacher', 'name' => 'teacher_id', 'type' => 'select', 'model' => "App\Models\User", 'attribute' => 'name']);
        CRUD::field('school_id');
        CRUD::field('application_date');
        CRUD::field('status');
        CRUD::addField(['label' => 'Feedback Questionnaire', 'name' => 'feedback_questionnaire_id', 'type' => 'select', 'model' => "App\Models\Questionnaire", 'attribute' => 'title']);
        CRUD::addField(['label' => 'Documentation Questionnaire', 'name' => 'doc_questionnaire_id', 'type' => 'select', 'model' => "App\Models\Questionnaire", 'attribute' => 'title']);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
