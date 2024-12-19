<div class="container my-4">
    <div>
        <h1 class="text-xl font-bold mb-4">Подподкатегории</h1>

        @if (Auth::check() && Auth::user()->isAdmin())
            <!-- Форма создания / редактирования -->
            <form wire:submit.prevent="{{ $editId ? 'update' : 'create' }}" class="mb-4">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-2 py-1">Название</th>
                            <th class="border border-gray-300 px-2 py-1">Выберите подкатегории</th>
                            <th class="border border-gray-300 px-2 py-1">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 px-2 py-1" rowspan="{{ count($subcategories) }}">
                                <input type="text" wire:model="name" placeholder="Введите название подподкатегории" class="border rounded px-2 py-1">
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </td>
                            <td class="border border-gray-300 px-2 py-1">
                                @if (isset($subcategories[0]))
                                    <label class="inline-block mr-4">
                                        <input type="checkbox" wire:model="subcategoryIds" value="{{ $subcategories[0]->id }}">
                                        {{ $subcategories[0]->name }}
                                    </label>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-2 py-1" rowspan="{{ count($subcategories) }}">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">
                                    <i class="fas fa-save"></i>
                                </button>
                            </td>
                        </tr>
                        @for ($i = 1; $i < count($subcategories); $i++)
                            <tr>
                                <td class="border border-gray-300 px-2 py-1">
                                    @if (isset($subcategories[$i]))
                                        <label class="inline-block mr-4">
                                            <input type="checkbox" wire:model="subcategoryIds" value="{{ $subcategories[$i]->id }}">
                                            {{ $subcategories[$i]->name }}
                                        </label>
                                    @endif
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </form>
        @endif

        <!-- Таблица подподкатегорий -->
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
