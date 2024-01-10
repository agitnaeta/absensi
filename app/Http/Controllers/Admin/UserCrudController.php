<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use App\Models\Schedule;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
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
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
        $this->crud->addClause('with','schedule');
    }

    protected function setupShowOperation()
    {
        $this->autoSetupShowOperation();

        $this->crud->removeColumn('schedule_id');
        $this->crud->column( [
            'name' => 'schedule_id',
            'label' => 'Jadwal',
            'type' => 'select',
            'entity' => 'schedule', // the relationship method name
            'attribute' => 'name', // the attribute to display from the related model
            'model' => Schedule::class, // the related model
        ])->after('email');

        $this->crud->column([
           "name"=>"qr",
           "label"=>"QR Code",
           "type"=>"custom_html",
           "value"=> function($entry){
                if(!$entry->qr){
                    $entry->qr = Str::uuid();
                    $entry->saveQuietly();
                }

                $base = base64_encode( QrCode::size(200)
                    ->generate($entry->qr));
                return "<img src='data:image/svg+xml;base64,$base'/>";
           }
        ])->after('email');

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

        $this->crud->removeColumn('schedule_id');
        $this->crud->column( [
            'name' => 'schedule_id',
            'label' => 'Jadwal',
            'type' => 'select',
            'entity' => 'schedule', // the relationship method name
            'attribute' => 'name', // the attribute to display from the related model
            'model' => Schedule::class, // the related model
        ]);

        $this->crud->addButtonFromView('line','user-print','user-print','end');

    }


    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserRequest::class);
        CRUD::setFromDb(); // set fields from db columns.
        CRUD::field([
            'label'=> "Jadwal",
            'name'=>'schedule_id',
            'type'=>'select',
            'model'     => Schedule::class,
            'attribute'=>'name'
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

        $userReq = $this->crud->validateRequest();
        $this->crud->setValidation(
            (new UserRequest())->updateRules($userReq->get('id')),
            (new UserRequest())->messages(),
        );
        CRUD::setFromDb(); // set fields from db columns.
        CRUD::field([
            'Label'=> "Jadwal",
            'name'=>'schedule_id',
            'type'=>'select',
            'model'     => Schedule::class,
            'attribute'=>'name'
        ]);
    }

    public function update()
    {
        $request = $this->crud->validateRequest()->all();
        $user = User::find($request['id']);
        if($request['password']){
            $user->password = Hash::make($request['password']);
        }
        else{
            unset($request['password']);
        }
        $user->update($request);
        Alert::success("<strong>Success</strong><br> Berhasil Update data")->flash();
        return redirect(route('user.index'));
    }

    public function printButton($userId){
        // and even the attributes of the <a> element in meta's `wrapper`
        CRUD::button('print')->stack('line')->view('crud::buttons.quick')->meta([
            'access' => true,
            'label' => 'Print',
            'icon' => 'la la-print',
            'wrapper' => [
                'element' => 'a',
                'href' => route('user.print',['id'=>$userId]),
                'target' => '_blank',
                'title' => 'Print PDF ID CARD',
            ]
        ]);
    }

    public function print($id){
        $user = User::find($id);
        $pdf =  Pdf::loadView('user.detail',compact('user'))
        ->setPaper([0,0,220,300],'p');
        return $pdf->stream("sample.pdf");
    }
}
