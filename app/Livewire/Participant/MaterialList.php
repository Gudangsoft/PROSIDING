<?php

namespace App\Livewire\Participant;

use App\Models\Conference;
use App\Models\ConferenceMaterial;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MaterialList extends Component
{
    public function render()
    {
        $user = Auth::user();

        // Ambil semua conference_id yang sudah diverifikasi pembayarannya
        $verifiedConferenceIds = Payment::where('user_id', $user->id)
            ->where('type', Payment::TYPE_PARTICIPANT)
            ->where('status', 'verified')
            ->with('registrationPackage')
            ->get()
            ->map(function ($payment) {
                // Ambil conference_id dari registration package
                if ($payment->registrationPackage) {
                    return $payment->registrationPackage->conference_id;
                }
                // Fallback: cari konferensi aktif jika tidak ada package
                return Conference::active()->value('id');
            })
            ->filter()
            ->unique()
            ->values();

        // Ambil data konferensi beserta materinya, dikelompokkan per konferensi
        $conferenceGroups = collect();

        if ($verifiedConferenceIds->isNotEmpty()) {
            $conferences = Conference::whereIn('id', $verifiedConferenceIds)
                ->orderByDesc('start_date')
                ->get();

            foreach ($conferences as $conference) {
                $materials = ConferenceMaterial::where('conference_id', $conference->id)
                    ->active()
                    ->orderBy('sort_order')
                    ->orderBy('type')
                    ->get()
                    ->groupBy('type');

                $conferenceGroups->push([
                    'conference' => $conference,
                    'materials'  => $materials,
                    'total'      => $materials->flatten()->count(),
                ]);
            }
        }

        $hasAccess = $verifiedConferenceIds->isNotEmpty();

        return view('livewire.participant.material-list', compact('conferenceGroups', 'hasAccess'))
            ->layout('layouts.app');
    }
}
