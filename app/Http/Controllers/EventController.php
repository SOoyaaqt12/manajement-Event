<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events =  Event::all();
     
        return view('event.index',  compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('event.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
{
    // Validasi input
    $request->validate([
        'nama_event' => 'required|string',
        'deskripsi' => 'required|min:10',
        'tanggal' => 'required',
        'lokasi' => 'required|string',
        'foto' => 'required|image|mimes:png,jpg,jpeg,svg|max:2048',
    ]);

    // Proses penyimpanan file foto
    if ($request->hasFile('foto')) {
        $foto = $request->file('foto')->store('foto', 'public');
        $fotoPath = 'storage/' . $foto;
    } else {
        $fotoPath = null; // Jika tidak ada foto, set nilai default null
    }

    // Simpan data ke database
    Event::create([
        'nama_event' => $request->nama_event,
        'deskripsi' => $request->deskripsi,
        'tanggal' => $request->tanggal,
        'lokasi' => $request->lokasi,
        'foto' => $fotoPath ? basename($fotoPath) : null, // Jika ada foto simpan nama file, jika tidak simpan null
    ]);

    return redirect('event')->with('success', 'Tambah Data Berhasil');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $events = Event::findorFail($id);

        return view('event.show', compact('events'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $events = Event::find($id);

        if (!$events) {
            return redirect()->back()->with('errer','Event tidak ditemukan');
        }

        return view('event.edit', compact('events'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_event' => 'required',
            'deskripsi' => 'required|string|min:10',
            'tanggal' => 'required|date',
            'lokasi'=> 'required',
            'foto'=>  'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $events = Event::findOrFail($id);

        if ($request->hasFile('foto')) {

            $foto = $request->file('foto');
            $foto->store('foto', 'public', $events->foto);

            Storage::delete(['storage/foto', $events->foto]);

            $events->update([
                'foto' => $foto->basename(),
                'nama_event' =>  $request->nama_event,
                'tanggal' =>  $request->tanggal,
                'lokasi' =>  $request->lokasi,
                'deskripsi' =>  $request->deskripsi,
            ]);
        } else {
            $events->update([
                'foto' => $request->foto,
                'nama_events' => $request->nama_events,
                'tanggal' => $request->tanggal,
                'lokasi' => $request->lokasi,
                'deskripsi' => $request->deskripsi,

            ]);
        }

        return redirect()->route('event.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $events = Event::find($id);

        if (!$events) {
            return redirect()->back()->with('error','Event tidak ditemukan');
        }

        // hapus foto dari penyimpanan jika ada
        if ($events->foto && Storage::exists('public/foto/', $events->foto)) {
            Storage::delete('public/foto/' . $events->foto);
        }

        // hapus data dari database
        $events->delete();

        return redirect()->route('event.index')->with('succes','data berhasil dihapus');
    }
}
