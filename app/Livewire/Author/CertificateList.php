<?php

namespace App\Livewire\Author;

use App\Models\Certificate;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CertificateList extends Component
{
    public function render()
    {
        $user = Auth::user();
        
        // Get certificates for:
        // 1. Direct certificates (participant, reviewer, committee)
        // 2. Certificates from papers (presenter)
        $certificates = Certificate::where('user_id', $user->id)
            ->with(['conference', 'paper'])
            ->latest('issued_at')
            ->get();
        
        return view('livewire.author.certificate-list', compact('certificates'))
            ->layout('layouts.app');
    }
}
