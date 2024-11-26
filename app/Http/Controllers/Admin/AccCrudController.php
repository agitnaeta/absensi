<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AccRequest;
use App\Services\Acc\Acc;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use App\Models\Acc as AccModel;

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
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation  { update as traitUpdate;}
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation ;
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
        CRUD::column('code');
        CRUD::column('source_name')->label('Sumber');
        CRUD::column('destination_name')->label('Target');
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

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */

        $account = $this->acc->getAccounts();
        $this->crud->field('code');
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
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {

        $accReq = $this->crud->validateRequest();
        $this->crud->setValidation(AccRequest::rulesUpdate($accReq->get('id')));
        $account = $this->acc->getAccounts();
        $this->crud->field('code');
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
    }


    public function store(Request $request)
    {
        $account = $this->acc->getAccounts();

        $response =  $this->traitStore();
        $acc=AccModel::get()->last();
        $acc->source_name=$account[$request->get('source_id')];
        $acc->destination_name=$account[$request->get('destination_id')];
        $acc->saveQuietly();

        return $response;
    }

    public function update(Request $request)
    {
        $response = $this->traitUpdate();

        $account = $this->acc->getAccounts();
        $acc=AccModel::findOrFail($request->id);
        $acc->source_name=$account[$request->get('source_id')];
        $acc->destination_name=$account[$request->get('destination_id')];
        $acc->saveQuietly();

        return $response;


    }
}
