<div class="container my-4">
    <div>
        <h1 class="text-xl font-bold mb-4">Характеристики</h1>

        @if (Auth::check() && Auth::user()->isAdmin())
            <!-- Create / Edit Form -->
            <form wire:submit.prevent="{{ $editId ? 'update' : 'create' }}" class="mb-4">
                <input type="text" wire:model="name" placeholder="Введите название характеристики" class="border rounded px-2 py-1">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">
                    <i class="fas fa-save"></i>
                </button>
            </form>
        @endif

        <!-- Table -->
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-2 py-1">ID</th>
                    <th class="border border-gray-300 px-2 py-1">Название</th>
                    @if (Auth::check() && Auth::user()->isAdmin())
                        <th class="border border-gray-300 px-2 py-1">Действия</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(isset($characteristics) && count($characteristics) > 0)
                    @foreach ($characteristics as $characteristic)
                        <tr>
                            <td class="border border-gray-300 px-2 py-1">{{ $characteristic->id }}</td>
                            <td class="border border-gray-300 px-2 py-1">{{ $characteristic->name }}</td>
                            @if (Auth::check() && Auth::user()->isAdmin())
                                <td class="border border-gray-300 px-2 py-1">
                                    <button wire:click="edit({{ $characteristic->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="delete({{ $characteristic->id }})" class="bg-red-500 text-white px-2 py-1 rounded">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" class="border border-gray-300 px-2 py-1 text-center">No characteristics found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>