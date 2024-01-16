<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use App\Models\CompanyProfile;
use App\Models\Schedule;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
        $this->crud->column([
            'name'=>'image',
            'label'=>'Logo Perusahaan',
            'type'=>'custom_html',
            'value'=>function($entry){
                $path = "public/$entry->image";
                $storage = Storage::url($path);
                return "<img width='100' height='100' src='$storage' />";
            }
        ]);
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
        $this->printAllIdCard();

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

        $userReq = $this->crud->validateRequest();
        $this->crud->setValidation(
            (new UserRequest())->updateRules($userReq->get('id')),
            (new UserRequest())->messages(),
        );
        CRUD::setFromDb(); // set fields from db columns.
        $this->fieldModification();
    }

    function fieldModification(){

        CRUD::field([
            'Label'=> "Jadwal",
            'name'=>'schedule_id',
            'type'=>'select',
            'model'     => Schedule::class,
            'attribute'=>'name'
        ]);
        CRUD::field('image')
            ->type('upload')
            ->withFiles([
                'disk' => 'public', // the disk where file will be stored
                'path' => 'uploads', // the path inside the disk where file will be stored
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

    public function printAllIdCard(){
        $this->crud->allowAccess('print_id_cards');
        $this->crud->addButtonFromView(
            'top','print_id_cards','print_id_cards','end'
        );
    }

    public function print($id){
        $users = User::where('id',$id)->get();
        return $this->_print($users);
    }
    public function printAll(){
        $users =  User::all();
        return $this->_print($users);
    }
    private function _print(Collection $users){
        $users->map(function ($user){
            $user->isUserImage = strlen($user->image) > 0 ;
            $user->image = Storage::path("public/$user->image");
            if($user->qr){
                $user->qr = base64_encode(QrCode::size(200)->generate($user->qr));
            }
        });
        $company = CompanyProfile::find(1);
        if(!$company->id_card || !$company->image){
            Alert::error("Silahkan Seting Profile Perusaan Terlebih dahulu!")->flash();
            return redirect(route('company-profile.index'));
        }
        $company->image = Storage::path("public/$company->image");
        $company->id_card = Storage::path("public/$company->id_card");
//        return view('user.detail',compact('users','company'));
        $pdf =  Pdf::loadView('user.detail',compact('users','company'))
            ->setPaper([0,0,300,470],'p');
        return $pdf->stream("sample.pdf");
    }
}
