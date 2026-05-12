<?php

namespace App\Http\Controllers\User\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\TicketResponseBelongsToUser;
use App\Services\Support\ListMySupportTicketsService;
use App\Services\Support\NewTicketFormConfigService;
use App\Services\Support\NewResponseFormConfigService;
use App\Services\Support\StoreSupportTicketService;
use App\Queries\Support\ShowResponseQuery;
use App\Queries\Support\ShowSupportTicketQuery;
use App\Services\Support\RespondToSupportService;
use App\Services\Support\MarkAsReadService;
use App\Utilities\Auth;

class UserSupportController extends Controller
{

    public function index(Request $request)
    {

        $request->validate([

            'status' => 'required|string',

        ]);

        return ListMySupportTicketsService::listMySupportTickets($request);

    }

    public function newTicketFormConfig()
    {

        return NewTicketFormConfigService::getNewTicketFormConfig();

    }

    public function newResponseFormConfig()
    {

        return NewResponseFormConfigService::getNewResponseFormConfig();

    }

    public function store(Request $request)
    {

        return StoreSupportTicketService::storeSupportTicket($request);

    }

    public function show(int $id)
    {

        return ShowSupportTicketQuery::showSupportTicket($id);

    }

    public function respondToSupport(Request $request)
    {

        return RespondToSupportService::respondToSupport($request);

    }

    public function showResponse(Request $request)
    {

        $user_id = Auth::id();

        $request->validate([

            'ticket_response_id' => ['integer', 'required', new TicketResponseBelongsToUser($user_id)]

        ]);

        return ShowResponseQuery::showResponse($request);

    }

    public function markAsRead(Request $request)
    {

        $user_id = Auth::id();

        $request->validate([

            'ticket_response_id' => ['integer', 'required', new TicketResponseBelongsToUser($user_id)]

        ]);

        return MarkAsReadService::markAsRead($request);

    }

}
