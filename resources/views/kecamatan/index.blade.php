<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kecamatan Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between mb-4">
                        <h3 class="text-lg font-semibold">All Kecamatan</h3>
                        <a href="{{ route('kecamatan.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Add New Kecamatan
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kecamatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Wilayah</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hotels</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($kecamatans as $kecamatan)
                                <tr>
                                    <td class="px-6 py-4">{{ $kecamatan->NamaKecamatan }}</td>
                                    <td class="px-6 py-4">{{ $kecamatan->Wilayah }}</td>
                                    <td class="px-6 py-4">{{ $kecamatan->hotels_count }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('kecamatan.show', $kecamatan) }}" class="text-blue-600 hover:underline mr-2">View</a>
                                        <a href="{{ route('kecamatan.edit', $kecamatan) }}" class="text-green-600 hover:underline mr-2">Edit</a>
                                        <form action="{{ route('kecamatan.destroy', $kecamatan) }}" method="POST" class="inline" 
                                            onsubmit="return confirm('Delete this kecamatan?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No kecamatan found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $kecamatans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
