<?php

namespace App\Http\Controllers\Admin\Operations;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;
use Prologue\Alerts\Facades\Alert;

trait SetPaymentOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupSetPaymentRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/set-payment', [
            'as'        => $routeName.'.setPayment',
            'uses'      => $controller.'@setPayment',
            'operation' => 'setPayment',
        ]);

    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupSetPaymentDefaults()
    {
        CRUD::allowAccess('setPayment');
        CRUD::allowAccess('set_payment_cash');
        CRUD::allowAccess('set_payment_transfer');

        CRUD::operation('setPayment', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
            // CRUD::addButton('top', 'set_payment', 'view', 'crud::buttons.set_payment');
             CRUD::addButton('line', 'set_payment_cash', 'view', 'crud::buttons.set_payment_cash');
             CRUD::addButton('line', 'set_payment_transfer', 'view', 'crud::buttons.set_payment_transfer');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function setPayment()
    {


        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['title'] = CRUD::getTitle() ?? 'Set Payment '.$this->crud->entity_name;
        $recap = $this->crud->getCurrentEntry();

        $recap->paid = 1;
        $recap->method = $this->crud->getRequest()->get('method');
        $recap->save();

        Alert::add('success', '<strong>Berhasil</strong><br>Berhasil bayar secara '.$recap->method)->flash();
        return redirect(route('salary-recap.index'));
    }


}
