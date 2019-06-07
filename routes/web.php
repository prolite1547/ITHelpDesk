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


use App\ConnectionIssueReply;
use App\Ticket;
use Illuminate\Support\Facades\Mail;
use Webklex\IMAP\Client;


Route::get('/', 'PublicController@login')->name('index');
Route::get('/dashboard', 'PublicController@dashboard')->name('dashboard');
Route::get('/store-operations', 'PublicController@storeOperations')->name('storeOperations');


//////////////////////////
////////*USER*///////////
//////////////////////////
Route::get('/user/profile/{id}', 'UserController@profile')->name('userProfile');
Route::post('/image', 'UserController@changeProf')->name('changeProf');
Route::post('/user/add', 'UserController@create');

//////////////////////////
////////*TICKETS*/////////
//////////////////////////

Route::get('/ticket/incomplete/{id}', 'TicketController@incompleteTicket')->name('incompleteTicket');
Route::get('/ticket/{id}', 'TicketController@getTicket');
Route::get('/tickets/add', 'TicketController@addTicketView')->name('addTicketView');
Route::post('/ticket/add', 'TicketController@addTicket')->name('addTicket');
Route::patch('/ticket/add/details', 'TicketController@addTicketDetails')->name('addTicketDetails');
Route::post('/ticket/pldt/add', 'TicketController@addConnectionIssue')->name('addConnectionIssue');
Route::get('/tickets/view/{id}', 'TicketController@lookupView')->name('lookupTicketView');
Route::patch('/ticket/edit/{id}', 'TicketController@edit')->name('editTicket');
Route::patch('/tickets/status/fixed/{id}', 'TicketController@editStatus')->name('editStatus');
Route::get('/tickets/open', 'TicketController@open')->name('openTickets');
Route::get('/tickets/ongoing', 'TicketController@ongoing')->name('ongoingTickets');
Route::get('/tickets/expired', 'TicketController@expired')->name('expiredTickets');
Route::get('/tickets/closed', 'TicketController@closed')->name('closedTickets');
Route::get('/tickets/fixed', 'TicketController@fixed')->name('fixedTickets');
Route::get('/tickets/all', 'TicketController@all')->name('allTickets');
Route::get('/tickets/verification', 'TicketController@forVerifcation')->name('verificationTickets');
Route::get('/tickets/closed', 'TicketController@closed')->name('closedTickets');
Route::get('/tickets/my', 'TicketController@userTickets')->name('myTickets');
Route::get('/tickets/all', 'TicketController@all')->name('allTickets');
Route::delete('/ticket/delete/{id}', 'TicketController@delete')->name('ticketDelete');
Route::post('/ticket/reject/{id}', 'TicketController@reject')->name('ticketReject');
Route::post('/ticket/extend/{id}', 'TicketController@extend')->name('ticketExtend');
Route::get('/modal/form/ticketExtendDetails/{id}', 'TicketController@ticketExtendDetails')->name('ticketExtendDetails');
Route::post('/ticket/resolve/{id}', 'TicketController@resolve');

//////////////////////////
////////*FIX*/////////
//////////////////////////
Route::post('/ticket/{id}/fix/create', 'FixController@create')->name('addFix');

//////////////////////////
////////*FILE*////////////
//////////////////////////
Route::get('/file/download/{id}', 'FileController@download')->name('fileDownload');
Route::post('/file/ticket/{id}', 'TicketController@addFile');
//////////////////////////
////////*MODAL*////////////
//////////////////////////
//Route::get('/modal/ticketEdit/{id}','StoreController@contacts')->name('modalTicketEdit');
Route::get('/modal/ticketEdit/{id}', 'TicketController@editModal')->name('modalTicketEdit');
Route::get('/modal/form/reject/{ticket_id}', 'TicketController@rejectForm');
Route::get('/modal/lookup/reject/{ticket_id}', 'TicketController@rejectFormDetails');
Route::view('/modal/form/fix', 'modal.fix_form');
Route::view('/modal/form/userAdd', 'modal.user_add');
Route::get('/modal/form/extend/{id}', 'TicketController@getExtendForm');
Route::get('/modal/form/fix/{id}', 'FixController@show')->name('modalFixView');
Route::get('/modal/{store_id}/contacts', 'StoreController@storeContacts')->name('storeContacts');
Route::view('/modal/form/target', 'modal.target_form');
Route::view('/modal/form/visit-details', 'modal.visit_details_form');
Route::get('/modal/form/editVisitTarget/{id}','StoreVisitController@editTargetModal');
Route::get('/modal/form/editVisitDetails/{id}','StoreVisitController@editDetailsModal');
//////////////////////////
////////*MESSAGE*/////////
//////////////////////////
Route::post('/message/new', 'MessageController@create');
Route::delete('/message/delete/{id}', 'MessageController@destroy');

//////////////////////////
////////*CALLER*/////////
//////////////////////////
Route::post('/caller/save', 'CallerController@create');

//////////////////////////
////////*STORE*/////////
//////////////////////////
Route::post('/store/save', 'StoreController@create');

//////////////////////////
////////*CONTACT*/////////
//////////////////////////
Route::post('/contact/save', 'ContactController@create');

//////////////////////////
////////*ADMIN*/////////
//////////////////////////
Route::get('/admin', 'AdminController@index')->name('adminPage');

//////////////////////////
////////*SELECT*/////////
//////////////////////////
Route::get('/select/store', 'SelectController@branch');
Route::get('/select/caller', 'SelectController@caller');
Route::get('/select/contact', 'SelectController@contact');
Route::get('/select/position', 'SelectController@position');
Route::get('/select/department', 'SelectController@department');
Route::get('/select/expiration', 'SelectController@expiration');
Route::get('/select/users', 'SelectController@users');
Route::get('/select/techUsers', 'SelectController@techUsers');
Route::get('/select/catA', 'SelectController@categoryA');
Route::get('/select/catB', 'SelectController@categoryB');

//////////////////////////
////////*REPORTS*/////////
//////////////////////////
Route::get('/reports', 'AdminController@report')->name('reportsPage');

//////////////////////////
////////*DATATABLES*//////
//////////////////////////

Route::get('/tickets/ticket-data/{status}', 'DatatablesController@tickets');
Route::get('/store-visit/{table}', 'DatatablesController@storeVisit');

//////////////////////////
////////*MAINTENANCE*//////
//////////////////////////
Route::get('/maintenance', 'PublicController@maintenance')->name('maintenancePage');

//////////////////////////
////////*Email Group*//////
//////////////////////////
Route::get('/email/group/{id}/emails','MaintenanceController@getMailsFromGroup');
Route::post('/email/group/add','MaintenanceController@addMailsFromGroup');
Route::post('/email-group/add','MaintenanceController@addEmailGroup');
Route::delete('/email/group/delete/pivot/{pivot_id}','MaintenanceController@deleteEmailOnGroup');

/*EMAIL*/

Route::post('/email/add','MaintenanceController@addEmail');


//////////////////////////
////////*SEARCH*//////
//////////////////////////
Route::get('/search', 'PublicController@search')->name('search');

//////////////////////////
////////*POSITION*//////
//////////////////////////
Route::post('/add/position', 'PositionController@create');

//////////////////////////
////////*DEPARTMENT*//////
//////////////////////////
Route::post('/add/department', 'DepartmentController@create');

//////////////////////////
////////*Category A*//////
//////////////////////////
Route::post('/add/categoryA', 'MaintenanceController@storeCategoryA');
Route::get('/categoryA/{catAID}/subBCategories', 'CategoryAController@subBCategories');
//////////////////////////
////////*Category B*//////
//////////////////////////
Route::post('/add/categoryB', 'MaintenanceController@storeCategoryB');
//////////////////////////
////////*Category C*//////
//////////////////////////
Route::post('/add/categoryC', 'MaintenanceController@storeCategoryC');

//////////////////////////
////////*Connection Issue Reply*//////
//////////////////////////
Route::get('/reply/conversation/{id}', 'ConnectionIssueReplyController@replyConversation')->name('replyConversation');
Route::post('/reply/support', 'ConnectionIssueReplyController@replyMail');

//////////////////////////
////////*API*//////
//////////////////////////
Route::get('/api/user/{id}', 'UserController@userAPI');
Route::get('/api/connIssReply/{ticket_id}', 'ConnectionIssueReplyController@connIssReplyAPI');

//////////////////////////
////////*EXTENDED*//////
//////////////////////////
Route::post('/add/extend/{id}', 'ExtendController@create')->name('addExtend');

Route::get('/test', function () {
    $group = \App\EmailGroup::findOrFail(1)
        ->whereHas('emails',function($query)
        {
            $query->where('email_id',2);
        }
        )
        ->get();
    dd($group);
});


Route::get('/test2', function () {
    $group = \App\EmailGroup::
        whereHas('emails',function($query)
        {
            $query->where('email_id',16);
        })->find(1);
    dd($group);
});

//////////////////////////
////////*EXTENDED*//////
//////////////////////////
Route::get('treasury/dashboard','PublicController@treasuryDashboard')->name('treasuryDashboard');

//////////////////////////
////////*Technical Visit *//////
//////////////////////////
Route::get('/technical/store-visit','StoreVisitController@index')->name('storeVisitIndex');
Route::post('/store-visit/target/save','StoreVisitController@storeTarget');
Route::post('/store-visit/target/update/{id}','StoreVisitController@updateTarget')->name('updateTarget');
Route::post('/store-visit/detail/update/{id}','StoreVisitController@updateDetail')->name('updateDetail');
Route::delete('/store-visit/target/delete/{id}','StoreVisitController@deleteTarget');
Route::delete('/store-visit/details/delete/{id}','StoreVisitController@deleteDetails');
Route::post('/store-visit/details/save','StoreVisitController@storeDetails');
Route::get('/store-visit/target/{year}','StoreVisitController@getMonthsOnYear');


Route::get('/test', function () {
    $userSelect = \App\User::all()->toArray('full_name');

    dd($userSelect);


});

Route::get('/ongoingMail', function () {

    $ongoingMailInc = \App\ConnectionIssue::whereHas('incident.ticket',function($query){
        $query->where('status',2);
    })->with(['incident.ticket.connectionIssueMailReplies' => function($query){
        $query->latest('reply_date');
    }])->get();
       
    foreach ($ongoingMailInc as $connection_issue){
        $ticketID =  $connection_issue->incident->ticket->id;
        $subject = $connection_issue->incident->subject . " (TID#{$ticketID})";
        $latest_reply = $connection_issue->incident->ticket->connectionIssueMailReplies->first(); /*latest reply on the database*/
        
        fetchNewConnectionIssueEmailReplies($ticketID,$subject,$latest_reply);
    }
});

Auth::routes();
Auth::routes(['register' => false]);

Route::resource('sdc', 'SDCController');
Route::resource('mdc', 'MDCController');
Route::get('system/{id}/print', 'PrintController@sdcprinter')->name('sdc.printer');
Route::get('manual/{id}/print', 'PrintController@mdcprinter')->name('mdc.printer');
Route::get('reports/reports', 'ReportsController@reports')->name('reports.reports');
Route::post('reports/genipp', 'ReportsController@generateIPP')->name('reports.genipp');
Route::post('reports/genipc', 'ReportsController@generateIPC')->name('reports.genipc');
Route::post('reports/genilr', 'ReportsController@generateILR')->name('reports.genilr');
Route::post('reports/genrds', 'ReportsController@generateRDS')->name('reports.genrds');
Route::get('reports/charts', 'ReportsController@loadChart')->name('reports.charts');
Route::post('reports/lvr', 'ReportsController@loadLVR')->name('reports.lvrchart');
Route::post('reports/ipcr', 'ReportsController@loadIPCR')->name('reports.ipcr');
Route::post('reports/tpr', 'ReportsController@loadTR')->name('reports.tpr');

Route::post('get/positions', 'SDCController@getPosition')->name('get.positions');
Route::post('get/deppos', 'SDCController@getDepPos')->name('get.deppos');
// Data Corrections Separate Page
 Route::get('datacorrections', 'DCController@index')->name('datacorrections');
 
 
 Route::get('datacorrections/manualdc', 'DCController@manual')->name('datacorrections.manual');


 Route::get('datacorrections/sdc','DatatablesController@sdc')->name('datacorrection.sdc');
 Route::get('datacorrections/mdc','DatatablesController@mdc')->name('datacorrection.mdc');


 Route::get('datacorrections/sdc-data/{status}', 'DatatablesController@system')->name('datacorrections.system');
 Route::get('datacorrections/ty-data/{status}', 'DatatablesController@treasury')->name('datacorrections.dataty');
 Route::get('datacorrections/gc-data/{status}', 'DatatablesController@govcomp')->name('datacorrections.datagc');
 Route::get('datacorrections/app-data/{status}', 'DatatablesController@approver')->name('datacorrections.dataapp');
 
 Route::get('datacorrections/sdc/fordeployment', 'DCController@sdc_route')->name('datacorrectons.sdcDeployment');
 Route::get('datacorrections/sdc/ty1', 'DCController@sdc_route')->name('datacorrectons.sdcTreasury1');
 Route::get('datacorrections/sdc/ty2', 'DCController@sdc_route')->name('datacorrectons.sdcTreasury2');
 Route::get('datacorrections/sdc/govcomp', 'DCController@sdc_route')->name('datacorrectons.sdcGovComp');
 Route::get('datacorrections/sdc/finalapp', 'DCController@sdc_route')->name('datacorrectons.sdcFinalApp');

 Route::get('datacorrections/sdc/draft', 'DCController@sdc_route')->name('datacorrectons.sdcDraft');
 Route::get('datacorrections/sdc/done', 'DCController@sdc_route')->name('datacorrectons.sdcDone');
 Route::get('datacorrections/sdc/rejected', 'DCController@sdc_route')->name('datacorrectons.sdcRejected');
 Route::get('datacorrections/sdc/all', 'DCController@sdc_route')->name('datacorrectons.sdcAll');


 Route::get('datacorrections/ty/sdc/all', 'DCController@treasury_all')->name('datacorrectons.treasuryALL');
 Route::get('datacorrections/ty/sdc/pending', 'DCController@treasury_pending')->name('datacorrectons.treasuryPENDING');
 Route::get('datacorrections/ty/sdc/done', 'DCController@treasury_done')->name('datacorrectons.treasuryDONE');

 Route::get('datacorrections/ty2/sdc/all', 'DCController@treasury2_all')->name('datacorrectons.treasury2ALL');
 Route::get('datacorrections/ty2/sdc/pending', 'DCController@treasury2_pending')->name('datacorrectons.treasury2PENDING');
 Route::get('datacorrections/ty2/sdc/done', 'DCController@treasury2_done')->name('datacorrectons.treasury2DONE');
 
 Route::get('datacorrections/gc/sdc/all', 'DCController@govcomp_all')->name('datacorrectons.govcompALL');
 Route::get('datacorrections/gc/sdc/pending', 'DCController@govcomp_pending')->name('datacorrectons.govcompPENDING');
 Route::get('datacorrections/gc/sdc/done', 'DCController@govcomp_done')->name('datacorrectons.govcompDONE');
 

 Route::get('datacorrections/app/sdc/all', 'DCController@approver_all')->name('datacorrectons.approverALL');
 Route::get('datacorrections/app/sdc/pending', 'DCController@approver_pending')->name('datacorrectons.approverPENDING');
 Route::get('datacorrections/app/sdc/done', 'DCController@approver_done')->name('datacorrectons.approverDONE');
 
 

 Route::get('print/{id}/ticket', 'TicketController@print')->name('print.ticket');
 Route::post('rmv/attachment', 'SDCController@rmvattachment')->name('rmv.attachment');

 Route::post('reject/sdc', 'SDCController@rejectDataCorrect')->name('reject.sdc');

 Route::get('search/sdc', 'SDCController@search')->name('search.sdc');
 
 Route::post('group/selection', 'SDCController@selectGroup')->name('select.group');
 Route::post('group/selection2', 'SDCController@selectGroup2')->name('select.group2');
 
 Route::post('assign/group', 'SDCController@ChangeGroupData')->name('assign.group');
 Route::post('add/hierarchy', 'SDCController@addHierarchy')->name('add.hierarchy');
 
 Route::get('relate/{id}/ticket', 'TicketController@relateTicket')->name('relate.ticket');
 Route::get('/show/{id}/approverstats', 'TicketController@showAppStats')->name('show.appstats');
 Route::get('/get/{id}/appdetails', 'TicketController@getAppStatsDetails')->name('get.appDetails');
 Route::post('create/rticket', 'TicketController@createRTicket')->name('create.rticket'); 

 Route::get('/show/devprojects', 'DevProjController@show')->name('show.devprojs');
 Route::post('/add/devprojects', 'DevProjController@addProject')->name('add.devprojs');
 Route::post('/edit/devprojects', 'DevProjController@editProject')->name('edit.devprojs');
 Route::get('/delete/{id}/devprojects', 'DevProjController@deleteProject')->name('delete.devprojs');
 Route::get('/get/devprojects','DatatablesController@devProjects')->name('get.devprojs');
 Route::get('/show/{id}/editdevprojects','DevProjController@showEdit')->name('showEdit.devprojs');

 Route::get('/show/mds','MDSIssuesController@show')->name('show.mds');
 Route::get('/get/mdis','DatatablesController@addMDSissue')->name('get.mdis');
 Route::post('/add/mdissue', 'MDSIssuesController@store')->name('add.mdissue');
 Route::get('/show/{id}/editmdissue','MDSIssuesController@showEdit')->name('showEdit.mdIssue');
 Route::post('/edit/mdis','MDSIssuesController@edit')->name('edit.mdis');
 Route::get('/delete/{id}/mdis','MDSIssuesController@destroy')->name('delete.mdis');
 Route::get('/show/greport', 'ReportsController@showNetworkDowns')->name('show.GReport');
