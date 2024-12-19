<div class="container my-4">
    <div>
      
        <h1 class="text-xl font-bold mb-4">Разделы</h1>

        @if (Auth::check() && Auth::user()->isAdmin())
            <!-- Create / Edit Form -->
            <form wire:submit.prevent="{{ $editId ? 'update' : 'create' }}" class="mb-4">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-2 py-1">Название</th>
                            <th class="border border-gray-300 px-2 py-1">Избранные ссылки</th>
                            <th class="border border-gray-300 px-2 py-1">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 px-2 py-1" rowspan="{{ count($allLinks) }}">
                                <input type="text" wire:model="name" placeholder="Введите название раздела" class="border rounded px-2 py-1">
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </td>
                            <td class="border border-gray-300 px-2 py-1">
                                @if (isset($allLinks[0]))
                                    <div>
                                        <input type="checkbox" wire:model="selectedLinks" value="{{ $allLinks[0]['id'] }}">
                                        <label>{{ $allLinks[0]['name'] }}</label>
                                    </div>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-2 py-1" rowspan="{{ count($allLinks) }}">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">
                                    <i class="fas fa-save"></i>
                                </button>
                            </td>
                        </tr>
                        @for ($i = 1; $i < count($allLinks); $i++)
                            <tr>
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
                @foreach ($sections as $section)
                    <tr>
                        <td class="border border-gray-300 px-2 py-1">{{ $section->id }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $section->name }}</td>
                        @if (Auth::check() && Auth::user()->isAdmin())
                            <td class="border border-gray-300 px-2 py-1">
                                <button wire:click="edit({{ $section->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $section->id }})" class="bg-red-500 text-white px-2 py-1 rounded">
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
