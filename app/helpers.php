<?php

use App\AllUser;
use App\Caller;
use App\Category;
use App\Status;
use App\TempUser;
use App\Ticket;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

if (! function_exists('selectArray')) {
        function selectArray($group = '',$model,$id,$name){

            if($group){
                $category = $model::find($group);
                $rows = $category->categories;
            }else{
                $rows = $model::all();
            }




            $select = [];
            foreach ($rows as $category) {
                $select = array_add($select,$category->$id,$category->$name);
            }


            return $select;
        }
    }

    if (! function_exists('lookupCategories')) {
        //iterates array and look up the id to the categories table to get the value
        function lookupCategories($array){

            $lookupCategories = [];
            foreach ($array as $key => $value) {
                if($data = Category::find($value)){
                    $lookupCategories[$key] = $data;
                }
            }

            return $lookupCategories;
        }
    }

    if (! function_exists('groupListSelectArray')) {
        function groupListSelectArray($model,$groupName,$relationship,$value,$name,$constraint = []){
            $records = $model::when($constraint,function ($query,$constraint){

                return $query->whereNotIn($constraint['column'],$constraint['values']);
            })->with($relationship)->get();



            $dataArray = [];
            foreach ($records as $row){
                if($row->$relationship->count() !== 0){
                    $dataArray[$row->$groupName] = $row->$relationship->pluck($name,$value)->toArray();
                }
            }
            return $dataArray;
        }


    }

if (! function_exists('ticketTypeCount')) {
    function ticketTypeCount($type,$id = ''){

        if($type === 'all'){
            $tickets = Ticket::all();
        }elseif($type === 'status'){
            $tickets = Ticket::whereStatus($id)->get();
        }elseif($type === 'user'){
            $tickets = User::find($id)->assignedTickets;
        }

        $ticketCount = $tickets->count();
        $incident = $request = $others = 0;
        foreach ($tickets as $ticket){
            if($ticket->type === 1){
                $incident++;
            }elseif ($ticket->type === 2){
                $request++;
            }else{
                $others++;
            }
        }

        return compact('incident','request','ticketCount');
    }
}

if (! function_exists('addCaller')) {
    function addCaller($data){
        $data['department_id'] = \App\Position::findOrFail($data['position_id'])->department->id;
        return TempUser::create()->user()->create($data)->id;
    }
}

if (! function_exists('validateLoggersTicketStatus')) {
    function validateLoggersTicketStatus($user_id){
        $user_tickets = DB::table('tickets as t')
            ->join('incidents as i','t.issue_id','i.id')
            ->where('t.logged_by',$user_id)
            ->where('t.status',1)
            ->where(function ($query){
                $query->orWhere(['t.priority' => null,'t.expiration' => null,'i.subject' => null,'i.details' => null,'i.category' => null,'i.catA' => null,'i.catB' => null,'t.group' => null]);
            })
            ->select('t.id','t.logged_by')
            ->first();

        if($user_tickets){
            return ['incomplete' => true,'ticket_id' => $user_tickets->id];
        }else{
            return ['incomplete' => false];
        }

    }
}


if (! function_exists('checkTicketDataIfIncomplete')) {
    function checkTicketDataIfIncomplete($ticket_id){
        $ticket = DB::table('tickets AS t')
            ->join('incidents AS i','t.issue_id','i.id')
            ->select('t.id','t.logged_by','t.priority','t.expiration','i.subject','i.details','i.category','i.catA','i.catB')
            ->where('t.id',$ticket_id)
        ->first();
        foreach ($ticket as $property => $value){
            if(is_null($value)) return ['incomplete' => true,'logged_by' =>$ticket->logged_by];
        }

        return ['incomplete' => false,'logged_by' =>$ticket->logged_by];
    }
}

if (! function_exists('getNotificationContent')) {
    function getNotificationContent($userID){
        $userRejectedTickets = Ticket::whereStatus(5)->whereAssignee($userID)->count();

        if($userRejectedTickets !== 0){
            return $userRejectedTickets > 1 ?  "You have {$userRejectedTickets} rejected tickets!" : "You have {$userRejectedTickets} rejected ticket!";
        }else{
            return false;
        }
    }
}
if (! function_exists('cleanInputs')) { /*uppercase words and remove extra white spaces*/
    function cleanInputs($string){

        if($string !== ''){
            return $cleanString = ucwords(preg_replace('/\s+/', ' ', rtrim(ltrim(strtolower($string)))));
        }else {
            return $string;
        }
    }
}

if (! function_exists('getNumberOfTicketsOnASpecStatus')) { /*uppercase words and remove extra white spaces*/
    function getNumberOfTicketsOnASpecStatus(){
        $group = Auth::user()->group;
        $ticketStatuses = Status::all()->pluck('name','id')->toArray();
        $ticketCounts = array();
        $ticketCounts['All'] =  Ticket::whereGroup($group)->count();
        /*foreach ($ticketStatuses as $key => $value){
            if($value === 'Fixed'){
                $group = Auth::user()->group;
                $count = Ticket::whereStatus($key)->when($group,function ($query,$group){
                    return $query->whereGroup($group);
                })->count();
            }else{
                $count = Ticket::whereStatus($key)->count();
            }
            $ticketCounts[$value] = $count;
        }*/

        foreach ($ticketStatuses as $key => $value){
            $count = Ticket::whereStatus($key)->when($group,function ($query,$group){
                return $query->whereGroup($group);
            })->count();
            $ticketCounts[$value] = $count;
        }

        return $ticketCounts;
    }
}

if (! function_exists('getGroupIDDependingOnUser')) { /*uppercase words and remove extra white spaces*/
    function getGroupIDDependingOnUser(){

        $authRole = Auth::user()->role->role;
        $authPosition = Auth::user()->position->position;

        $group = [4 => [1],5 => [2],'all' => [1,2]];

        /*4 is equivalent to admin user*/
        if(strcasecmp($authRole,'admin') !== 0){

            if($authPosition !== 1 && $authPosition !== 2){
                $groupID = $group[$authPosition];
            }else{
                $groupID = $group[4];
            }

        }else {
            $groupID = $group['all'];
        }


        return $groupID;
    }
}


if (! function_exists('fetchNewConnectionIssueEmailReplies')) { /*fetch new mails then add to database*/
     function fetchNewConnectionIssueEmailReplies(int $ticketID,string $subject,$latest_reply){
        $date = \Carbon\Carbon::now()->format('d.m.Y');
        $oClient = new \Webklex\IMAP\Client;
        $oClient->connect();

        $inboxFolder = $oClient
            ->getFolder('INBOX');
        
        $inboxMessages = $inboxFolder
            ->query()
            ->on($date)
            ->subject($subject)
            ->setFetchFlags(false)
            ->setFetchBody(true)
            ->setFetchAttachment(true)
            ->get();

        
        foreach ($inboxMessages as $message){

                $reply = (new \EmailReplyParser\Parser\EmailParser())->parse($message->getTextBody())->getVisibleText();
                $reply_date = $message->getDate();

                /*IF MAIL HAS NO REPLY YET OR COMPARE LATEST REPLY ON THE MAIL AND IN THE DATABASE THEN INSERT*/
                /*Note: The reason that from is not json encoded because it is cast to array*/
                if (is_null($latest_reply) || ($latest_reply->reply !== $reply && !$latest_reply->reply_date->greaterThan($reply_date))) {
                    $plain_text = $message->getTextBody();
                    $html_body = $message->getHTMLBody();
                    $hasAttachments = $message->getAttachments()->count();
                    $subject = $message->getSubject();
                    $from = $message->getFrom()[0];
                    $to = json_encode($message->getTo());
                    $cc = json_encode($message->getCC());
                    $reply_to = $message->getInReplyTo();
                    $ticket_id = $ticketID;

                    $connection_issue = new \App\ConnectionIssueReply;
                    $connection_issue_fillable = $connection_issue->getFillable();
                    $connection_issue->create(compact($connection_issue_fillable));
                }
        }

         

    }
}




