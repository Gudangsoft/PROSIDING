<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use App\Models\Paper;
use App\Models\Payment;
use App\Models\User;
use App\Models\AbstractSubmission;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParticipantsExport;
use App\Exports\AcceptedPapersExport;
use App\Exports\RevenueExport;

class ReportExportController extends Controller
{
    public function participants(Request $request): mixed
    {
        $confId   = $request->conference_id;
        $filename = 'laporan-peserta-' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new ParticipantsExport($confId), $filename);
    }

    public function papers(Request $request): mixed
    {
        $confId   = $request->conference_id;
        $status   = $request->status;
        $filename = 'laporan-paper-' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new AcceptedPapersExport($confId, $status), $filename);
    }

    public function revenue(Request $request): mixed
    {
        $confId    = $request->conference_id;
        $startDate = $request->start_date;
        $endDate   = $request->end_date;
        $filename  = 'laporan-pendapatan-' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new RevenueExport($confId, $startDate, $endDate), $filename);
    }
}
