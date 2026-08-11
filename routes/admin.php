<?php

use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\admin\ProductsController;
use App\Http\Controllers\admin\WebsiteController;
use App\Http\Controllers\admin\TemplateController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\RoleController;
use App\Http\Controllers\admin\ModulController;
use App\Http\Controllers\admin\ModulAksesController;
use App\Http\Controllers\admin\DepartemenController;
use App\Http\Controllers\admin\StaffController;
use App\Http\Controllers\admin\LeadsController;
use App\Http\Controllers\admin\CustomersController;
use App\Http\Controllers\admin\CustomersWebController;
use App\Http\Controllers\admin\LeadsTrackerController;

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::redirect('/', '/admin/dashboard')->middleware('auth');

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {


    Route::get('products', [ProductsController::class, 'index'])->name('products');

    Route::get('template', [TemplateController::class, 'index'])->name('template');
    Route::get('template/create', [TemplateController::class, 'create'])->name('template.create');
    Route::post('template', [TemplateController::class, 'store'])->name('template.store');
    Route::get('template/{id}/edit', [TemplateController::class, 'edit'])->name('template.edit');
    Route::get('template/{id}/section', [TemplateController::class, 'section'])->name('template.section');
    Route::post('template/{id}/section', [TemplateController::class, 'sectionStore'])->name('template.section.store');
    Route::put('template/{id}/section/{sectionId}', [TemplateController::class, 'sectionUpdate'])->name('template.section.update');
    Route::delete('template/{id}/section/{sectionId}', [TemplateController::class, 'sectionDestroy'])->name('template.section.destroy');
    Route::delete('template/{id}/section-content/{contentId}', [TemplateController::class, 'sectionContentDestroy'])->name('template.section.content.destroy');
    Route::put('template/{id}', [TemplateController::class, 'update'])->name('template.update');
    Route::delete('template/{id}', [TemplateController::class, 'destroy'])->name('template.destroy');

    Route::get('website', [WebsiteController::class, 'index'])->name('website');

    Route::get('customers-website', [CustomersWebController::class, 'index'])->name('customers-website');
    Route::get('customers-website/create', [CustomersWebController::class, 'create'])->name('customers-website.create');
    Route::post('customers-website', [CustomersWebController::class, 'store'])->name('customers-website.store');
    Route::get('customers-website/{id}/edit', [CustomersWebController::class, 'edit'])->name('customers-website.edit');
    Route::put('customers-website/{id}', [CustomersWebController::class, 'update'])->name('customers-website.update');
    Route::delete('customers-website/{id}', [CustomersWebController::class, 'destroy'])->name('customers-website.destroy');
    Route::get('customers-website/{id}/page', [CustomersWebController::class, 'page'])->name('customers-website.page');
    Route::get('customers-website/{id}/layout/{page_type}', [CustomersWebController::class, 'layout'])->name('customers-website.layout');
    Route::post('customers-website/{id}/layout/{page_type}', [CustomersWebController::class, 'layoutStore'])->name('customers-website.layout.store');
    Route::put('customers-website/{id}/layout/{page_type}/{layoutId}', [CustomersWebController::class, 'layoutUpdate'])->name('customers-website.layout.update');
    Route::delete('customers-website/{id}/layout/{page_type}/{layoutId}', [CustomersWebController::class, 'layoutDestroy'])->name('customers-website.layout.destroy');

    //Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Customers
    Route::get('/customers', [CustomersController::class, 'index'])->name('customers');
    Route::get('/customers/get-data', [CustomersController::class, 'getData'])->name('customers.getData');
    Route::get('/customers/get-dataRecycle', [CustomersController::class, 'getDataRecy cle'])->name('customers.getDataRecycle');
    Route::get('/customers/create', [CustomersController::class, 'create'])->name('customers.create');
    Route::get('/customers/recycle', [CustomersController::class, 'recycle'])->name('customers.recycle');
    Route::post('/customers/restore/{id}', [CustomersController::class, 'restore'])->name('customers.restore');
    Route::post('/customers', [CustomersController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}/edit', [CustomersController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomersController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomersController::class, 'destroy'])->name('customers.destroy');
    Route::get('/customers/get-cities/{province_name}', [CustomersController::class, 'getCities'])->name('customers.getCities');

    Route::get('/customers/weblist', [CustomersController::class, 'weblist'])->name('customers.weblist');

    //User
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/users/recycle', [UserController::class, 'recycle'])->name('users.recycle');
    Route::post('/users/restore/{id}', [UserController::class, 'restore'])->name('users.restore');
    Route::get('/users/get-data', [UserController::class, 'getData'])->name('users.getData');
    Route::get('/users/get-dataRecycle', [UserController::class, 'getDataRecycle'])->name('users.getDataRecycle');
    Route::get('/users/datatable', [UserController::class, 'datatable'])->name('users.datatable');
    Route::delete('/users/{modul}', [UserController::class, 'destroy'])->name('modul.destroy');
    // Route::post('/users', [UserController::class, 'store']);

    //Role
    Route::get('/role', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/role/get-data', [RoleController::class, 'getData'])->name('roles.getData');
    Route::get('/role/get-dataRecycle', [RoleController::class, 'getDataRecycle'])->name('roles.getDataRecycle');
    Route::get('/role/create', [RoleController::class, 'create'])->name('roles.create');
    Route::get('/role/recycle', [RoleController::class, 'recycle'])->name('roles.recycle');
    Route::post('/roles/restore/{id}', [RoleController::class, 'restore'])->name('roles.restore');
    Route::post('/role', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/role/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/role/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    Route::get('/role/datatable', [RoleController::class, 'datatable'])->name('roles.datatable');


    //Modul
    Route::get('/modul', [ModulController::class, 'index'])->name('modul.index');
    Route::get('/modul/get-data', [ModulController::class, 'getData'])->name('modul.getData');
    Route::get('/modul/get-dataRecycle', [ModulController::class, 'getDataRecycle'])->name('modul.getDataRecycle');
    Route::get('/modul/create', [ModulController::class, 'create'])->name('modul.create');
    Route::get('/modul/recycle', [ModulController::class, 'recycle'])->name('modul.recycle');
    Route::post('/modul-store', [ModulController::class, 'store'])->name('modul.store');
    Route::get('/modul/{modul_data}/edit', [ModulController::class, 'edit'])->name('modul.edit');
    Route::put('/modul/{modul}', [ModulController::class, 'update'])->name('modul.update');
    Route::delete('/modul/{modul}', [ModulController::class, 'destroy'])->name('modul.destroy');
    // Route::put('/modul/restore/{id}', [ModulController::class, 'restore'])->name('modul.restore');
    Route::post('/modul/restore/{id}', [ModulController::class, 'restore'])->name('modul.restore');

    //Departemen
    Route::get('/departemen', [DepartemenController::class, 'index'])->name('departemen.index');
    Route::get('/departemen/get-data', [DepartemenController::class, 'getData'])->name('departemen.getData');
    Route::get('/departemen/get-dataRecycle', [DepartemenController::class, 'getDataRecycle'])->name('departemen.getDataRecycle');
    Route::get('/departemen/create', [DepartemenController::class, 'create'])->name('departemen.create');
    Route::get('/departemen/recycle', [DepartemenController::class, 'recycle'])->name('departemen.recycle');
    Route::post('/departemen/restore/{id}', [DepartemenController::class, 'restore'])->name('departemen.restore');
    Route::post('/departemen', [DepartemenController::class, 'store'])->name('departemen.store');
    Route::get('/departemen/{departemen}/edit', [DepartemenController::class, 'edit'])->name('departemen.edit');
    Route::put('/departemen/{departemen}', [DepartemenController::class, 'update'])->name('departemen.update');
    Route::delete('/departemen/{departemen}', [DepartemenController::class, 'destroy'])->name('departemen.destroy');
    Route::get('/departemen/datatable', [DepartemenController::class, 'datatable'])->name('departemen.datatable');

    //Staff
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/get-data', [StaffController::class, 'getData'])->name('staff.getData');
    Route::get('/staff/get-dataRecycle', [StaffController::class, 'getDataRecycle'])->name('staff.getDataRecycle');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::get('/staff/recycle', [StaffController::class, 'recycle'])->name('staff.recycle');
    Route::post('/staff/restore/{id}', [StaffController::class, 'restore'])->name('staff.restore');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit');
    Route::put('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::get('/staff/datatable', [StaffController::class, 'datatable'])->name('staff.datatable');

    //Leads
    Route::get('/leads', [LeadsController::class, 'index'])->name('leads.index');
    Route::get('/leads/get-data', [LeadsController::class, 'getData'])->name('leads.getData');
    Route::get('/leads/get-dataRecycle', [LeadsController::class, 'getDataRecycle'])->name('leads.getDataRecycle');
    Route::get('/leads/create', [LeadsController::class, 'create'])->name('leads.create');
    Route::get('/leads/recycle', [LeadsController::class, 'recycle'])->name('leads.recycle');
    Route::post('/leads/restore/{id}', [LeadsController::class, 'restore'])->name('leads.restore');
    Route::post('/leads', [LeadsController::class, 'store'])->name('leads.store');
    Route::get('/leads/{lead}/edit', [LeadsController::class, 'edit'])->name('leads.edit');
    Route::put('/leads/{lead}', [LeadsController::class, 'update'])->name('leads.update');
    Route::delete('/leads/{lead}', [LeadsController::class, 'destroy'])->name('leads.destroy');
    Route::post('/leads/add-to-customer/{id}', [LeadsController::class, 'addToCustomer'])->name('leads.addToCustomer');
    Route::get('/leads/get-cities/{province_name}', [LeadsController::class, 'getCities'])->name('leads.getCities');


    //Leads Tracker
    Route::get('/leads-tracker', [LeadsTrackerController::class, 'index'])->name('leads-tracker.index');
    Route::get('/leads-tracker/get-data', [LeadsTrackerController::class, 'getData'])->name('leads-tracker.getData');
    Route::get('/leads-tracker/overview/get-data', [LeadsTrackerController::class, 'getOverviewData'])->name('leads-tracker.overview.getData');

    // Leads Tracker - Targeting CRUD (AJAX)
    Route::get('/leads-tracker/targeting/get-data', [LeadsTrackerController::class, 'getTargetingData'])->name('leads-tracker.targeting.getData');
    Route::post('/leads-tracker/targeting', [LeadsTrackerController::class, 'targetingStore'])->name('leads-tracker.targeting.store');
    Route::put('/leads-tracker/targeting/{id}', [LeadsTrackerController::class, 'targetingUpdate'])->name('leads-tracker.targeting.update');
    Route::delete('/leads-tracker/targeting/{id}', [LeadsTrackerController::class, 'targetingDestroy'])->name('leads-tracker.targeting.destroy');

    // Leads Tracker - Campaign CRUD (AJAX)
    Route::get('/leads-tracker/campaign/get-data', [LeadsTrackerController::class, 'getCampaignData'])->name('leads-tracker.campaign.getData');
    Route::post('/leads-tracker/campaign', [LeadsTrackerController::class, 'campaignStore'])->name('leads-tracker.campaign.store');
    Route::put('/leads-tracker/campaign/{id}', [LeadsTrackerController::class, 'campaignUpdate'])->name('leads-tracker.campaign.update');
    Route::delete('/leads-tracker/campaign/{id}', [LeadsTrackerController::class, 'campaignDestroy'])->name('leads-tracker.campaign.destroy');

    // Leads Tracker - Adset CRUD (AJAX)
    Route::get('/leads-tracker/adset/get-data', [LeadsTrackerController::class, 'getAdsetData'])->name('leads-tracker.adset.getData');
    Route::post('/leads-tracker/adset', [LeadsTrackerController::class, 'adsetStore'])->name('leads-tracker.adset.store');
    Route::put('/leads-tracker/adset/{id}', [LeadsTrackerController::class, 'adsetUpdate'])->name('leads-tracker.adset.update');
    Route::delete('/leads-tracker/adset/{id}', [LeadsTrackerController::class, 'adsetDestroy'])->name('leads-tracker.adset.destroy');

    // Leads Tracker - Creative (Ads) CRUD (AJAX)
    Route::get('/leads-tracker/creative/get-data', [LeadsTrackerController::class, 'getCreativeData'])->name('leads-tracker.creative.getData');
    Route::post('/leads-tracker/creative', [LeadsTrackerController::class, 'creativeStore'])->name('leads-tracker.creative.store');
    Route::put('/leads-tracker/creative/{id}', [LeadsTrackerController::class, 'creativeUpdate'])->name('leads-tracker.creative.update');
    Route::patch('/leads-tracker/creative/{id}/spend', [LeadsTrackerController::class, 'creativeUpdateSpend'])->name('leads-tracker.creative.updateSpend');
    Route::delete('/leads-tracker/creative/{id}', [LeadsTrackerController::class, 'creativeDestroy'])->name('leads-tracker.creative.destroy');

    // Leads Tracker - LT Leads CRUD (AJAX)
    Route::get('/leads-tracker/lt-lead/get-data', [LeadsTrackerController::class, 'getLtLeadData'])->name('leads-tracker.lt-lead.getData');
    Route::post('/leads-tracker/lt-lead', [LeadsTrackerController::class, 'ltLeadStore'])->name('leads-tracker.lt-lead.store');
    Route::put('/leads-tracker/lt-lead/{id}', [LeadsTrackerController::class, 'ltLeadUpdate'])->name('leads-tracker.lt-lead.update');
    Route::patch('/leads-tracker/lt-lead/{id}/status', [LeadsTrackerController::class, 'ltLeadUpdateStatus'])->name('leads-tracker.lt-lead.updateStatus');
    Route::delete('/leads-tracker/lt-lead/{id}', [LeadsTrackerController::class, 'ltLeadDestroy'])->name('leads-tracker.lt-lead.destroy');

});

