<div class="container my-4">
    <div>
        <h1 class="text-xl font-bold mb-4">Фильтры</h1>

        @if (Auth::check() && Auth::user()->isAdmin())
            <form wire:submit.prevent="{{ $editId ? 'update' : 'create' }}" class="mb-4">
                <input type="text" wire:model="name" placeholder="Введите название фильтра" class="border rounded px-2 py-1">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <textarea wire:model="values" placeholder="Вносите значения через ЗАПЯТУЮ" class="border rounded px-2 py-1 w-full"></textarea>
                @error('values') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

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
                    <th class="border border-gray-300 px-2 py-1">Значения</th>
                    @if (Auth::check() && Auth::user()->isAdmin())
                        <th class="border border-gray-300 px-2 py-1">Действия</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($filters as $filter)
                    <tr>
                        <td class="border border-gray-300 px-2 py-1">{{ $filter->id }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $filter->name }}</td>
                        <td class="border border-gray-300 px-2 py-1">
                            {{ implode(', ', $filter->values) }}
                        </td>
                        @if (Auth::check() && Auth::user()->isAdmin())
                            <td class="border border-gray-300 px-2 py-1">
                                <button wire:click="edit({{ $filter->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $filter->id }})" class="bg-red-500 text-white px-2 py-1 rounded">
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
