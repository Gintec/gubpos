<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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


Auth::routes(['verify' => true]);
// ->middleware('role:editor,approver');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('logout');

// MEMBERS
Route::get('/members', [App\Http\Controllers\HomeController::class, 'members'])->name('members')->middleware('role:Worker,Admin,Followup,Staff,Finance,Super');
Route::get('/customers', [App\Http\Controllers\HomeController::class, 'customers'])->name('customers')->middleware('role:Worker,Admin,Followup,Staff,Finance,Super');

Route::get('/add-new', [App\Http\Controllers\HomeController::class, 'addNew'])->name('add-new')->middleware('role:Worker,Admin,Followup,Staff,Super');
Route::post('/addnew', [App\Http\Controllers\HomeController::class, 'create'])->name('addnew')->middleware('role:Worker,Admin,Followup,Staff,Super');
Route::get('/edit-member/{id}/', [App\Http\Controllers\HomeController::class, 'editMember'])->name('edit-member')->middleware('role:Worker,Admin,Followup,Staff,Super');
Route::get('/member/{id}/', [App\Http\Controllers\HomeController::class, 'member'])->name('member')->middleware('role:Worker,Admin,Followup,Staff,Super');
Route::get('/delete-member/{id}', [App\Http\Controllers\HomeController::class, 'deleteMember'])->name('delete-member')->middleware('role:Admin,Followup,Staff,Super');
Route::post('/settings', [App\Http\Controllers\HomeController::class, 'settings'])->name('settings')->middleware('role:Super');
Route::post('/switchbusiness', [App\Http\Controllers\HomeController::class, 'switchbusiness'])->name('switchbusiness')->middleware('role:Super,Admin');
Route::post('/searchmembers', [App\Http\Controllers\HomeController::class, 'membersSearch'])->name('searchmembers')->middleware('role:Worker,Admin,Followup,Staff,Finance,Super');

Route::get('/businesses', [App\Http\Controllers\HomeController::class, 'businesses'])->name('businesses')->middleware('role:Super');


// MATERIALS
Route::get('/materials', [App\Http\Controllers\MaterialsController::class, 'index'])->name('materials')->middleware('role:Admin,Super,Staff');
Route::post('/addmaterial', [App\Http\Controllers\MaterialsController::class, 'store'])->name('addmaterial')->middleware('role:Admin,Super,Staff');
Route::get('/material/{id}', [App\Http\Controllers\MaterialsController::class, 'material'])->name('material');
Route::get('/delete-mat/{id}', [App\Http\Controllers\MaterialsController::class, 'destroy'])->name('delete-mat')->middleware('role:Admin,Super,Staff');

// MATERIAL DAMAGES
Route::get('/material-damages', [App\Http\Controllers\MaterialsController::class, 'damages'])->name('material-damages')->middleware('role:Admin,Super,Staff');
Route::post('/adddmaterial', [App\Http\Controllers\MaterialsController::class, 'adddMaterial'])->name('adddmaterial')->middleware('role:Admin,Super,Staff');
Route::get('/delete-dmat/{id}', [App\Http\Controllers\MaterialsController::class, 'removedMaterial'])->name('delete-dmat')->middleware('role:Admin,Super,Staff');

// SUPPLIERS
Route::get('/suppliers', [App\Http\Controllers\SuppliersController::class, 'index'])->name('suppliers')->middleware('role:Admin,Super,Staff');
Route::post('/addsupplier', [App\Http\Controllers\SuppliersController::class, 'store'])->name('addsupplier')->middleware('role:Admin,Super,Staff');
Route::get('/supplier/{id}', [App\Http\Controllers\SuppliersController::class, 'supplier'])->name('supplier');
Route::get('/delete-sup/{id}', [App\Http\Controllers\SuppliersController::class, 'destroy'])->name('delete-sup')->middleware('role:Admin,Super,Staff');

// SUPPLIES
Route::get('/supplies', [App\Http\Controllers\MaterialSuppliesController::class, 'index'])->name('supplies')->middleware('role:Admin,Super,Staff');
Route::post('/addsupply', [App\Http\Controllers\MaterialSuppliesController::class, 'store'])->name('addsupply')->middleware('role:Admin,Super,Staff');
Route::get('/supply/{id}', [App\Http\Controllers\MaterialSuppliesController::class, 'supply'])->name('supply');
Route::get('/delete-sp/{id}', [App\Http\Controllers\MaterialSuppliesController::class, 'destroy'])->name('delete-sp')->middleware('role:Admin,Super,Staff');

// MATERIAL CHECKOUTS
Route::get('/mcheckouts', [App\Http\Controllers\MaterialCheckoutsController::class, 'index'])->name('mcheckouts')->middleware('role:Admin,Super,Staff');
Route::post('/addmcheckout', [App\Http\Controllers\MaterialCheckoutsController::class, 'store'])->name('addmcheckout')->middleware('role:Admin,Super,Staff');
Route::get('/delete-mtc/{id}/{mid}/{qty}', [App\Http\Controllers\MaterialCheckoutsController::class, 'destroy'])->name('delete-mtc')->middleware('role:Super');


// PRODUCT SUPPLIES
Route::get('/psupplies', [App\Http\Controllers\ProductSuppliesController::class, 'index'])->name('psupplies')->middleware('role:Admin,Super,Staff');
Route::post('/addpsupply', [App\Http\Controllers\ProductSuppliesController::class, 'store'])->name('addpsupply')->middleware('role:Admin,Super,Staff');
Route::get('/psupply/{id}', [App\Http\Controllers\ProductSuppliesController::class, 'psupply'])->name('psupply');
Route::get('/delete-psp/{id}', [App\Http\Controllers\ProductSuppliesController::class, 'destroy'])->name('delete-sp')->middleware('role:Admin,Super,Staff');


// TASKS / TO DOs
Route::post('/newtask', [App\Http\Controllers\TasksController::class, 'store'])->name('newtask')->middleware('role:Worker,Admin,Followup,Staff,Super');
Route::post('/newfollowup', [App\Http\Controllers\TasksController::class, 'newfollowup'])->name('newfollowup')->middleware('role:Worker,Admin,Followup,Staff,Super');
Route::get('/tasks', [App\Http\Controllers\TasksController::class, 'index'])->name('tasks')->middleware('role:Worker,Admin,Followup,Staff,Super');
Route::get('/completetask/{id}', [App\Http\Controllers\TasksController::class, 'completetask'])->name('completetask')->middleware('role:Worker,Admin,Followup,Staff,Super');
Route::get('/inprogresstask/{id}', [App\Http\Controllers\TasksController::class, 'inprogresstask'])->name('inprogresstask')->middleware('role:Worker,Admin,Followup,Staff,Super');
Route::get('/delete-task/{id}', [App\Http\Controllers\TasksController::class, 'destroy'])->name('destroy')->middleware('role:Super');
Route::get('/delete-followup/{id}', [App\Http\Controllers\TasksController::class, 'deletefollowup'])->name('delete-followup')->middleware('role:Worker,Admin,Followup,Staff,Super');

Route::get('/mytickets', [App\Http\Controllers\TasksController::class, 'myTickets'])->name('mytickets')->middleware('role:Customer,Supplier,Distributor,Drivers');


// ACCOUNT HEADS
Route::get('/account-heads', [App\Http\Controllers\AccountheadsController::class, 'index'])->name('account-heads')->middleware('role:Finance,Admin,Super');
Route::post('/addaccounthead', [App\Http\Controllers\AccountheadsController::class, 'store'])->name('addaccounthead')->middleware('role:Finance,Admin,Super');
Route::get('/delete-acch/{id}', [App\Http\Controllers\AccountheadsController::class, 'destroy'])->name('delete-acch')->middleware('role:Super');

// ACCOUNT HEADS
Route::get('/categories', [App\Http\Controllers\CategoriesController::class, 'index'])->name('categories')->middleware('role:Finance,Admin,Super');
Route::post('/addcategory', [App\Http\Controllers\CategoriesController::class, 'store'])->name('addcategory')->middleware('role:Finance,Admin,Super');
Route::get('/delete-cat/{id}', [App\Http\Controllers\CategoriesController::class, 'destroy'])->name('delete-cat')->middleware('role:Super');

// PRODUCTION JOBS
Route::get('/productionjobs', [App\Http\Controllers\ProductionJobsController::class, 'index'])->name('productionjobs')->middleware('role:Finance,Admin,Super');
Route::post('/addproduction', [App\Http\Controllers\ProductionJobsController::class, 'store'])->name('addproduction')->middleware('role:Finance,Admin,Super');
Route::get('/pjob/{batchno}', [App\Http\Controllers\ProductionJobsController::class, 'pjob'])->where('batchno', '.*')->name('pjob')->middleware('role:Super,Admin,Staff');
Route::get('/delete-pjob/{id}', [App\Http\Controllers\ProductionJobsController::class, 'destroy'])->name('delete-pjob')->middleware('role:Super');


// PRODUCTS
Route::get('/products', [App\Http\Controllers\ProductsController::class, 'index'])->name('products')->middleware('role:Finance,Admin,Super');
Route::post('/addproduct', [App\Http\Controllers\ProductsController::class, 'store'])->name('addproduct')->middleware('role:Finance,Admin,Super');
Route::get('/delete-prd/{id}', [App\Http\Controllers\ProductsController::class, 'destroy'])->name('delete-prd')->middleware('role:Super');
Route::get('/product/{id}', [App\Http\Controllers\ProductsController::class, 'product'])->name('product')->middleware('role:Super');

// PRODUCT SALES
Route::get('/sales', [App\Http\Controllers\ProductSalesController::class, 'index'])->name('sales')->middleware('role:Finance,Admin,Super,Staff');
Route::get('/newsales', [App\Http\Controllers\ProductSalesController::class, 'sale'])->name('newsales')->middleware('role:Finance,Admin,Super,Staff');

Route::post('/addsales', [App\Http\Controllers\ProductSalesController::class, 'store'])->name('addsales')->middleware('role:Finance,Admin,Super,Staff');
Route::post('/update-invoice', [App\Http\Controllers\ProductSalesController::class, 'updateInvoice'])->name('update-invoice')->middleware('role:Finance,Admin,Super,Staff');

Route::get('/invoice/{category}/{tid}', [App\Http\Controllers\ProductSalesController::class, 'invoice'])->name('invoice')->middleware('role:Finance,Admin,Super,Staff');
// Route::get('/pinvoice/{category}/{tid}', [App\Http\Controllers\ProductSalesController::class, 'invoice'])->name('invoice')->middleware('role:Finance,Admin,Super,Staff');
Route::get('/send-document/{category}/{tid}', [App\Http\Controllers\ProductSalesController::class, 'sendDocument'])->name('send-document')->middleware('role:Finance,Admin,Super,Staff');

Route::get('/new-invoice/{category}/{tid}', [App\Http\Controllers\ProductSalesController::class, 'newInvoice'])->name('new-invoice')->middleware('role:Finance,Admin,Super,Staff');
Route::get('/proformas', [App\Http\Controllers\ProductSalesController::class, 'proformas'])->name('proforma')->middleware('role:Finance,Admin,Super,Staff');
Route::get('/invoices', [App\Http\Controllers\ProductSalesController::class, 'invoices'])->name('invoices')->middleware('role:Finance,Admin,Super,Staff');

Route::get('/newproforma', [App\Http\Controllers\ProductSalesController::class, 'newproforma'])->name('newproforma')->middleware('role:Finance,Admin,Super,Staff');
Route::post('/addproforma', [App\Http\Controllers\ProductSalesController::class, 'addproforma'])->name('addproforma')->middleware('role:Finance,Admin,Super,Staff');

Route::get('/edit-invoice/{tid}', [App\Http\Controllers\ProductSalesController::class, 'editInvoice'])->name('edit-invoice')->middleware('role:Finance,Admin,Super,Staff');


// REPORTS
Route::post('generateInvoiceReport', [App\Http\Controllers\ProductSalesController::class, 'generateInvoiceReport'])->name('generateInvoiceReport')->middleware('role:Admin,Staff,Super');


// SERVICES
Route::get('/services', [App\Http\Controllers\ServiceController::class,'index'])->name('services');
Route::get('/new_service', [App\Http\Controllers\ServiceController::class,'create'])->name('new_service');
Route::get('/service/{id}', [App\Http\Controllers\ServiceController::class,'edit'])->name('service');
Route::get('/edit-service/{id}', [App\Http\Controllers\ServiceController::class,'edit'])->name('edit-service')->middleware('role:Super');
Route::post('/save-service', [App\Http\Controllers\ServiceController::class,'store'])->name('save-service');
Route::get('/del-service/{id}', [App\Http\Controllers\ServiceController::class,'delete'])->name('del-service');

// PRODUCT DAMAGES
Route::get('/product-damages', [App\Http\Controllers\ProductsController::class, 'damages'])->name('product-damages')->middleware('role:Admin,Super,Staff');
Route::post('/adddproduct', [App\Http\Controllers\ProductsController::class, 'adddProduct'])->name('adddproduct')->middleware('role:Admin,Super,Staff');
Route::get('/delete-dprd/{id}', [App\Http\Controllers\ProductsController::class, 'removedproduct'])->name('delete-dprd')->middleware('role:Admin,Super,Staff');

// ATTENDANCE
Route::get('/attendance', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendance')->middleware('role:Usher,Admin,Super');
Route::post('/addattendance', [App\Http\Controllers\AttendanceController::class, 'store'])->name('addattendance')->middleware('role:Usher,Admin,Super');
Route::get('/delete-attd/{id}', [App\Http\Controllers\AttendanceController::class, 'destroy'])->name('delete-attd')->middleware('role:Usher,Admin,Super');

// TRANSACTIONS
Route::get('/transactions', [App\Http\Controllers\TransactionsController::class, 'index'])->name('transactions')->middleware('role:Finance,Admin,Super');
Route::post('/addtransaction', [App\Http\Controllers\TransactionsController::class, 'store'])->name('addtransaction')->middleware('role:Finance,Admin,Super');
Route::get('/delete-trans/{id}', [App\Http\Controllers\TransactionsController::class, 'destroy'])->name('delete-trans')->middleware('role:Finance,Super');
Route::get('/myinvoices', [App\Http\Controllers\TransactionsController::class, 'myInvoices'])->name('transactions')->middleware('role:Customer,Supplier,Distributor,Drivers');


// PROGRAMMES
Route::get('/programmes', [App\Http\Controllers\ProgrammesController::class, 'index'])->name('programmes')->middleware('role:Admin,Super,Staff');
Route::post('/addprogramme', [App\Http\Controllers\ProgrammesController::class, 'store'])->name('addprogramme')->middleware('role:Admin,Super,Staff');
Route::get('/post/{id}', [App\Http\Controllers\ProgrammesController::class, 'post'])->name('post');
Route::get('/delete-prog/{id}', [App\Http\Controllers\ProgrammesController::class, 'destroy'])->name('delete-prog')->middleware('role:Admin,Super,Staff');

// COMMUNICATION
Route::get('/communications', [App\Http\Controllers\HomeController::class, 'communications'])->name('communications')->middleware('role:Admin,Super,Staff');
Route::post('/sendsms', [App\Http\Controllers\HomeController::class, 'sendSMS'])->name('sendsms')->middleware('role:Admin,Super,Staff');
Route::get('/sentmessages', [App\Http\Controllers\HomeController::class, 'sentSMS'])->name('sentmessages')->middleware('role:Admin,Super,Staff');

// ARTISAN COMMANDS
Route::get('/artisan1/{command}', [App\Http\Controllers\HomeController::class, 'Artisan1']);
Route::get('/artisan2/{command}/{param}', [App\Http\Controllers\HomeController::class, 'Artisan2']);
