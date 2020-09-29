<?php

// use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;

use function GuzzleHttp\Promise\all;

Route::get('auth/login', ['uses' => 'Auth\LoginController@showLoginForm']);
Route::post('auth/login', ['uses' => 'Auth\LoginController@Login']);
Route::get('/home', function(){
    return redirect()->route('home');
});
Route::group(['prefix' => 'proadmin', 'middleware' => ['auth', 'user.status']], function () {
    Route::group(['middleware' => 'officeUsers'], function () {// so other Users cant see web
        Route::get('/', ['as' => 'admin.home', 'uses' => 'Admin\AdminController@index']);
        Route::get('/manage', ['as' => 'manage', 'uses' => 'Admin\AdminController@manage']);
        Route::get('/manage/notification', ['as' => 'notificationManager', 'uses' => 'Admin\AdminController@notificationManager']);
        Route::post('/manage/notification', ['as' => 'notificationManagerPost', 'uses' => 'Admin\AdminController@notificationManagerPost']);
        Route::get('/program', ['as' => 'program', 'uses' => 'Admin\AdminController@program']);
        Route::get('/administration', ['as' => 'administration', 'uses' => 'Admin\AdminController@administration']);
        Route::get('/allowance', ['as' => 'allowance', 'uses' => 'Admin\AdminController@allowance']);
        Route::get('/capital', ['as' => 'capital', 'uses' => 'Admin\AdminController@capital']);
        Route::get('/misc', ['as' => 'misc', 'uses' => 'Admin\AdminController@misc']);
        Route::get('/report', ['as' => 'reports_print', 'uses' => 'Admin\AdminController@reports_print']);
        Route::get('/summary', ['as' => 'summary', 'uses' => 'Admin\AdminController@summaryReport']);
        Route::get('/moreThanFifteenCorerSummary', ['as' => 'moreThanFifteenCorerSummary', 'uses' => 'Admin\AdminController@moreThanFifteenCorerSummary']);
        Route::get('/divisionSummary', ['as' => 'divisionSummary', 'uses' => 'Admin\AdminController@divisionSummary']);
        Route::get('/logout', ['as' => 'logout', 'uses' => 'Admin\AdminController@logout']);
        Route::post('delete_mass', ['as' => 'delete_mass', 'uses' => 'Admin\AdminController@deleteMass']);
        Route::post('budget/guide', ['as' => 'change.budget.guide', 'uses' => 'Admin\AdminController@changeBudgetGuide']);
        Route::get('restore/{model}/{value}/{field?}', ['as' => 'restore', 'uses' => 'Admin\AdminController@restore']);
        Route::get('download/{filePath}/{fileName}', ['as' => 'download_file', 'uses' => 'Admin\AdminController@downloadFile']);
        Route::get('delete/{filePath}/{slug}/{fileName}', ['as' => 'delete_file', 'uses' => 'Admin\AdminController@deleteFile']);
        Route::get('set/fiscal-year', ['as' => 'set.fiscal.year', 'uses' => 'Admin\AdminController@set_fiscal_year']);
        Route::get('set/month-id', ['as' => 'set.month.id', 'uses' => 'Admin\AdminController@set_month_id']);
        Route::get('set/month-id-summary', ['as' => 'set.month.id.summary', 'uses' => 'Admin\AdminController@set_month_id_summary']);
        Route::get('set/budget-topic', ['as' => 'set.budget.topic', 'uses' => 'Admin\AdminController@set_budget_topic']);
        Route::get('set/expenditure-topic', ['as' => 'set.expenditure.topic', 'uses' => 'Admin\AdminController@set_expenditure_topic']);
        Route::get('set/implementing-office', ['as' => 'set.implementing.office', 'uses' => 'Admin\AdminController@set_implementing_office']);
        Route::get('set/last-amendment', ['as' => 'set.last.amendment', 'uses' => 'Admin\AdminController@set_last_amendment']);
        Route::get('set/division-office', ['as' => 'set.division.office', 'uses' => 'Admin\AdminController@set_division_office']);
        Route::get('set/destroy-session', ['as' => 'set.destroy.session', 'uses' => 'Admin\AdminController@set_destroy_session']);
        Route::get('set/destroy-session-summary', ['as' => 'set.destroy.session.summary', 'uses' => 'Admin\AdminController@set_destroy_session_summary']);




        Route::post('/implementingoffice/upload/log/{implementingoffice}', ['as' => 'implementingoffice.upload.log', 'uses' => 'Admin\ImplementingofficeController@uploadLog']);
        Route::post('/project/upload/log', ['as' => 'project.upload.log', 'uses' => 'Admin\ProjectController@uploadLog']);
        Route::post('/project/upload/{project_id}/apug_kagajat', ['as' => 'project.upload.apug_kagajat', 'uses' => 'Admin\ProjectController@uploadApugKagajat']);
        Route::get('/project/deleteMyadThapDocumentFile/{file}', ['as' => 'project.deleteMyadThapDocumentFile', 'uses' => 'Admin\ProjectController@deleteMyadThapDocumentFile']);
        Route::get('/project/updateMyadThapDocumentFile/{file}/{date}', ['as' => 'project.updateMyadThapDocumentFileDate', 'uses' => 'Admin\ProjectController@updateMyadThapDocumentFileDate']);
        Route::post('/project/upload/log/{block_id?}', ['as' => 'project.upload.log.block', 'uses' => 'Admin\ProjectController@uploadLog']);
        Route::post('/project/extension/time/{project}', ['as' => 'project.extension.time', 'uses' => 'Admin\ProjectController@extendTime']);
        Route::post('/project/variation/add/{project}', ['as' => 'project.variation.add', 'uses' => 'Admin\ProjectController@addVariation']);
        Route::post('/project/variation/make-letter/{project}', ['as' => 'project.vope_letter.add', 'uses' => 'Admin\ProjectController@printVopeLetter']);
        Route::post('/project/procurement_date/add/{project}', ['as' => 'project.procurement_date.add', 'uses' => 'Admin\ProjectController@createProcurementDate']);
        Route::post('/project/liquidation/add/{project}', ['as' => 'project.liquidation.add', 'uses' => 'Admin\ProjectController@addLiquidation']);
        Route::post('/project/authorized_person/add/{project}', ['as' => 'project.authorized_person.add', 'uses' => 'Admin\ProjectController@manageAuthorizedPerson']);
        Route::post('/project/contractor/{contractor}/authorized_person', ['as' => 'project.authorized_person.sync', 'uses' => 'Admin\ProjectController@manageAuthorizedPersonSync']);
        Route::post('/project/engineers/add/{project}', ['as' => 'project.engineers.add', 'uses' => 'Admin\ProjectController@manageEngineers']);
        Route::post('/project/daily-progress-user/add/{project}', ['as' => 'project.daily-progress.user.add', 'uses' => 'Admin\ProjectController@manageDailyProgressUser']);
        Route::post('/variation/update/', ['as' => 'update.old.variation', 'uses' => 'Admin\ProjectController@updateVariation']);
        Route::post('/time_extension/update/', ['as' => 'update.old.time_extension', 'uses' => 'Admin\ProjectController@updateExtension']);
        Route::post('/liquidation_damage/update/', ['as' => 'update.old.liquidation_damage', 'uses' => 'Admin\ProjectController@updateLiquidationDamage']);
        Route::post('/activity_log/update/', ['as' => 'update.old.activity_log', 'uses' => 'Admin\ProjectController@updateActivityLog']);
        Route::delete('/activity_log/destroy/{id}', ['as' => 'delete.old.activity_log', 'uses' => 'Admin\ProjectController@deleteActivityLog']);
        Route::delete('/activity_log/file/destroy/{id}', ['as' => 'delete.old.activity.log.file', 'uses' => 'Admin\ProjectController@deleteActivityLogFile']);
        Route::delete('/time_extension/delete/{id}', ['as' => 'delete.time_extension', 'uses' => 'Admin\ProjectController@deleteExtension']);
        Route::get('/release/implementingoffice/{implementingoffice}', ['as' => 'implementingoffice.release', 'uses' => 'Admin\ReleaseController@showOfficeRelease']);
        Route::get('/release/preview/{payment}', ['as' => 'release.preview', 'uses' => 'Admin\ReleaseController@preview']);
        Route::get('/release/cheque/{payment}', ['as' => 'release.cheque', 'uses' => 'Admin\ReleaseController@cheque']);
        Route::get('/release/implementingoffice/{implementingoffice}/create', ['as' => 'implementingoffice.release.create', 'uses' => 'Admin\ReleaseController@officeRelease']);
        Route::post('/release/implementingoffice/{implementingoffice}/post', ['as' => 'implementingoffice.release.post', 'uses' => 'Admin\ReleaseController@officeReleasePost']);
        //project-code-ajax
        //project status
        Route::put('/project/changestatus/{project}', ['as' => 'project.status.change', 'uses' => 'Admin\ProjectController@changeProjectStatus']);

        Route::post('/project/postImport', ['as' => 'project.postImport', 'uses' => 'Admin\ImportController@projectImport']);
        Route::post('/procurement/postImport', ['as' => 'procurement.postImport', 'uses' => 'Admin\ImportController@procurementImport']);
        Route::post('/progress/postImport', ['as' => 'progress.postImport', 'uses' => 'Admin\ImportController@progressImport']);
        Route::post('/allocation/postImport', ['as' => 'allocation.postImport', 'uses' => 'Admin\ImportController@allocationImport']);
        Route::post('/vope/postImport', ['as' => 'vope.postImport', 'uses' => 'Admin\ImportController@vopeImport']);
        Route::post('/time_extension/postImport', ['as' => 'time_extension.postImport', 'uses' => 'Admin\ImportController@time_extensionImport']);
        Route::post('/project_group/postImport', ['as' => 'group.postImport', 'uses' => 'Admin\ImportController@groupImport']);
        Route::post('/excel/file_modules', ['as' => 'excel.file_modules', 'uses' => 'Admin\ImportController@fileModules']);

        /*  Merge contractor*/
        Route::get('/contractor/merge', ['as' => 'contractor_merge', 'uses' => 'Admin\AdminController@mergeContractor']);
        Route::post('/contractor/merge', ['as' => 'contractor_merge', 'uses' => 'Admin\AdminController@postMergeContractor']);
        /*  Merge contractor*/

        /*  Log*/
        Route::get('/log', ['as' => 'allLog', 'uses' => 'Admin\AdminController@logAll']);
        Route::get('/log/search', ['as' => 'searchLogs', 'uses' => 'Admin\AdminController@searchLog']);
        Route::get('/log/{id}', ['as' => 'detailLog', 'uses' => 'Admin\AdminController@logDetail']);
        Route::get('/project/log/{id}', ['as' => 'projectLogs', 'uses' => 'Admin\AdminController@ProjectLogDetail']);
        Route::get('/projects/log', ['as' => 'recentProjectLogs', 'uses' => 'Admin\AdminController@projectsLog']);
        /*  Log*/

        Route::get('/backup/database/', ['as' => 'backupdatabase', 'uses' => 'Admin\Mysql@index']);
        Route::get('db/backup/', ['as' => 'backup_history', 'uses' => 'Admin\Mysql@backups']);
        Route::get('get/resources/{folder}/{name}', ['as' => 'get_backup_resources_download', 'uses' => 'Admin\Mysql@getBackup']);

        Route::get('notification/log', ['as' => 'get_notification_log', 'uses' => 'Admin\NotificaitonLogger@getLog']);
        Route::get('resource/pack', ['as' => 'public_folder_zip', 'uses' => 'Admin\Mysql@publicBackup']);


        Route::get('/project/search', ['as' => 'searchProject', 'uses' => 'Admin\ProjectController@search']);
        Route::post('/project/handover', ['as' => 'project.handover', 'uses' => 'Admin\ProjectController@handover']);
        Route::get('/procurement/search', ['as' => 'searchProcurement', 'uses' => 'Admin\ProcurementController@search']);
        Route::resource('document', 'Admin\DocumentController');
        Route::resource('web-message', 'Admin\WebMessageController');
        Route::post('web-message/send/{message}', ['as'=>'sync_web_message','uses'=>'Admin\WebMessageController@syncOffices']);

        //current progress
        Route::get('/current-progress', ['as' => 'current.progress', 'uses' => 'Admin\AdminController@currentProgress']);
        Route::post('/current-progress', ['as' => 'current.progress.store', 'uses' => 'Admin\AdminController@currentProgressStore']);

        //current progress ends

        Route::get('/imports', ['as' => 'imports', 'uses' => 'Admin\ImportController@index']);
        Route::resource('construction-located-area', 'Admin\ConstructionLocatedAreaController');
        Route::resource('region', 'Admin\RegionController');
        Route::resource('zone', 'Admin\ZoneController');
        Route::resource('state', 'Admin\StateController');
        Route::resource('district', 'Admin\DistrictController');
        Route::resource('expense', 'Admin\ExpenseController');
        Route::resource('income', 'Admin\IncomeController');
        Route::resource('division', 'Admin\DivisionController');
        Route::resource('address', 'Admin\AddressController');
        Route::resource('engineers', 'Admin\EngineersController');
        Route::resource('month', 'Admin\MonthController');
        Route::resource('fiscalyear', 'Admin\FiscalyearController');
        Route::resource('constructiontype', 'Admin\ConstructiontypeController');
        Route::resource('progresstrack', 'Admin\ProgresstrackController');
        Route::resource('budgettopic', 'Admin\BudgettopicController');
        Route::resource('expendituretopic', 'Admin\ExpendituretopicController');
        Route::resource('income-topic', 'Admin\IncomeTopicController');
        Route::resource('lumpsumbudget', 'Admin\LumpsumbudgetController');
        Route::resource('sector', 'Admin\SectorController');
        Route::resource('implementingoffice', 'Admin\ImplementingofficeController');
        Route::resource('implementingmode', 'Admin\ImplementingmodeController');

        Route::post('project/{project_id}/project-block','Admin\ProjectController@storeBlock')->name('project-block.store');
        Route::put('project/project-block/edit/{block_id}', 'Admin\ProjectController@blockUpdate')->name('project-block.update');
        Route::get('project/project-block/delete/{block_id}', 'Admin\ProjectController@blockDelete')->name('project-block.delete');
        Route::get('project/{project_id}/project-block/detail/{block_id}','Admin\ProjectController@blockDetail')->name('project-block.detail');
        Route::post('project/{project_id}/project-block/detail/{block_id}','Admin\ProjectController@blockDetailStore')->name('project-block.detail.store');
        Route::get('progresstrack-block/{project_id}', 'Admin\ProgresstrackBlockController@index')->name('progresstrack-block.index');
        Route::get('progresstrack-block/{project_id}/create', 'Admin\ProgresstrackBlockController@create')->name('progresstrack-block.create');
        Route::post('progresstrack-block/{project_id}/create', 'Admin\ProgresstrackBlockController@store')->name('progresstrack-block.store');
        Route::get('progresstrack-block/{project_id}/edit/{progressTrack}', 'Admin\ProgresstrackBlockController@edit')->name('progresstrack-block.edit');
        Route::put('progresstrack-block/{project_id}/edit/{progressTrack}', 'Admin\ProgresstrackBlockController@update')->name('progresstrack-block.update');
        Route::delete('progresstrack-block/{id}/delete', 'Admin\ProgresstrackBlockController@destroy')->name('progresstrack-block.destroy');

        Route::resource('project', 'Admin\ProjectController');
        Route::resource('project-group', 'Admin\ProjectGroupController');
        Route::resource('user', 'Admin\UserController');
        Route::resource('procurement', 'Admin\ProcurementController');
        Route::resource('allocation', 'Admin\AllocationController');
        Route::resource('progress', 'Admin\ProgressController');
        Route::resource('cheif', 'Admin\CheifController');
        Route::resource('authorized_person', 'Admin\AuthorizedPersonController');
        Route::resource('notice', 'Admin\NoticeController');
        Route::resource('procurement_date', 'Admin\ProcurementDateController');
        Route::get('/notice/listener/push', ['as' => 'notice_push_selector', 'uses' => 'Admin\NoticeController@pushNotificationSelector']);
        Route::post('/notice/listener/push', ['as' => 'notice_pushed', 'uses' => 'Admin\NoticeController@notificationPushed']);

        Route::resource('release', 'Admin\ReleaseController');
        Route::resource('release', 'Admin\ReleaseController');

        //
        Route::resource('vendor', 'Admin\VendorController');
        Route::resource('contractor', 'Admin\ContractorController');
        Route::resource('joint_venture', 'Admin\JointVentureController');

        //contract break and black list contractor
        Route::post('/project/cancel/{project}', ['as' => 'project.cancel', 'uses' => 'Admin\ProjectController@cancelProject']);

        //phpinfo test
        Route::get('phpinfo', function(){
            print_r(phpinfo());
        });

        Route::get('/project-setting', ['as' => 'project.setting', 'uses' => 'Admin\ProjectSettingController@index']);
        Route::get('/project-setting-design', ['as' => 'project.setting.design', 'uses' => 'Admin\ProjectSettingController@design']);

//        Route::delete('/manpower/{manpower}/destroy', ['as' => 'manpower.destroy', 'uses' => 'Admin\ManpowerController@manpowerDestroy']);
        Route::resource('manpower', 'Admin\ManpowerController');
        Route::resource('equipment', 'Admin\EquipmentController');
        Route::resource('material', 'Admin\MaterialController');
        Route::resource('work-activity', 'Admin\WorkActivityController');


        /*----------------Daily Progress------------------*/
        Route::get('/project/{project}/daily-progress', ['as' => 'daily.progress', 'uses' => 'Admin\DailyProgressController@create']);
        Route::post('/project/{project}/daily-progress', ['as' => 'daily.progress.store', 'uses' => 'Admin\DailyProgressController@store']);

        Route::get('/daily-progress/{dailyProgress}/edit', ['as' => 'daily.progress.edit', 'uses' => 'Admin\DailyProgressController@edit']);
        Route::post('/daily-progress/{dailyProgress}', ['as' => 'daily.progress.update', 'uses' => 'Admin\DailyProgressController@update']);

        Route::get('/report/{project}/daily-progress', ['as' => 'report.daily.progress', 'uses' => 'Admin\DailyProgressController@report']);

        /*----------------Daily Progress End------------------*/


        Route::post('/project/consultant-team/add/{project}', ['as' => 'project.consulatant-team.add', 'uses' => 'Admin\ProjectController@manageAuthorizedPerson']);
        Route::resource('consultant', 'Admin\ConsultantController');

        Route::get('/project/{project}/time-extension-format', ['as' => 'timeExtensionFormat', 'uses' => 'Admin\ProjectController@timeExtensionFormat']);
        Route::get('/profile/{user}/password-change', ['as' => 'user.password.change', 'uses' => 'Admin\UserController@passwordChange']);
        Route::put('/profile/{user}/password-change', ['as' => 'user.password.change.store', 'uses' => 'Admin\UserController@passwordChangeStore']);
        
    });
});
Route::get('/', ['as' => 'home', 'uses' => 'HomeController@index']);

Route::get('/maintenance/change_to_database', ['uses' => 'HomeController@changeToDatabase']);
Route::get('/auto_script', ['as' => 'auto_script', 'uses' => 'Admin\AutoScriptController@index']);
//Route::get('/testsms', ['as' => 'testsms', 'uses' => 'Admin\AutoScriptController@testsms']);
Route::group(['middleware'=>'api_request'],function(){
    Route::group(['middleware' => ['request.log']], function(){
        Route::post('/api/login', ['uses' => 'HomeController@apiLogin']);

        /* challenge api should be here */
        Route::get('/api/auth/dp', ['uses' => 'ApiController@dp']);//

    });
});
