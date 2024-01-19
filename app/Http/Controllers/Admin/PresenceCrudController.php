<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PresenceRequest;
use App\Models\Presence;
use App\Models\User;
use App\Services\PresenceService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Database\Factories\TranslateFactory;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;

/**
 * Class PresenceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PresenceCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {store as storeTrait;}
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
        CRUD::setModel(\App\Models\Presence::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/presence');
        CRUD::setEntityNameStrings('Kehadiran', 'Kehadiran');
        $this->crud->addClause('with','user');

    }

    protected function autoSetupShowOperation()
    {
        CRUD::setFromDb();
        $this->fieldModification();
    }

    protected function setupShowOperation()
    {
        $this->autoSetupShowOperation();
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

        $this->crud->removeColumn('user_id');
        $this->crud->addColumn($this->entityField)->beforeColumn('in');
        $this->fieldModification();
    }

    public function fieldModification(){
        $this->crud->removeColumn('user_id');
        $this->crud->addColumn($this->entityField)->beforeColumn('in');

        $translate = new TranslateFactory();
        foreach($translate->presences() as $key => $value){
            $this->crud->field($key)->label($value);
            $this->crud->column($key)->label($value);
        }

        $fieldsToRemove = ['created_at', 'updated_at','lat','lng'];

        if($this->crud->getCurrentOperation() !='show'){
            foreach ($fieldsToRemove as $field) {
                $this->crud->removeField($field);
                $this->crud->removeColumn($field);
            }
        }


    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PresenceRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */

        $this->crud->field($this->entityField);
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

    public function store()
    {

        $request = $this->crud->validateRequest();
        $presense  = new Presence();
        $presense->user_id = $request->user_id;
        $presense->in = $request->in;
        $presense->out = $request->out;

        $presense->save();
        Alert::add('success', 'Berhasil input data')->flash();
        return redirect(route('presence.index'));
    }

    public function update()
    {
        $request = $this->crud->validateRequest();
        $presense  = $this->crud->getCurrentEntry();
        $presense->user_id = $request->user_id;
        $presense->in = $request->in;
        $presense->out = $request->out;

        $presense->save();
        Alert::add('success', 'Berhasil update data')->flash();
        return redirect(route('presence.index'));
    }

    public function scan(){
        return view('presence.scan');
    }

    public function record(Request $request){
        if($request->qr){
            $user = User::with('schedule')
                ->where("qr",$request->qr)->first();
            if(!$user){
                return response()->json('Failed',500);
            }
            $p = (new PresenceService())->record($user);
            (new PresenceService())->updateCoordinate($p,$request->lat, $request->lng);
            return response()->json($p);
        }
        return response()->json("Not Found",404);
    }
}
