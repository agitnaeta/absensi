<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LoanPaymentRequest;
use App\Models\LoanPayment;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Prologue\Alerts\Facades\Alert;

/**
 * Class LoanPaymentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LoanPaymentCrudController extends CrudController
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
        CRUD::setModel(\App\Models\LoanPayment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/loan-payment');
        CRUD::setEntityNameStrings('loan payment', 'loan payments');
        $this->crud->addClause('with','user');
    }
    protected function setupShowOperation()
    {
        $this->autoSetupShowOperation();
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
        CRUD::setValidation(LoanPaymentRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
        $this->crud->field('amount')->prefix('Rp.');
        $this->crud->field($this->entityField);
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

        $request = $this->crud->validateRequest();
        $loan  = new LoanPayment();
        $loan->user_id = $request->user_id;
        $loan->amount = $request->amount;
        $loan->date = $request->date;

        $loan->save();
        Alert::add('success', 'Berhasil input data')->flash();
        return redirect(route('loan-payment.index'));
    }

    public function update()
    {
        $request = $this->crud->validateRequest();
        $loan  = new LoanPayment();
        $loan->user_id = $request->user_id;
        $loan->amount = $request->amount;
        $loan->date = $request->date;

        $loan->save();
        Alert::add('success', 'Berhasil update data')->flash();
        return redirect(route('loan-payment.index'));
    }
}
