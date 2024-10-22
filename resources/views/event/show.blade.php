<x-app-layout>
    <div class="container p-6 my-16 mx-auto bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="container mx-auto p-4 text-black dark:text-gray-300">
            <h1 class="text-2xl font-bold mb-4">{{ $events->nama_event }}</h1>
                
            <div class="mb-4">
                <img src="{{ asset('storage/foto/' . $events->foto) }}" alt="{{ $events->nama_event }}" class="h-48 w-full object-contain mb-4">
            </div>
        
            <div class="mb-4">
                <strong>Deskripsi: </strong>
                <p>{{ $events->deskripsi }}</p>
            </div>
        
            <div class="mb-4">
                <strong>Tanggal: </strong>
                <p>{{ $events->tanggal }}</p>
            </div>
        
            <div class="mb-40">
                <strong>Lokasi: </strong>
                <p>{{ $events->lokasi }}</p>
            </div>
        
            <a href="{{ route('event.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                Kembali ke Daftar Event
            </a>
        </div>
    </div>
</x-app-layout>