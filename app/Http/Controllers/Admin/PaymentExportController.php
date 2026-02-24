<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PaymentsExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PaymentExportController extends Controller
{
    public function export(Request $request)
    {
        $type   = $request->get('type', '');
        $status = $request->get('status', '');

        $typePart   = $type   ? '-' . $type   : '';
        $statusPart = $status ? '-' . $status : '';
        $filename = 'payments' . $typePart . $statusPart . '-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new PaymentsExport($type, $status), $filename);
    }
}
