<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('tanggal_mulai', 'desc')->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $event = Event::create($request->all());

        if ($request->has('is_active')) {
            Event::where('tenant_id', Auth::user()->tenant_id)
                ->where('id', '!=', $event->id)
                ->update(['is_active' => false]);
            $event->update(['is_active' => true]);
            session(['active_event_id' => $event->id]);
        }

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dibuat.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'nama_event' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
        ]);

        $event->update($request->except('is_active'));

        if ($request->has('is_active')) {
            Event::where('tenant_id', Auth::user()->tenant_id)
                ->where('id', '!=', $event->id)
                ->update(['is_active' => false]);
            $event->update(['is_active' => true]);
            session(['active_event_id' => $event->id]);

        } else {
            $event->update(['is_active' => false]);
            if (session('active_event_id') == $event->id) {
                session()->forget('active_event_id');
            }
        }

        return redirect()->route('admin.events.index')
            ->with('success', "Status event '{$event->nama_event}' berhasil diperbarui.");
    }

    public function destroy(Event $event)
    {
        // Hapus event dari session jika sedang aktif
        if (session('active_event_id') == $event->id) {
            session()->forget('active_event_id');
        }

        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil dihapus.');
    }

    public function setActive(Event $event)
    {
        // cek milik tenant yang sama
        if ($event->tenant_id !== Auth::user()->tenant_id) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }

        // Nonaktifkan event lain di tenant yang sama (per tenant, not global)
        Event::where('tenant_id', Auth::user()->tenant_id)
            ->where('id', '!=', $event->id)
            ->update(['is_active' => false]);

        // Aktifkan event yang dipilih
        $event->update(['is_active' => true]);

        //  Set session untuk admin 
        session(['active_event_id' => $event->id]);

        return redirect()->route('admin.events.index')
            ->with('success', "Event '{$event->nama_event}' sekarang aktif untuk tenant Anda.");
    }
}