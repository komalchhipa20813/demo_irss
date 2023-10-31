<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;

use App\Http\Controllers\{AgentController, DepartmentController, ChangePasswordController, DesignationController, PermissionController, RoleController, IrssBranchController, CompanyBranchController, DocumentTypeController, CompanyController, BranchImdNameController, BusinessCategoryController, CountryController, StateController, CityController, ProductController, SubProductController, UserController, FdoController, HealthPolicyController, CustomerController, ProductTypeController, PolicyNoFromPDFController, MakeController, ProductModelController, ProductVariantController, BankController, PublicHolidayController, LeaveApplicationController, MotorPolicyController, ProfileController, SmePolicyController, AllpolicylistController, GenerateOutwardController, NotPolicyPDFController, AgentreportsController, CancelPolicyController, CarnivalEventController, FdoReportsController, GeneratedOutwardController, GrossBusinessReportController,RaiseQueryController};
use App\Models\MotorPolicyVehical;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes  loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
* Auth Routes
*/

require __DIR__ . '/auth.php';
Route::group(['prefix' => 'carnival-event'], function () {
    Route::get('/', [CarnivalEventController::class, 'index']);
    Route::post('/data', [CarnivalEventController::class, 'data']);
});

Route::group(['prefix' => 'fdo-agent'], function () {
    Route::get('/login', [FdoController::class, 'login_create'])->name('fdo.agent.login');
    Route::post('/login', [FdoController::class, 'login_store'])->name('fdo.agent.signIn');
});
/* dashboard of fdo and agent */
Route::get('/', function () {
    return view('pages.fdo-agent-panel.dashboard');
})->name('fdo.agent.dashboard')->middleware('fdoAgentCheck');

Route::group(['prefix' => 'fdo-agent', 'middleware' => 'fdoAgentCheck'], function () {
    Route::get('/logout', [FdoController::class, 'fdo_agent_destroy'])->name('fdo.agent.logout');
    /* Profile Change Password */
    Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('fdo.agent.change-password.index');
    Route::get('/notification', [FdoController::class, 'notification'])->name('view.notification');
    Route::name('fdo-agent.')->group(function () {
        Route::resources([
            'fdo' => FdoController::class,
            'motor-policy' => MotorPolicyController::class,
            'health-policy' => HealthPolicyController::class,
            'sme-policy' => SmePolicyController::class,
            'agent' => AgentController::class,
        ]);
    });
    /* listing routes */
    Route::post('health-policy/listing', [HealthPolicyController::class, 'FodAgentListing']);
    Route::post('motor-policy/listing', [MotorPolicyController::class, 'FodAgentListing']);
    Route::post('sme-policy/listing', [SmePolicyController::class, 'FodAgentListing']);
});

/*
* After Login Route
*/
Route::post('/agent/listing', [AgentController::class, 'listing']);
/* download policy route */
Route::get('policy/download/{id}', [MotorPolicyController::class, 'policy_download'])->name('policy.download');

/* old password cheking */
Route::group(['prefix' => 'change-password'], function () {
    Route::post('/', [ChangePasswordController::class, 'update']);
    Route::post('/old-password-check', [ChangePasswordController::class, 'oldPasswordCheck']);
});


Route::group(['middleware' => 'auth'], function () {

    /* dashboard */
    Route::get('/employee-dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/upload', function () {
        Artisan::call('fdo:upload');
    });
    /* login route */
    Route::post('/login/password-check', [AuthenticatedSessionController::class, 'passwordCheck']);
    Route::get('update-policy/motor-policy-update', [MotorPolicyController::class, 'policy_update'])->name('update.policy');
    Route::get('update-policy/health-policy-update', [HealthPolicyController::class, 'policy_update'])->name('update.health_policy');
    Route::get('update-policy/sme-policy-update', [SmePolicyController::class, 'policy_update'])->name('update.sme_policy');
    Route::get('update-policy/list-all-policy', [AllpolicylistController::class, 'list_all'])->name('update.allpolicy');
    Route::get('not-uploaded-policy/motor', [NotPolicyPDFController::class, 'motorPolicyIndex'])->name('notUpload.motorploicy');
    Route::get('not-uploaded-policy/health', [NotPolicyPDFController::class, 'healthPolicyIndex'])->name('notUpload.healthploicy');
    Route::get('not-uploaded-policy/sme', [NotPolicyPDFController::class, 'smePolicyIndex'])->name('notUpload.smeploicy');
    Route::get('cancel-policy/motor', [CancelPolicyController::class, 'motorPolicyIndex'])->name('cancel-policy.motor');
    Route::get('cancel-policy/health', [CancelPolicyController::class, 'healthPolicyIndex'])->name('cancel-policy.health');
    Route::get('cancel-policy/sme', [CancelPolicyController::class, 'smePolicyIndex'])->name('cancel-policy.sme');
    Route::get('raise-query/solved-query', [RaiseQueryController::class, 'solvedIndex'])->name('solved.index');
    Route::get('motor-policy/download-pdf', [MotorPolicyController::class, 'download_pdf'])->name('motor-policy.download-pdf');
    /* download policy route */
    /* Resource Module */
    Route::resources([
        'permission' => PermissionController::class,
        'role' => RoleController::class,
        'retinue-branch' => IrssBranchController::class,
        'department' => DepartmentController::class,
        'designation' => DesignationController::class,
        'document-type' => DocumentTypeController::class,
        'company' => CompanyController::class,
        'company-branch' => CompanyBranchController::class,
        'branch-imd' => BranchImdNameController::class,
        'business-category' => BusinessCategoryController::class,
        'product' => ProductController::class,
        'country' => CountryController::class,
        'state' => StateController::class,
        'city' => CityController::class,
        'sub-product' => SubProductController::class,
        'public-holiday' => PublicHolidayController::class,
        'employee' => UserController::class,
        'leave-application' => LeaveApplicationController::class,
        'fdo' => FdoController::class,
        'motor-policy' => MotorPolicyController::class,
        'health-policy' => HealthPolicyController::class,
        'sme-policy' => SmePolicyController::class,
        'customer' => CustomerController::class,
        'make-product' => MakeController::class,
        'product-model' => ProductModelController::class,
        'product-variant' => ProductVariantController::class,
        'bank' => BankController::class,
        'product-type' => ProductTypeController::class,
        'pdf-read' => PolicyNoFromPDFController::class,
        'agent' => AgentController::class,
        'update-policy' => AllpolicylistController::class,
        'agent-details' => AgentreportsController::class,
        'fdo-details' => FdoReportsController::class,
        'gross-business' => GrossBusinessReportController::class,
        'raise-query' => RaiseQueryController::class,
    ]);

    /* Role & Permission */
    Route::group(['prefix' => 'permission'], function () {
        Route::post('/listing', [PermissionController::class, 'listing']);
        Route::post('/permission-check', [PermissionController::class, 'checkPermission']);
    });
    Route::group(['prefix' => 'role'], function () {
        Route::post('/listing', [RoleController::class, 'listing']);
        Route::post('/role-check', [RoleController::class, 'rolecheck']);
    });

    /* Profile Change Password */
    Route::group(['prefix' => 'change-password'], function () {
        Route::get('/', [ChangePasswordController::class, 'index'])->name('change-password.index');
    });

    //IRSS Branch Route
    Route::group(['prefix' => 'retinue-branch'], function () {
        Route::post('/listing', [IrssBranchController::class, 'listing']);
        Route::post('/branch-check', [IrssBranchController::class, 'checkBranch']);
        Route::post('/branch-rto-check', [IrssBranchController::class, 'checkBranchRTOCode']);
        Route::post('/branch-inward-check', [IrssBranchController::class, 'checkBranchInwardCode']);
        Route::post('/get-data', [IrssBranchController::class, 'get_data']);
    });
    /* Department Route */
    Route::group(['prefix' => 'department'], function () {
        Route::post('/check-department', [DepartmentController::class, 'checkDepartment']);
        Route::post('/listing', [DepartmentController::class, 'listing']);
    });
    // Designation Route
    Route::group(['prefix' => 'designation'], function () {
        Route::post('/listing', [DesignationController::class, 'listing']);
        Route::post('/check-designation', [DesignationController::class, 'checkDesignation']);
    });
    /* Document-type Route */
    Route::group(['prefix' => 'document-type'], function () {
        Route::post('/check-document-type', [DocumentTypeController::class, 'checkDocumenTtype']);
        Route::post('/listing', [DocumentTypeController::class, 'listing']);
    });
    //Company Route
    Route::group(['prefix' => 'company'], function () {
        Route::post('/listing', [CompanyController::class, 'listing']);
        Route::post('/company-name-check', [CompanyController::class, 'checkCompanyName']);
    });

    //Company Branch Route
    Route::group(['prefix' => 'company-branch'], function () {
        Route::post('/listing', [CompanyBranchController::class, 'listing']);
        Route::post('/company-branch-check', [CompanyBranchController::class, 'checkCompanyBranch']);
    });

    //Branch Imd Route
    Route::group(['prefix' => 'branch-imd'], function () {
        Route::post('/listing', [BranchImdNameController::class, 'listing']);
        Route::post('/branch-imd-check', [BranchImdNameController::class, 'branch_imd_check']);
        Route::post('/get-company-data', [BranchImdNameController::class, 'get_company_data']);
    });

    // Business-category Route
    Route::group(['prefix' => 'business-category'], function () {
        Route::post('/business-category-check', [BusinessCategoryController::class, 'business_category_check']);
        Route::post('/listing', [BusinessCategoryController::class, 'listing']);
    });
    /* Country Route */
    Route::group(['prefix' => 'country'], function () {
        Route::post('/listing', [CountryController::class, 'listing']);
        Route::post('/country-check', [CountryController::class, 'country_check']);
        Route::post('/get-state-name', [CountryController::class, 'get_state_name']);
    });

    /* State Route */
    Route::group(['prefix' => 'state'], function () {
        Route::post('/listing', [StateController::class, 'listing']);
        Route::post('/state-check', [StateController::class, 'state_check']);
        Route::post('/get-city-name', [StateController::class, 'get_city_name']);
    });

    /* City Route */
    Route::group(['prefix' => 'city'], function () {
        Route::post('/listing', [CityController::class, 'listing']);
        Route::post('/city-check', [CityController::class, 'city_check']);
        Route::post('/city-rto-check', [CityController::class, 'checkCityRTOCode']);
        Route::post('/get-rto-code-city', [CityController::class, 'getRTOCodeCity']);
        Route::post('/rto-code-patten-check', [CityController::class, 'check_rtoCode_pattern']);
    });

    /* Product Route */
    Route::group(['prefix' => 'product'], function () {
        Route::post('/product-check', [ProductController::class, 'product_check']);
        Route::post('/listing', [ProductController::class, 'listing']);
        Route::post('/get-data', [ProductController::class, 'get_data']);
    });

    /* Sub-products Route */
    Route::group(['prefix' => 'sub-product'], function () {
        Route::post('/check-sub-product', [SubProductController::class, 'check_sub_product']);
        Route::post('/listing', [SubProductController::class, 'listing']);
        Route::post('/get-product-name', [SubProductController::class, 'get_product_name']);
    });

    /* product-type Route */
    Route::group(['prefix' => 'product-type'], function () {
        Route::post('/check-product-type', [ProductTypeController::class, 'check_product_type']);
        Route::post('/listing', [ProductTypeController::class, 'listing']);
        Route::post('/get-product-name', [ProductTypeController::class, 'get_product_name']);
    });

    /* Employee Route */
    Route::group(['prefix' => 'employee'], function () {
        Route::post('/employee-check', [UserController::class, 'employee_check']);
        Route::post('/code-check', [UserController::class, 'code_check']);
        Route::post('/listing', [UserController::class, 'listing']);
        Route::post('/change-password',[UserController::class,'changePassword']);
    });

    /* public Holiday Route */
    Route::group(['prefix' => 'public-holiday'], function () {
        Route::post('/listing', [PublicHolidayController::class, 'listing']);
        Route::post('/holiday-check', [PublicHolidayController::class, 'holiday_check']);
        Route::post('/get-status', [PublicHolidayController::class, 'ajaxchangestatus']);
        Route::post('/generate-pdf', [PublicHolidayController::class, 'generate_pdf']);
    });


    /* public Leave Route */
    Route::group(['prefix' => 'leave-application'], function () {
        Route::post('/listing', [LeaveApplicationController::class, 'listing']);
        Route::post('/change-status-leave', [LeaveApplicationController::class, 'ajaxchangestatus']);
    });

    /* FDO Route */
    Route::group(['prefix' => 'fdo'], function () {
        Route::post('/fdo-check', [FdoController::class, 'fdo_check']);
        Route::post('/fdo-code', [FdoController::class, 'fdo_code']);
        Route::post('/listing', [FdoController::class, 'listing']);
        Route::post('/document/listing', [FdoController::class, 'document_listing']);
        Route::post('/document', [FdoController::class, 'fdo_document']);
        Route::post('/document/delete/{id}', [FdoController::class, 'fdo_delete'])->name('fdo_document.delete');
        Route::post('/adharcard_number', [FdoController::class, 'adharcard_number']);
        Route::post('/pancard_number', [FdoController::class, 'pancard_number']);
        Route::post('/get-data', [FdoController::class, 'get_data']);
        Route::post('/change-password',[FdoController::class,'changePassword']);
    });

    /* Customer Route */
    Route::group(['prefix' => 'customer'], function () {
        Route::post('/customer-check', [CustomerController::class, 'customer_check']);
        Route::post('listing', [CustomerController::class, 'listing']);
        Route::post('/customer-pan-card-check', [CustomerController::class, 'customer_pan_card_check']);
        Route::post('/get-data', [CustomerController::class, 'get_data']);
        Route::post('/get-customer', [CustomerController::class, 'get_customer']);
        Route::post('/get-customers', [CustomerController::class, 'get_customers']);
    });

    /* Make Route */
    Route::group(['prefix' => 'make-product'], function () {
        Route::post('/listing', [MakeController::class, 'listing']);
        Route::post('/make-check', [MakeController::class, 'make_check']);
        Route::post('/get-make-name', [MakeController::class, 'get_make_name']);
    });

    /* Model Route */
    Route::group(['prefix' => 'product-model'], function () {
        Route::post('/listing', [ProductModelController::class, 'listing']);
        Route::post('/model-check', [ProductModelController::class, 'model_check']);
        Route::post('/get-model-name', [ProductModelController::class, 'get_model_name']);
    });

    /* variant Route */
    Route::group(['prefix' => 'product-variant'], function () {
        Route::post('/listing', [ProductVariantController::class, 'listing']);
        Route::post('/variant-check', [ProductVariantController::class, 'variant_check']);
        Route::post('/get-variant-name', [ProductVariantController::class, 'get_variant_name']);
    });

    /* Bank Route */
    Route::group(['prefix' => 'bank'], function () {
        Route::post('/listing', [BankController::class, 'listing']);
        Route::post('/data', [BankController::class, 'data']);
        Route::post('/bank-check', [BankController::class, 'bank_check']);
    });

    /* Update all policy */
    Route::group(['prefix' => 'update-policy'], function () {
        Route::post('/health/update-listing', [HealthPolicyController::class, 'update_policy_listing']);
        Route::post('/motor/update-listing', [MotorPolicyController::class, 'update_policy_listing']);
        Route::post('/sme/update-listing', [SmePolicyController::class, 'update_policy_listing']);
        Route::post('/update-listing', [AllpolicylistController::class, 'listing']);
        Route::post('/store-data', [AllpolicylistController::class, 'update_policy']);
        Route::post('/export-motor-data', [MotorPolicyController::class, 'update_policy_export']);
        Route::post('/export-health-data', [HealthPolicyController::class, 'update_policy_export']);
        Route::post('/export-sme-data', [SmePolicyController::class, 'update_policy_export']);
        Route::post('/export-all-data', [AllpolicylistController::class, 'update_policy_export']);
    });

    // Not uploade PDF
    Route::group(['prefix' => 'not-uploaded-policy'], function () {
        Route::post('/motor', [NotPolicyPDFController::class, 'motorPolicyListing']);
        Route::post('/health', [NotPolicyPDFController::class, 'healthPolicyListing']);
        Route::post('/sme', [NotPolicyPDFController::class, 'smePolicyListing']);
        Route::post('/export-motor-data', [NotPolicyPDFController::class, 'export_notPDF_motor_policy']);
        Route::post('/export-health-data', [NotPolicyPDFController::class, 'export_notPDF_health_policy']);
        Route::post('/export-sme-data', [NotPolicyPDFController::class, 'export_notPDF_sme_policy']);
    });

    // cancel policy
    Route::group(['prefix' => 'cancel-policy'], function () {
        Route::post('/motor', [CancelPolicyController::class, 'motorPolicyListing']);
        Route::post('/health', [CancelPolicyController::class, 'healthPolicyListing']);
        Route::post('/sme', [CancelPolicyController::class, 'smePolicyListing']);
        Route::post('/export-motor-data', [CancelPolicyController::class, 'export_motor_policy']);
        Route::post('/export-health-data', [CancelPolicyController::class, 'export_health_policy']);
        Route::post('/export-sme-data', [CancelPolicyController::class, 'export_sme_policy']);
    });

    /* generate outwards */
    Route::group(['prefix' => 'generate-outward'], function () {
        Route::get('/', [GenerateOutwardController::class, 'index'])->name('generate-outward.index');
        Route::post('/listing', [GenerateOutwardController::class, 'listing']);
        Route::post('/pdf', [GenerateOutwardController::class, 'pdf']);
    });

    /* generated outwards */
    Route::group(['prefix' => 'generated-outward'], function () {
        Route::get('/', [GeneratedOutwardController::class, 'index'])->name('generated-outward.index');
        Route::post('/listing', [GeneratedOutwardController::class, 'listing']);
        Route::post('/{id}', [GeneratedOutwardController::class, 'upload']);
    });

    /* Health Policy Route */
    Route::group(['prefix' => 'health-policy'], function () {
        Route::post('/listing', [HealthPolicyController::class, 'listing']);
        Route::post('/policy-number', [HealthPolicyController::class, 'policy_number_check']);
        Route::post('/get-payment-data', [HealthPolicyController::class, 'get_payment_data']);
        Route::post('/get-member-data/{id}', [HealthPolicyController::class, 'get_member_data']);
        Route::post('/add-member-listing', [HealthPolicyController::class, 'add_member_listing']);
        Route::post('/store-member', [HealthPolicyController::class, 'store_member']);
        Route::post('/delete/{id}', [HealthPolicyController::class, 'delete']);
        Route::post('/cancel', [HealthPolicyController::class, 'cancel']);
        Route::post('/export-data', [HealthPolicyController::class, 'export']);
    });

    /* Motor Policy Route */
    Route::group(['prefix' => 'motor-policy'], function () {
        Route::post('/listing', [MotorPolicyController::class, 'listing']);
        Route::post('/policy-number', [MotorPolicyController::class, 'policy_number_check']);
        Route::post('/get-payment-data', [MotorPolicyController::class, 'get_payment_data']);
        Route::post('/policy-check', [MotorPolicyController::class, 'policy_check']);
        Route::post('/cancel', [MotorPolicyController::class, 'cancel']);
        Route::post('/export-data', [MotorPolicyController::class, 'export']);
      
    });

    /*SME Policy Route */
    Route::group(['prefix' => 'sme-policy'], function () {
        Route::post('/listing', [SmePolicyController::class, 'listing']);
        Route::post('/policy-number', [SmePolicyController::class, 'policy_number_check']);
        Route::post('/co-sharing-detail', [SmePolicyController::class, 'co_sharing_data']);
        Route::post('/co-sharing/listing', [SmePolicyController::class, 'co_sharing_listing']);
        Route::post('/co-sharing/delete/{id}', [SmePolicyController::class, 'co_sharing_delete'])->name('co_sharing_data.delete');
        Route::post('/get-payment-data', [SmePolicyController::class, 'get_payment_data']);
        Route::post('/cancel', [SmePolicyController::class, 'cancel']);
        Route::post('/export-data', [SmePolicyController::class, 'export']);
    });

    /* Agent Route */
    Route::group(['prefix' => 'agent'], function () {
        Route::post('/agent-check', [AgentController::class, 'agent_check']);
        Route::post('/document/listing', [AgentController::class, 'document_listing']);
        Route::post('/document', [AgentController::class, 'agent_document']);
        Route::post('/document/delete/{id}', [AgentController::class, 'agent_delete'])->name('agent_document.delete');
        Route::post('/adharcard_number', [AgentController::class, 'adharcard_number']);
        Route::post('/pancard_number', [AgentController::class, 'pancard_number']);
        Route::post('/change-password',[AgentController::class,'changePassword']);
    });

    // Agent Reports Route
    Route::group(['prefix' => 'agent-details'], function () {
        Route::post('/agents-export', [AgentreportsController::class, 'export_agent_detail']);
    });

    // FDO Reports Route
    Route::group(['prefix' => 'fdo-details'], function () {
        Route::post('/fdos-export', [FdoReportsController::class, 'export_fdo_detail']);
    });

    // GB Reports Route
    Route::group(['prefix' => 'gross-business'], function () {
        Route::post('/gbr-export', [GrossBusinessReportController::class, 'export_gbr']);
    });

     /*Raise Qury Route */
     Route::group(['prefix' => 'raise-query'], function () {
        Route::post('/listing', [RaiseQueryController::class, 'listing']);
        Route::post('/get-policy-data', [RaiseQueryController::class, 'getPolicyData']);
        Route::post('/solved-listing', [RaiseQueryController::class, 'listingOfSolvedQuery']);
        Route::post('/change-status', [RaiseQueryController::class, 'statusUpdate']);
        Route::post('/export-raise-query', [RaiseQueryController::class, 'exportRaiseQuery']);
        Route::post('/export-solved-query', [RaiseQueryController::class, 'exportSolvedQuery']);
       
    });



    /* Profile Module */
    Route::get('/profile', [ProfileController::class, 'viewProfile'])->name('user.profile');
    Route::post('/updateProfile', [ProfileController::class, 'updateProfile']);
});
/* sync routes */
Route::post('sync', [MotorPolicyController::class, 'sync']);
/* upload policy route */
Route::post('policy-copy/upload', [MotorPolicyController::class, 'upload']);
Route::get('/agents-export', [AgentController::class, 'export'])->name('agents.export');
Route::get('/export-fdo', [FdoController::class, 'export'])->name('export-fdo');
Route::get('/export-customer', [CustomerController::class, 'export'])->name('export-customer');
Route::get('/export-motor-policy', [MotorPolicyController::class, 'export'])->name('export-motor-policy');
Route::get('/export-health-policy', [HealthPolicyController::class, 'export'])->name('export-health-policy');
Route::get('/export-sme-policy', [SmePolicyController::class, 'export'])->name('export-sme-policy');
Route::get('/export-employee', [UserController::class, 'export'])->name('export-employee');

/* Route For Redirecting While 404 */
Route::any('{url}', function () {
    return redirect('/');
})->where('url', '.*');
