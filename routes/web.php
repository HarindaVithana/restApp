<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/sendmail', 'MailController@send');
Route::get('/emppdf', 'PdfController@employeePdf');
Route::get('/venpdf', 'PdfController@vendorPdf');
Route::post('/daypdf', 'PdfController@dayPdf')->name('PdfController.dayPdf');
Route::post('/weekpdf', 'PdfController@weekPdf')->name('PdfController.weekPdf');

Route::post('/checkvendor', 'CheckVendorController@check')->name('CheckVendorController.check');
Route::get('/checkfood', 'CheckVendorController@checkfoodIndex');
Route::get('/login', function () {
    return view('auth.login');
});
Route::get('/admin', 'DashboardController@adminIndex');
Route::post('/admin/daytable', 'DashboardController@daytable')->name('DashboardController.daytable');
Route::post('/admin/weektable', 'DashboardController@weektable')->name('DashboardController.weektable');
Route::post('/inventory/linegraph', 'DashboardController@linegraph')->name('DashboardController.linegraph');
Route::post('/inventory/pricetable', 'DashboardController@pricetable')->name('DashboardController.pricetable');
Route::post('/admin/expensetable', 'DashboardController@expensetable')->name('DashboardController.expensetable');
Route::get('/cashier','BillController@index');

Route::get('/admin/expenseview', 'ExpenseController@expenseIndex');

Route::get('/accountant/setprice','PriceController@setPrice');
Route::get('/accountant','DashboardController@accountIndex');
Route::get('/accountant/order','OrderController@index')->name('OrderController.index');
Route::get('/accountant/payvendor','PayVendorController@index')->name('PayVendorController.index');
Route::post('/accountant/payvendor/data','PayVendorController@getitems')->name('PayVendorController.getitems');
Route::post('/accountant/payvendor/table','PayVendorController@makeTable')->name('PayVendorController.makeTable');
Route::post('/accountant/payvendor/store','PayVendorController@store')->name('PayVendorController.store');
Route::get('/accountant/order/{id}','OrderController@index1')->name('OrderController.index1');
Route::post('/accountant/fetchUnit','OrderController@fetchUnit')->name('OrderController.fetchUnit');
Route::post('/accountant/vendorLoad','OrderController@vendorLoad')->name('OrderController.vendorLoad');
Route::post('/accountant/fillForm','OrderController@fillForm')->name('OrderController.fillForm');
Route::post('/accountant/orderfoods','OrderController@orderfoods')->name('OrderController.orderfoods');
Route::get('autofill/{foodname}', [
    'uses' => 'OrderController@fillForm',
    'as'   => 'autofill']);

Route::post('/inventory/fillform', 'FoodItemController@fillform')->name('FoodItemController.fillform');
Route::get('/inventory','DashboardController@inventoryIndex');
Route::post('foodItems/fetch', 'FoodItemController@fetch')->name('FoodItemController.fetch');
Route::post('foodItems/fetchItemName', 'FoodItemController@fetchItemName')->name('FoodItemController.fetchItemName');
Route::post('foodItems/fetchNameWhenType', 'FoodItemController@fetchNameWhenType')->name('FoodItemController.fetchNameWhenType');
Route::post('foodItems/fetchID', 'FoodItemController@fetchID')->name('FoodItemController.fetchID');
Route::get('/inventory/update', 'FoodItemController@indexUpdate');
Route::get('/inventory/update/{id}', 'FoodItemController@indexUpdate1')->name('FoodItemController.indexUpdate1');
Route::get('/inventory/addnew', 'FoodItemController@indexAddNew');
Route::get('/inventory/recipe', 'RecipeController@indexRecipe');
Route::get('/inventory/issue', 'FoodItemController@indexIssue');
Route::post('recipes/store', 'RecipeController@store');
Route::post('recipes/submit', 'RecipeController@submit')->name('RecipeController.submit');;
Route::post('inventory/editItem', 'FoodItemController@editItem')->name('FoodItemController.editItem');
Route::post('inventory/editSubmit', 'FoodItemController@editSubmit')->name('FoodItemController.editSubmit');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('foodItems/storenew', 'FoodItemController@storenew');

Route::resource('foodItems', 'FoodItemController');

Route::get('/admin/vendors','VendorController@index');
Route::post('/admin/vendors','VendorController@setVendor')->name('VendorController.setVendor');
Route::post('vendors/fetchID','VendorController@fetchID')->name('VendorController.fetchID');
Route::post('vendors/fetchNameWhenType','VendorController@fetchNameWhenType')->name('VendorController.fetchNameWhenType');
Route::post('vendors/fetchVendorName','VendorController@fetchVendorName')->name('VendorController.fetchVendorName');
Route::post('vendors/fetchVendorid','VendorController@fetchvendorID')->name('VendorController.fetchvendorID');
Route::post('vendors/update','VendorController@updateVendor');
Route::post('vendors/remove','VendorController@removeVendor');
Route::post('/admin/vendor/editVendor','VendorController@editVendor')->name('VendorController.editVendor');

Route::get('/admin/employees','EmployeeController@index');
Route::post('/admin/employees','EmployeeController@setEmployee')->name('EmployeeController.setEmployee');
Route::post('employees/fetchID','EmployeeController@fetchID')->name('EmployeeController.fetchID');
Route::post('employees/fetchEmployeeName','EmployeeController@fetchEmployeeName')->name('EmployeeController.fetchEmployeeName');
Route::post('employees/fetchNameWhenType','EmployeeController@fetchNameWhenType')->name('EmployeeController.fetchNameWhenType');
Route::post('employees/fetchEmployeeid','EmployeeController@fetchemployeeID')->name('EmployeeController.fetchemployeeID');
Route::post('employees/update','EmployeeController@updateEmployee');
Route::post('/admin/employee/editEmployee','EmployeeController@editEmployee')->name('EmployeeController.editEmployee');
Route::post('employee/remove','EmployeeController@removeEmployee');

Route::post('recipe/storing','RecipeController@store1')->name('RecipeController.store1');
Route::post('recipe/deleting','RecipeController@delete')->name('RecipeController.delete');  

Route::post('cashier/dish', 'BillController@fetchName')->name('BillController.fetchName');
Route::post('cashier/bill', 'BillController@store')->name('BillController.store');
Route::post('cashier/bill-table', 'BillController@makeTable')->name('BillController.makeTable');
Route::post('cashier/billRemove', 'BillController@billRemove')->name('BillController.billRemove');
Route::post('cashier/billPaid', 'BillController@storePaid')->name('BillController.storePaid');
Route::post('cashier/dishdiv', 'BillController@dishDiv')->name('BillController.dishDiv');

Route::post('account/setprice','PriceController@store')->name('PriceController.store'); 
Route::post('account/editprice','PriceController@edit')->name('PriceController.edit'); 

Route::post('inventory/issue', 'IssueFoodItemsController@store')->name('IssueFoodItemsController.store');
Route::post('inventory/issue-table', 'IssueFoodItemsController@makeTable')->name('IssueFoodItemsController.makeTable');
Route::post('inventory/issueRemove', 'IssueFoodItemsController@issueRemove')->name('IssueFoodItemsController.issueRemove');
Route::post('inventory/submit', 'IssueFoodItemsController@submit')->name('IssueFoodItemsController.submit');

// Route::post('/notification/get','NotificationController@get');
// Route::post('/notification/read','NotificationController@read');

Route::post('notification/check', 'NotificationController@checkNotify')->name('NotificationController.checkNotify');
Route::post('notification/admin', 'NotificationController@adminRead')->name('NotificationController.adminRead');

Route::get('/invoice',function(){
    return view('invoice');

    // $pdf = PDF::loadView('invoice');
    // return $pdf->download('invoice.pdf');
});