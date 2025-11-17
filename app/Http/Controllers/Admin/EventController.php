<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

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

        // Logika untuk status 'is_active'
        if ($request->has('is_active')) {
            // Set event lain menjadi tidak aktif
            Event::where('id', '!=', $event->id)->update(['is_active' => false]);
            $event->update(['is_active' => true]);
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
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $event->update($request->except('is_active'));

        // Logika untuk status 'is_active'
        if ($request->has('is_active')) {
            // Set event lain menjadi tidak aktif
            Event::where('id', '!=', $event->id)->update(['is_active' => false]);
            $event->update(['is_active' => true]);
        } else {
            $event->update(['is_active' => false]);
        }

        return redirect()->route('admin.events.index')
                         ->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        // Hati-hati: Menghapus event bisa merusak relasi data
        // Tambahkan proteksi jika diperlukan
        
        $event->delete();
        return redirect()->route('admin.events.index')
                         ->with('success', 'Event berhasil dihapus.');
    }
}