<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentorDashboardController extends Controller
{
    private function getActiveEvent()
    {
        return Event::where('tenant_id', Auth::user()->tenant_id)
                      ->where('is_active', true)
                      ->first();
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $activeEvent = $this->getActiveEvent();

        if (!$activeEvent) {
            return view('mentor.dashboard-no-event');
        }

        $query = $request->input('query');

        $results = Mahasiswa::query()
            ->where('event_id', $activeEvent->id)
            ->with(['alergi', 'kelompok'])
            ->when($query, function ($q, $query) {
                $q->where(function ($q) use ($query) {
                    $q->where('nama', 'LIKE', "%{$query}%")
                        ->orWhere('nim', 'LIKE', "%{$query}%")
                        ->orWhereHas('kelompok', function ($kelompokQuery) use ($query) {
                            $kelompokQuery->where('nama', 'LIKE', "%{$query}%");
                        });
                });
            })
            ->orderBy('nama')
            ->paginate(15)
            ->appends($request->except('page'));

        return view('mentor.dashboard', compact('user', 'results', 'query'));
    }
}
