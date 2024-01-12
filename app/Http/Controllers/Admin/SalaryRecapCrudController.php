<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SalaryRecapRequest;
use App\Models\SalaryRecap;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Database\Factories\TranslateFactory;
use Prologue\Alerts\Facades\Alert;

/**
 * Class SalaryRecapCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SalaryRecapCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    protected $entityField = [
        'name'=>'user_id',
        'entity'=>'user',
        'model'=>User::class,
        'attribute'=>'name',
        'type'=>'select',
        'label'=>'Nama Karyawan'
    ];

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\SalaryRecap::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/salary-recap');
        CRUD::setEntityNameStrings('Rekap Gaji', 'Rekap Gaji');
        $this->crud->addClause('with','user');
        $this->crud->denyAccess('create');
    }


    protected function setupShowOperation()
    {
        $this->autoSetupShowOperation();
        $this->crud->addColumn($this->entityField)->makeFirstColumn();
        $this->fieldModification();
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
        $this->fieldModification();
    }

    public function fieldModification(){
        // display
        $this->crud->removeColumn('user_id');
        $this->crud->removeColumn('work_day');
        $this->crud->removeColumn('late_day');
        $this->crud->removeColumn('loan_cut');
        $this->crud->removeColumn('late_cut');
        $this->crud->removeColumn('abstain_cut');
        $this->crud->removeColumn('created_at');
        $this->crud->removeColumn('received_at');
        $this->crud->removeColumn('updated_at');
        $this->crud->addColumn($this->entityField)->afterColumn('recap_month');
        $columns = array_merge([$this->entityField],$this->crud->columns());
        $this->crud->setColumns($columns);

        if($this->crud->getCurrentOperation() != 'show'){
            $this->crud->removeButtons(['delete','update']);
        }

        // form
        $this->crud->field($this->entityField);
        $this->crud->field([
            'name'=>'method',
            'value'=> $this->crud->getCurrentEntry()->method ?? '',
            'type'=>'payment_method',
            'placeholder'=>'payment'
        ]);

        $attrDisable = ['readonly'=>'true','class'=>'disabled-input form-control'];

        $this->crud->field('recap_month')->attributes($attrDisable);
        $this->crud->field('user_id')->attributes($attrDisable);
        $this->crud->field('work_day')->attributes($attrDisable);
        $this->crud->field('late_day')->attributes($attrDisable);
        $this->crud->field('salary_amount')->attributes($attrDisable);
        $this->crud->field('overtime_amount')->attributes($attrDisable);
        $this->crud->field('late_cut')->attributes($attrDisable);
        $this->crud->field('received')->attributes($attrDisable);
        $this->crud->field('abstain_cut')->attributes($attrDisable);
        $this->crud->field('abstain_count')->attributes($attrDisable);

        // Translate Field
        $translate = new TranslateFactory();
        foreach($translate->salaryRecap() as  $key => $value){
            $this->crud->field($key)->label($value);
            $this->crud->column($key)->label($value);
        }

        // Prefix
        foreach($translate->salaryRecapPrefix() as  $key => $value){
            $this->crud->field($key)->prefix($value);
            $this->crud->column($key)->prefix($value);
        }
        $this->crud->orderFields([
            'user_id',
            'recap_month',
            'work_day',
            'abstain_count',
            'late_count',
        ]);
        $this->crud->field('loan_cut')->before('received');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(SalaryRecapRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
        $this->fieldModification();

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

    public function store(){
        $request = $this->crud->validateRequest();
        SalaryRecap::create($request->all());
        Alert::success('Berhasil Update data')->flash();
        return redirect(route('salary-recap.index'));
    }

    public function update()
    {
        $request = $this->crud->validateRequest();
        $salaryRecap   = $this->crud->getCurrentEntry();
        $salaryRecap->update($request->all());
        Alert::success('Berhasil Update data')->flash();
        return redirect(route('salary-recap.index'));
    }
}
