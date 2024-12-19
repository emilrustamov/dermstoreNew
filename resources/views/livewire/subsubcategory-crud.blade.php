<div class="container my-4">
    <div>
        <h1 class="text-xl font-bold mb-4">Подподкатегории</h1>

        @if (Auth::check() && Auth::user()->isAdmin())
            <form wire:submit.prevent="{{ $editId ? 'update' : 'create' }}" class="mb-4">
                <input type="text" wire:model="name" placeholder="Введите название подподкатегории" class="border rounded px-2 py-1">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <h3 class="mt-2">Выберите подкатегории</h3>
                @foreach ($subcategories as $subcategory)
                    <label class="inline-block mr-4">
                        <input type="checkbox" wire:model="subcategoryIds" value="{{ $subcategory->id }}">
                        {{ $subcategory->name }}
                    </label>
                @endforeach

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
                    <th class="border border-gray-300 px-2 py-1">Подкатегории</th>
                    @if (Auth::check() && Auth::user()->isAdmin())
                        <th class="border border-gray-300 px-2 py-1">Действия</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($subsubcategories as $subsubcategory)
                    <tr>
                        <td class="border border-gray-300 px-2 py-1">{{ $subsubcategory->id }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $subsubcategory->name }}</td>
                        <td class="border border-gray-300 px-2 py-1">
                            {{ implode(', ', $subsubcategory->subcategories) }}
                        </td>
                        @if (Auth::check() && Auth::user()->isAdmin())
                            <td class="border border-gray-300 px-2 py-1">
                                <button wire:click="edit({{ $subsubcategory->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $subsubcategory->id }})" class="bg-red-500 text-white px-2 py-1 rounded">
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