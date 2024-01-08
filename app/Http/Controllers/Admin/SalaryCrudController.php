<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SalaryRequest;
use App\Models\Loan;
use App\Models\Salary;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Prologue\Alerts\Facades\Alert;

/**
 * Class SalaryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SalaryCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Salary::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/salary');
        CRUD::setEntityNameStrings('salary', 'salaries');
    }

    protected function setupShowOperation()
    {
        $this->autoSetupShowOperation();
        $this->crud->column('amount')->prefix('Rp.');
        $this->crud->column('overtime_amount')->prefix('Rp.');
        $this->crud->addColumn($this->entityField)->beforeColumn('amount');
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
        $this->crud->removeColumn('user_id');
        $this->crud->column('amount')->prefix('Rp.');
        $this->crud->column('overtime_amount')->prefix('Rp.');
        $this->crud->addColumn($this->entityField)->beforeColumn('amount');
    }


    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(SalaryRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
        $this->crud->field($this->entityField)->beforeColumn('amount');

        $this->crud->field('amount')->prefix('Rp');
        $this->crud->field('overtime_amount')->prefix('Rp');
        $this->crud->field(
            [
                'name'        => 'overtime_type',
                'label'       => 'Jenis Lembur',
                'type'        => 'radio',
                'options'     => [
                    'hour' => 'Per-Jam',
                    'flat' => 'Flat'
                ]
            ]
        );
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
    public function store()
    {
        //  'user_id', 'amount', 'overtime_amount', 'overtime_type',

        $request = $this->crud->validateRequest();
        $salary  = new Salary();
        $salary->user_id = $request->user_id;
        $salary->amount = $request->amount;
        $salary->overtime_amount = $request->overtime_amount;
        $salary->overtime_type = $request->overtime_type;

        $salary->save();
        Alert::add('success', 'Berhasil input Gaji')->flash();
        return redirect(route('salary.index'));
    }

    public function update()
    {
        $request = $this->crud->validateRequest();
        $salary  = new Salary();
        $salary->user_id = $request->user_id;
        $salary->amount = $request->amount;
        $salary->overtime_amount = $request->overtime_amount;
        $salary->overtime_type = $request->overtime_type;

        $salary->save();
        Alert::add('success', 'Berhasil input Gaji')->flash();
        return redirect(route('salary.index'));
    }
}
