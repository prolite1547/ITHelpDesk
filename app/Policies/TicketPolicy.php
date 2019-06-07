<?php

namespace App\Policies;

use App\Role;
use App\Status;
use App\Ticket;
use App\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;
    private static $user_roles;
    private static $ticket_statuses;
    private static $high_roles;

    public function __construct()
    {
        self::$user_roles = Role::pluck('id','role');
        self::$ticket_statuses = Status::pluck('id','name');
        self::$high_roles = Role::whereIn('id',[2,4])->pluck('id','role')->toArray();

    }

    /**
     * Determine whether the user can view the ticket.
     *
     * @param  \App\User  $user
     * @param  \App\Ticket  $ticket
     * @return mixed
     */
    public function view(User $user, Ticket $ticket)
    {
        //
    }

    /**
     * Determine whether the user can create tickets.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the ticket subject and details.
     *
     * @param  \App\User  $user
     * @param  \App\Ticket  $ticket
     * @return mixed
     */
    public function update(User $user, Ticket $ticket)
    {
        $statuses = array(self::$ticket_statuses['Open'],self::$ticket_statuses['Ongoing'],self::$ticket_statuses['Rejected']);
        return ($user->id == $ticket->assignee || in_array($user->role_id,self::$high_roles)) && in_array($ticket->status,$statuses);
    }


    /**
     * Determine whether the user can update the ticket's details.
     *
     * @param  \App\User  $user
     * @param  \App\Ticket  $ticket
     * @return mixed
     */
    public function updateDetails(User $user, Ticket $ticket)
    {
        $statuses = array(self::$ticket_statuses['Open'],self::$ticket_statuses['Ongoing'],self::$ticket_statuses['Rejected']);
        return in_array($user->role_id,self::$high_roles) && in_array($ticket->status,$statuses);
    }

    /**
     * Determine whether the user can delete the ticket.
     *
     * @param  \App\User  $user
     * @param  \App\Ticket  $ticket
     * @return mixed
     */
    public function delete(User $user)
    {
        return in_array($user->role_id,self::$high_roles);
    }

    /**
     * Determine whether the user can restore the ticket.
     *
     * @param  \App\User  $user
     * @param  \App\Ticket  $ticket
     * @return mixed
     */
    public function restore(User $user, Ticket $ticket)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the ticket.
     *
     * @param  \App\User  $user
     * @param  \App\Ticket  $ticket
     * @return mixed
     */
    public function forceDelete(User $user, Ticket $ticket)
    {
        //
    }

    /**
     * Determine whether the user can extend the ticket .
     *
     * @param  \App\User  $user
     * @param  \App\Ticket  $ticket
     * @return mixed
     */
    public function markAsFix(User $user, Ticket $ticket)
    {
        $statuses = array(self::$ticket_statuses['Ongoing'],self::$ticket_statuses['Rejected']);

        return !is_null($ticket->assignee) && ($user->id == $ticket->assignee) && in_array($ticket->status,$statuses) ;
    }



    /**
     * Determine whether the user can mark the ticket as fix .
     *
     * @param  \App\User  $user
     * @param  \App\Ticket  $ticket
     * @return mixed
     */
    public function extend(User $user, Ticket $ticket)
    {
        return ($ticket->status == self::$ticket_statuses['Expired']) && in_array($user->role_id,self::$high_roles);
    }



    /**
     * Determine whether the user can resolve or reject the ticket.
     *
     * @param  \App\User  $user
     * @param  \App\Ticket  $ticket
     * @return mixed
     */
    public function resolveReject(User $user, Ticket $ticket)
    {
        return in_array($user->role_id,self::$high_roles) && $ticket->status == self::$ticket_statuses['Fixed'];
    }
}
