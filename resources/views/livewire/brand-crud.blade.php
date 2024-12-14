<div class="container">
    <div>
        <h1 class="text-xl font-bold mb-4">Бренды</h1>

        @if (Auth::check() && Auth::user()->isAdmin())
            <form wire:submit.prevent="{{ $editId ? 'обновить' : 'создать' }}" class="mb-4">
                <input type="text" wire:model="name" placeholder="Введите название бренда" class="border rounded px-2 py-1">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">
                    <i class="fas fa-save"></i>
                </button>
            </form>
        @endif

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
                @foreach ($brands as $brand)
                    <tr>
                        <td class="border border-gray-300 px-2 py-1">{{ $brand->id }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $brand->name }}</td>
                        @if (Auth::check() && Auth::user()->isAdmin())
                            <td class="border border-gray-300 px-2 py-1">
                                <button wire:click="edit({{ $brand->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $brand->id }})" class="bg-red-500 text-white px-2 py-1 rounded">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
