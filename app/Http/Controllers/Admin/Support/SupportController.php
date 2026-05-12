<?php

namespace App\Http\Controllers\Admin\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SupportReplyToTicketRequest;
use App\Queries\Admin\Support\ShowTicketQuery;
use App\Services\Support\FiltersForSupportService;
use App\Services\Support\ReplyFormConfigService;
use App\Services\Support\SupportReplyToTicketService;
use App\Services\Support\SupportTableDataService;
use App\Services\Support\CloseTicketService;


class SupportController extends Controller
{

    public function index(Request $request, SupportTableDataService $service)
    {
        $request->validate([

            'status' => 'required|integer'

        ]);

        return $service->getTickets($request);   

    }

    public function show(int $id)
    {

        return ShowTicketQuery::getTicket($id);

    }

    public function getSupportDataFilters()
    {

        return FiltersForSupportService::getFilters();

    }

    public function getSupportReplyFormConfig()
    {

        return ReplyFormConfigService::getReplyFormConfig();

    }

    public function supportReplyToTicket(SupportReplyToTicketRequest $request)
    {

        return SupportReplyToTicketService::storeSupportReplyToTicket($request);

    }

    public function closeTicket(Request $request)
    {

        return CloseTicketService::closeTicket($request);

    }

}
