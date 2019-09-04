<?php

namespace App\Http\Controllers;

use App\Call;
use App\Caller;
use App\CategoryB;
use App\CategoryC;
use App\ConnectionIssue;
use App\File;
use App\Fix;
use App\Http\Requests\StoreTicket;
use App\Incident;
use App\Mail\PLDTIssue;
use App\Ticket;
use App\SystemDataCorrection;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','check.role'])->except(['print','lookupView']);
    }

    public function getTicket($id)
    {

        $ticket = Ticket::with([
            'issue.incident',
            'statusRelation',
//            'issue.issue.caller',
            'typeRelation',
            'assigneeRelation',
            'issue.categoryRelation',
            'issue.catARelation',
            'issue',
            'issue.getFiles',
            'ticketMessages'
        ])
            ->findOrFail($id)->toArray();

        return response()->json($ticket);
    }

    public function lookupView(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        /*1 means Open Ticket*/
        if ($ticket->status === 1) {
            $incomplete_ticket = checkTicketDataIfIncomplete($id);
            if ($incomplete_ticket['incomplete'] && ($incomplete_ticket['logged_by'] === $request->user()->id)) {
                return redirect()->route('incompleteTicket', ['id' => $id]);
            } else if($incomplete_ticket['logged_by'] !== $request->user()->id){
                return redirect()->back();
            }
        }

        /*CHECK IF TICKET IS CREATED THROUGH CALL OR EMAIL*/
        if ($ticket->issue->incident_type === \App\Call::class) {
            $relationArray = [
                'issue.incident.loggedBy',
                'statusRelation',
                'issue.incident.caller',
                'typeRelation',
                'issue.incident.contact.store',
                'assigneeRelation',
                'issue.categoryRelation',
                'issue.catARelation',
                'issue',
                'issue.getFiles'
            ];
        } else {
            $relationArray = [
                'userLogged',
                'statusRelation',
                'typeRelation',
                'assigneeRelation',
                'issue.categoryRelation',
                'issue.catARelation',
                'issue',
                'issue.getFiles',
                'connectionIssueMailReplies' => function ($query) {
                    $query->latest();
                }
            ];

        }
        $sdc = null;
        // $ticket = Ticket::with($relationArray)

        $ticket::with($relationArray)->findOrFail($id);
        $sdc = SystemDataCorrection::where('ticket_no', '=', $ticket->id)->first();

        $cTicket = null;
        $pTicket = null;

        if(isset($ticket->crt_id)){ 
            $cTickets = unserialize($ticket->crt_id);
            $cTicket = Ticket::whereIn('id',$cTickets)->get();
        }elseif(isset($ticket->prt_id)){
            $pTicket = Ticket::findOrfail($ticket->prt_id);
        }

        
    
        // return view("ticket.ticket_lookup",  compact(['ticket','cTicket', 'pTicket', 'sdc']));
        if(Auth::user()->position->department->id === $this->treasury_department_id){
            $view  = 'treasury.ticket_lookup';
        }else {
            $view = 'ticket.ticket_lookup';
        }
        return view($view, compact(['ticket','cTicket', 'pTicket', 'sdc']));
    }

    public function addTicketView()
    {
        $userID = Auth::user()->id;

        /*CHECK IF USER STILL HAS UNCOMPLETED TICKET DATA'S*/
        $uncompleted_ticket = validateLoggersTicketStatus($userID);

        if ($uncompleted_ticket['incomplete'] === true) {
            return redirect()->route('incompleteTicket', ['id' => $uncompleted_ticket['ticket_id']]);
        } else {
            return view('ticket.add_ticket');
        }

    }

    public function incompleteTicket($id)
    {

        $ticket = Ticket::findOrFail($id);

        if ($ticket->logged_by == Auth::id()) {
            return view('ticket.incomplete', compact('ticket'));
        } else {
            return redirect()->back();
        }

    }

    public function relateTicket($id){
        $ticket = Ticket::findOrFail($id);
        // return view('ticket.create_rticket', compact('ticket'));
        return view('modal.r_ticket', compact('ticket'));
    }
    public function addTicket(StoreTicket $request)
    {

        $ticket_id = DB::transaction(function () use ($request) {
            $caller_id = ((int)$request->user === 0) ? addCaller($request->except(['_token', 'store'])) : $request->user;
            $requester_id = $request->user()->id;

            /*INSERT CALL RECORD ANG GET THE ID OF THE INSERTTED RELATION INCIDENT*/
            $ticket_id = Call::create(['caller_id' => $caller_id,'caller_type' => 'App\User', 'user_id' => $requester_id])
                ->incident()->create()->ticket()->create(['store_id' => $request->store,'store_type' => 'App\Store', 'status' => 1, 'logged_by' => $requester_id])->id;

            if(!is_null($ticket_id)){
                return $ticket_id;
            }else {
                return response('Failed to create ticket',400);
            }
        });

        return response(compact('ticket_id'));

    }

    public function createRTicket(Request $request){
         $ticket = Ticket::findOrFail($request->rt_id);
         $incident = Incident::findOrFail($ticket->issue_id);
        //  $ticketReplicate = $ticket->replicate();
        //  $incidentReplicate = $incident->replicate();         

         $requester_id = Auth::user()->id;
         $call = Call::findOrfail($incident->incident_id);
         $caller_id = $call->caller_id;
         $caller_type = $call->caller_type;

         $newCall =  new Call();
         $newCall->caller_type = $caller_type;
         $newCall->caller_id = $caller_id;
         $newCall->user_id = $requester_id;
         $newCall->save();

         $newIncident = new Incident();
         $newIncident->incident_type = "App\Call";
         $newIncident->incident_id = $newCall->id ;
        //  $newIncident->connection_id = null;
         $newIncident->subject = $request->subject;
         $newIncident->details = $request->details;
         $newIncident->category = $request->category;
         $catA = CategoryB::findOrFail($request->catB)->group->id;
         $newIncident->catA =  $catA;
         $newIncident->catB = $request->catB;
         $newIncident->catC = null;
         $newIncident->save();
         $newIncident_id = $newIncident->id;

         $newTicket = new Ticket();
        //  MAY CHANGE IN THE FUTURE IF THERES AN REQUEST TYPE TICKET DEPLOYED
         $newTicket->issue_type = $ticket->issue_type;
         $newTicket->issue_id = $newIncident->id;
        //  END
         $newTicket->assignee = $request->assignee;
         $newTicket->logged_by = $newCall->user_id;
         $newTicket->type = 1;
         $newTicket->priority = $request->priority;
         if(isset($request->assignee)){
            $newTicket->status = 2;
         }else{
            $newTicket->status = 1;
         }
         $newTicket->store_type = $ticket->store_type;
         $newTicket->store_id = $ticket->store_id;
         $newTicket->group = $request->group;
         $expiration_hours = CategoryB::findOrFail($request->catB)->getExpiration->expiration;
         $expiration_date = Carbon::now()->addHours($expiration_hours);
         $newTicket->expiration = $expiration_date;
         $newTicket->prt_id = $ticket->id;
         $newTicket->save();

         $newTicket_id = $newTicket->id;
         $newTicket_cdate = $newTicket->created_at;

          $crtArr = "";
         if(isset($ticket->crt_id)){
              $arr = unserialize($ticket->crt_id);
              array_push($arr, $newTicket_id );
              $crtArr = serialize($arr);
          }else{
              $arr = [];
              array_push($arr,$newTicket_id);
              $crtArr = serialize($arr);
          }

          $ticket->crt_id = $crtArr;
          $ticket->save();
         
           /*CREATE DIRECTORY NAME*/
           $ticketDirectoryName = str_replace(':', '', preg_replace('/[-,\s]/', '_', $newTicket_cdate)) . '_' .  $newTicket_id;

         if ($request->hasFile('attachments')) {
            foreach ($request->attachments as $attachment) {

                $original_name = $attachment->getClientOriginalName();
                $mime_type = $attachment->getMimeType();
                $original_ext = $attachment->getClientOriginalExtension();
                $path = $attachment->store("$ticketDirectoryName", 'ticket');

                File::create(['incident_id' => $newIncident_id , 'path' => $path, 'original_name' => $original_name, 'mime_type' => $mime_type, 'extension' => $original_ext]);
            };
        }

        return redirect()->route('lookupTicketView', ['id' => $newTicket->id]);
    }
    public function addTicketDetails(StoreTicket $request)
    {
        /*FETCH THE EXPIRATION HOURS COLUMN*/
        $expiration_hours = CategoryB::findOrFail($request->catB)->getExpiration->expiration;

        /*GENERATE TE EXPIRATION DATE*/
        $expiration_date = Carbon::now()->addHours($expiration_hours);

        /*ADD EXPIRATION IN REQUEST ARRAY*/
        $request->request->add(array('expiration' => $expiration_date));

        /*ID OF THE TICKET AND INCIDENT THAT THE DETAILS WILL BE INSERTED TO*/
        $ticket_id = $request->ticket_id;

        /*CHECK IF THE STATUS OF TICKET WILL BE OPEN OR ONGOING*/
        $request->request->add(self::assignStatus($request->assignee));

        DB::transaction(function () use ($request, $ticket_id) {

            $ticket = Ticket::findOrFail($ticket_id);
            $ticket->update($request->only('expiration', 'type', 'priority', 'assignee', 'status', 'group'));
            $ticket->issue->update($request->only('subject', 'details', 'category', 'catA', 'catB','catC'));

            /*CREATE DIRECTORY NAME*/
            $ticketDirectoryName = str_replace(':', '', preg_replace('/[-,\s]/', '_', $ticket->created_at)) . '_' . $ticket_id;

            /*CHECK IF REQUEST CONTAINS A FILE AND STORE IT*/
            if ($request->hasFile('attachments')) {
                foreach ($request->attachments as $attachment) {

                    $original_name = $attachment->getClientOriginalName();
                    $mime_type = $attachment->getMimeType();
                    $original_ext = $attachment->getClientOriginalExtension();
                    $path = $attachment->store("$ticketDirectoryName", 'ticket');

                    File::create(['incident_id' => $ticket->issue_id, 'path' => $path, 'original_name' => $original_name, 'mime_type' => $mime_type, 'extension' => $original_ext]);
                };
            }

        });


        return redirect()->route('lookupTicketView', ['id' => $ticket_id]);


    }


    public function open()
    {

        $ticketTotals = ticketTypeCount('status', 1);

        return view('ticket.openTickets', $ticketTotals);
    }

    public function ongoing()
    {

        $ticketTotals = ticketTypeCount('status', 2);
        return view('ticket.ongoingTickets', $ticketTotals);
    }

    public function expired()
    {

        $ticketTotals = ticketTypeCount('status', 6);
        return view('ticket.expiredTickets', $ticketTotals);
    }

    public function closed()
    {

        $ticketTotals = ticketTypeCount('status', 3);
        return view('ticket.closedTickets', $ticketTotals);
    }

    public function fixed()
    {

        $ticketTotals = ticketTypeCount('status', 4);
        return view('ticket.fixedTickets', $ticketTotals);
    }

    public function all()
    {

        $ticketTotals = ticketTypeCount('all');
        return view('ticket.allTickets', $ticketTotals);
    }

    public function forVerification()
    {

        return view('ticket.openTickets');
    }

    public function userTickets()
    {
        $ticketTotals = ticketTypeCount('user', Auth::id());
        return view('ticket.myTickets', $ticketTotals);
    }

    public function delete($id)
    {
        $this->authorize('delete', Ticket::class);
        Ticket::findOrFail($id)->delete();


        return redirect()->route('myTickets');
    }

    public function edit($id, StoreTicket $request)
    {
        try {
            DB::beginTransaction();
            $bool = true;
            if ($request->filled(['incident'])) {
                $incident = Ticket::findOrFail($id)->issue;

                foreach ($request->incident as $key => $value) {
                    $incident->$key = $value;
                }
                $incident->save();

            }

            if ($request->filled(['ticket'])) {
                $ticket = Ticket::findOrFail($id);
                foreach ($request->ticket as $key => $value) {
                    $ticket->$key = $value;
                }

                /*CHECK IF THE STATUS OF TICKET WILL BE OPEN OR ONGOING*/
                $status = self::assignStatus($request->ticket['assignee'] ?? FALSE);
                $ticket->status = $status['status'];
                $ticket->save();
            }

            if ($request->filled(['fileID'])) {
                foreach ($request->fileID as $id) {
                    $file = File::findOrFail($id);
                    if (Storage::disk('ticket')->delete($file->path)) $file->delete();
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            $bool = $e;
            DB::rollback();
        }

        return response()->json(['success' => $bool]);

    }

    public function editModal($id)
    {
        $ticket = Ticket::with([
            'issue.incident.loggedBy',
            'statusRelation',
//            'issue.issue.caller',
            'typeRelation',
            'issue.incident.contact.store',
            'assigneeRelation',
            'issue.categoryRelation',
            'issue.catARelation',
            'issue',
        ])
            ->findOrFail($id);
        $view = view('modal.ticket_edit', ['ticket' => $ticket]);
        $viewString = strlen($view->render());
        return response()->view('modal.ticket_edit', ['ticket' => $ticket])->header('Content-Encoding', 'none')->header('Content-Length', $viewString);
    }

    public function addFile($id, Request $request)
    {

        $ticket = Ticket::findOrFail($id);
        $destination = $ticket->getFileDirectoryFolder();

        foreach ($request->file as $attachment) {

            $original_name = $attachment->getClientOriginalName();
            $mime_type = $attachment->getMimeType();
            $original_ext = $attachment->getClientOriginalExtension();
            $path = $attachment->store($destination, 'ticket');

            File::create(['incident_id' => $ticket->issue->id, 'path' => $path, 'original_name' => $original_name, 'mime_type' => $mime_type, 'extension' => $original_ext]);
        };
    }

    public function print($id)
    {
        $ticket = Ticket::all()->where('id', $id);
        return view('layouts.printTicket')->with('ticket', $ticket);
    }

    public function addConnectionIssue(Request $request)
    {
        $validation = [
            'to' => 'required',
            'subject' => 'required|string|min:5',
            'cc' => 'string|nullable',
            'details' => 'required|string|min:5',
            'branch' => 'required|numeric',
            'contact_number' => 'required|string|min:5',
            'contact_person' => 'required|string|min:5',
        ];
        $voice = ['tel' => 'required|string|min:5'];
        $data = ['pid' => 'required|string|min:5'];

        $to = array();
        $group = array();

        /*split ang grouped emails and individual emails*/
        foreach ($request->to as $mail){
            (str_contains($mail,'group')) ? array_push($group,$mail) : array_push($to,$mail);
        }

        /*iterate grouped emails and get emails and check if present already on the individual emails*/
        foreach ($group as $email_group){
          [$group,$id] = explode("_",$email_group);
          $emails = \App\EmailGroup::find($id)->emails;
            /*check if present already on the individual emails*/
            foreach ($emails as $mail){
                $cur_mail = $mail->email;
                /*append to indivial emails*/
                if (!in_array($cur_mail,$to)) array_push($to,$cur_mail);
            }
        }
        
        /*change value of to in the request*/
        $request->request->add(['to' => implode(',',$to)]);

        $catC = $request->concern;
        /*GET CATEGORY B id*/
        $catB = CategoryC::findOrFail($catC)->catB;
        /*GET CATEGORY A id*/
        $catB_relations = CategoryB::with('group:id', 'getExpiration:id,expiration')->findOrFail($catB);

        /*ADD TO REQUEST PARAMETER BAG*/
        $request->request->add(['catC' => $catC, 'category' => 3, 'catB' => $catB, 'catA' => $catB_relations->group->id]);
        
        /*16 IS THE ID OF THE CATEGORY B VOICE*/
        if ((int)$catB === 16) {
            $validation = $validation + $voice;
        } elseif ((int)$catB === 17) {
            $validation = $validation + $data;
        } else {
            $validation = $validation + $data + $voice;
        }
        
        $request->validate($validation);
        $expiration = Carbon::now()->addHours($catB_relations->getExpiration->expiration);

        if ($catB_relations->name === 'Both') {
            $td_header = 'PID/TEL';
            $concern_number = "{$request->pid}/{$request->tel}";
        } elseif ($catB_relations->name === 'Data') {
            $td_header = 'PID';
            $concern_number = $request->pid;
        } elseif ($catB_relations->name === 'Voice') {
            $td_header = 'TEL';
            $concern_number = $request->tel;
        } else {
            $td_header = 'UNKNOWN';
        }


        $ticket_id = DB::transaction(function () use ($expiration, $request, $td_header, $concern_number) {
            /*INSERT TO DATABASE*/
            $ticket_id = ConnectionIssue::create($request->only(['cc', 'to', 'account', 'pid', 'tel', 'contact_person', 'contact_number']))
                ->incident()->create($request->only(['subject', 'details', 'catC', 'catB', 'catA', 'category']))
                ->ticket()->create(['assignee' => $request->user()->id, 'logged_by' => $request->user()->id, 'type' => 1, 'priority' => 4, 'status' => 2, 'store_id' => $request->branch, 'group' => 1, 'expiration' => $expiration,'store_type' => "App\Store"])->id;

            /*SEND MAIL*/
            $mail = new Mail;
            $to = explode(',', $request->to);

            /*include cc if request has cc*/
            if (!is_null($request->cc)) {
                $cc = explode(',', $request->cc);
                $mail::to($to)->cc($cc)->send(new PLDTIssue($request, $ticket_id, $td_header, $concern_number));
            } else {
                $mail::to($to)->send(new PLDTIssue($request, $ticket_id, $td_header, $concern_number));
            }

            return $ticket_id;

        });

        if (!is_null($ticket_id)) {
            return response()->json(['response' => 'emailConIssueSentSuccess', 'data' => ['ticket_id' => $ticket_id]]);
        }

    }

    public function editStatus(StoreTicket $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 4;
        $ticket->fixed_date = Carbon::now();
        $ticket->save();
    }

    public function reject(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 5;
        $ticket->save();
        $rejected_by = Auth::id();
        $ticket->rejectData()->create(['reason' => $request->reason, 'rejected_by' => $rejected_by]);

        return redirect()->route('lookupTicketView', ['id' => $id]);
    }

    public function rejectForm($id)
    {
        return view('modal.rejectForm', ['id' => $id]);
    }

    public function rejectFormDetails($id)
    {
        $ticket = Ticket::findOrFail($id);
        $rejectData = $ticket->rejectData->first();
        return view('modal.reject_lookup', compact('ticket', 'rejectData'));
    }

    public function getExtendForm($id)
    {
        return view('modal.extendForm', ['id' => $id]);
    }

    public function ticketExtendDetails($id)
    {
        $ticket_extensions = Ticket::findOrFail($id)->extended()->latest()->get();
        return view('ticketExtendDetails', ['ticket_extensions' => $ticket_extensions]);
    }

    private static function assignStatus($assignee)
    {
        /*GET ID'S OF THE OPEN AND ONGOING CATEGORY IN THE CATEGORY TABLE*/
        $openID = 1;
        $ongoingID = 2;

        if (!$assignee) {
            $status = ['status' => $openID];
        } else {
            $status = ['status' => $ongoingID];
        }

        return $status;
    }

    public function showAppStats($id){
        $data = "";
        $sdc = SystemDataCorrection::findOrfail($id);
        $unhierarchy = $sdc->hierarchy;
        $serhierarchy = unserialize($unhierarchy);
        $hierarchy = "";
        
        foreach ($serhierarchy as $app) {
        $data .="<tr>";
            $approver = "";
            $status = "";
            $datetimeapproved = "N/A";
            $approvedby = "N/A";
           switch($app){
                case '1' : 
                    $approver = "TREASURY I";
                    if($sdc->forward_status > 1 && $sdc->status != 0){
                        $status = "APPROVED";
                        $datetimeapproved = $sdc->t1_datetime_apprvd;
                        $approvedby = $sdc->ty1_fullname;
                    }else{
                       if($sdc->status == 2 && $sdc->forward_status == 1){
                            $status = "REJECTED";
                       }else{
                            $status = "PENDING";
                       }
                    }
                    $hierarchy .= "TREASURY I " . " >> ";
                break;
                case '2' : 
                    $approver = "TREASURY II";
                    if($sdc->forward_status > 2 && $sdc->status != 0){
                        $status = "APPROVED";
                        $datetimeapproved = $sdc->t2_datetime_apprvd;
                        $approvedby = $sdc->pre_verified_by;
                    }else{
                        if($sdc->status == 2 && $sdc->forward_status == 2){
                            $status = "REJECTED";
                       }else{
                            $status = "PENDING";
                       }
                    }
                    $hierarchy .= "TREASURY II " . " >> ";
                break;
                case '3' : 
                    $approver = "GOV. COMPLIANCE";
                    if($sdc->forward_status > 3 && $sdc->status != 0){
                        $status = "APPROVED";
                        $datetimeapproved = $sdc->govcomp_datetime_apprvd;
                         $approvedby = $sdc->pre_acc_verified_by;
                    }else{
                       if($sdc->status == 2 && $sdc->forward_status == 3){
                            $status = "REJECTED";
                       }else{
                            $status = "PENDING";
                       }
                    }
                    $hierarchy .= "GOV. COMPLIANCE " . " >> ";
                break;
                case '4' : 
                    $approver = "FINAL APPROVER";
                    
                    if($sdc->forward_status > 4 && $sdc->status != 0){
                        $status = "APPROVED";
                        $datetimeapproved = $sdc->app_datetime_apprvd;
                        $approvedby = $sdc->app_approved_by;
                    }else{
                       if($sdc->status == 2 && $sdc->forward_status == 4 ){
                            $status = "REJECTED";
                       }else{
                            $status = "PENDING";
                       }
                    }
                    $hierarchy .= "FINAL APPROVER";
                break;
           }
           $data .= "<td>".$approver."</td><td>".$status."</td><td>".$datetimeapproved."</td><td>".$approvedby."</td>";
        $data .= "</tr>";
        }

       
         $view = view('modal.approver_stats')->with('id',$id)->with('hierarchy', $hierarchy);
         return response()->json(array('success'=>true, 'data'=>"$data", 'view'=>"$view"), 200);
    }

    public function getAppStatsDetails($id){
        $data = "";
        $sdc = SystemDataCorrection::findOrfail($id);
        $unhierarchy = $sdc->hierarchy;
        $serhierarchy = unserialize($unhierarchy);
        
        foreach ($serhierarchy as $app) {
        $data .="<tr>";
            $approver = "";
            $status = "";
            $datetimeapproved = "N/A";
            $approvedby = "N/A";
           switch($app){
                case '1' : 
                    $approver = "TREASURY I";
                    if($sdc->forward_status > 1){
                        $status = "APPROVED";
                        $datetimeapproved = $sdc->t1_datetime_apprvd;
                        $approvedby = $sdc->ty1_fullname;
                    }else{
                        $status = "PENDING";
                    }
                break;
                case '2' : 
                    $approver = "TREASURY II";
                    if($sdc->forward_status > 2){
                        $status = "APPROVED";
                        $datetimeapproved = $sdc->t2_datetime_apprvd;
                        $approvedby = $sdc->ty1_fullname;
                    }else{
                        $status = "PENDING";
                    }
                break;
                case '3' : 
                    $approver = "GOV. COMPLIANCE";
                    if($sdc->forward_status > 3){
                        $status = "APPROVED";
                        $datetimeapproved = $sdc->govcomp_datetime_apprvd;
                        // $approvedby = $sdc->ty1_fullname;
                    }else{
                        $status = "PENDING";
                    }
                break;
                case '4' : 
                    $approver = "FINAL APPROVER";
                    
                    if($sdc->forward_status > 4){
                        $status = "APPROVED";
                        $datetimeapproved = $sdc->app_datetime_apprvd;
                        // $approvedby = $sdc->ty1_fullname;
                    }else{
                        $status = "PENDING";
                    }
                break;
           }
           $data .= "<td>".$approver."</td><td>".$status."</td><td>".$datetimeapproved."</td><td>".$approvedby."</td>";
        $data .= "</tr>";
        }

       

        return response()->json(array('success'=>true, 'data'=>"$data"), 200);
  
}
    public function resolve(Request $request,$id){
        $fix = Fix::findOrFail($id);

        DB::transaction(function () use($fix,$request,$id){
            $fix->ticket->update(['status' => 3]);
            $fix->resolve()->create(['fixes_id' => $id,'resolved_by' => $request->user()->id]);
        });
    }


    public function usersGroup(Request $request){
        if($request->assign != 'none'){
            $id = $request->id;
            $user = User::find($id);
        }else{
            $user = User::find(Auth::user()->id);
        }
       
        return response()->json(array('success'=>true, 'id'=>$user->getGroupAttribute()));
    }
}
