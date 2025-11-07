<div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden" id="{{ $id ?? 'default-modal' }}">
    <div class="bg-white rounded-lg shadow-lg w-1/3">
        <div class="p-4 border-b">
            <h2 class="text-lg font-semibold">{{ $title ?? 'Modal Title' }}</h2>
        </div>
        <div class="p-4">
            {{ $slot }}
        </div>
        <div class="p-4 border-t flex justify-end space-x-2">
            <button onclick="document.getElementById('{{ $id ?? 'default-modal' }}').classList.add('hidden')" class="px-4 py-2 bg-gray-500 text-white rounded">Tutup</button>
        </div>
    </div>
</div>
