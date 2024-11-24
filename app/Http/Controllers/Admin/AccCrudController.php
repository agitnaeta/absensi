<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AccRequest;
use App\Services\Acc\Acc;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AccCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AccCrudController extends CrudController
{
    protected $acc;
    public function __construct(Acc $acc) {
        parent::__construct();
        $this->acc = $acc;
    }

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
        CRUD::setModel(\App\Models\Acc::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/acc');
        CRUD::setEntityNameStrings('acc', 'accs');
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
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(AccRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

//        $table->string("destination_id");
//        $table->string("destination_name");
//        $table->string("source_id");
//        $table->string("source_name");

        $account = $this->acc->getAccounts();
        $this->crud->field(
            [
                'name'        => 'source_id',
                'label'       => 'Sumber',
                'type'        => 'select_from_array',
                'required' => true,
                'options'=>$account,
        ]);

        $this->crud->field(
            [
                'name'        => 'destination_id',
                'label'       => 'Target',
                'type'        => 'select_from_array',
                'required' => true,
                'options'=>$account,
            ]);

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
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
