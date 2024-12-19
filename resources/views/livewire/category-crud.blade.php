<div class="container my-4">
    <div>
     
        <h1 class="text-xl font-bold mb-4">Категории</h1>

        @if (Auth::check() && Auth::user()->isAdmin())
            <!-- Форма создания / редактирования -->
            <form wire:submit.prevent="{{ $editId ? 'update' : 'create' }}" class="mb-4">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-2 py-1">Название</th>
                            <th class="border border-gray-300 px-2 py-1">Выберите разделы</th>
                            <th class="border border-gray-300 px-2 py-1">Популярные ссылки</th>
                            <th class="border border-gray-300 px-2 py-1">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 px-2 py-1" rowspan="{{ max(count($sections), count($allLinks)) }}">
                                <input type="text" wire:model="name" placeholder="Enter category name" class="border rounded px-2 py-1">
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </td>
                            <td class="border border-gray-300 px-2 py-1">
                                @if (isset($sections[0]))
                                    <label class="inline-block mr-4">
                                        <input type="checkbox" wire:model="sectionIds" value="{{ $sections[0]->id }}">
                                        {{ $sections[0]->name }}
                                    </label>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-2 py-1">
                                @if (isset($allLinks[0]))
                                    <div>
                                        <input type="checkbox" wire:model="selectedLinks" value="{{ $allLinks[0]['id'] }}">
                                        <label>{{ $allLinks[0]['name'] }}</label>
                                    </div>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-2 py-1" rowspan="{{ max(count($sections), count($allLinks)) }}">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">
                                    <i class="fas fa-save"></i>
                                </button>
                            </td>
                        </tr>
                        @for ($i = 1; $i < max(count($sections), count($allLinks)); $i++)
                            <tr>
                                <td class="border border-gray-300 px-2 py-1">
                                    @if (isset($sections[$i]))
                                        <label class="inline-block mr-4">
                                            <input type="checkbox" wire:model="sectionIds" value="{{ $sections[$i]->id }}">
                                            {{ $sections[$i]->name }}
                                        </label>
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-1">
                                    @if (isset($allLinks[$i]))
                                        <div>
                                            <input type="checkbox" wire:model="selectedLinks" value="{{ $allLinks[$i]['id'] }}">
                                            <label>{{ $allLinks[$i]['name'] }}</label>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </form>
        @endif

        <!-- Таблица категорий -->
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-2 py-1">ID</th>
                    <th class="border border-gray-300 px-2 py-1">Название</th>
                    <th class="border border-gray-300 px-2 py-1">Разделы</th>
                    @if (Auth::check() && Auth::user()->isAdmin())
                        <th class="border border-gray-300 px-2 py-1">Действия</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td class="border border-gray-300 px-2 py-1">{{ $category->id }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $category->name }}</td>
                        <td class="border border-gray-300 px-2 py-1">
                            @php
                                $sectionNames = collect($category->sections)
                                    ->map(fn($sectionId) => optional($sections->firstWhere('id', $sectionId))->name)
                                    ->filter()
                                    ->join(', ');
                            @endphp
                            {{ $sectionNames }}
                        </td>
                        @if (Auth::check() && Auth::user()->isAdmin())
                            <td class="border border-gray-300 px-2 py-1">
                                <button wire:click="edit({{ $category->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $category->id }})" class="bg-red-500 text-white px-2 py-1 rounded">
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
