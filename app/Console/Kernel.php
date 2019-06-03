<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
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
        })->everyMinute()->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
