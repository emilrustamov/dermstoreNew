<div class="container my-4">
    <div>
        <h1 class="text-xl font-bold mb-4">Ranges</h1>

        @if (Auth::check() && Auth::user()->isAdmin())
            <form wire:submit.prevent="{{ $editId ? 'update' : 'create' }}" class="mb-4">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-2 py-1">Название</th>
                            <th class="border border-gray-300 px-2 py-1">Выберите бренды</th>
                            <th class="border border-gray-300 px-2 py-1">Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 px-2 py-1" rowspan="{{ count($brands) }}">
                                <input type="text" wire:model="name" placeholder="Введите название range" class="border rounded px-2 py-1">
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </td>
                            <td class="border border-gray-300 px-2 py-1">
                                @if (isset($brands[0]))
                                    <label class="inline-block mr-4">
                                        <input type="checkbox" wire:model="brandIds" value="{{ $brands[0]->id }}">
                                        {{ $brands[0]->name }}
                                    </label>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-2 py-1" rowspan="{{ count($brands) }}">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">
                                    <i class="fas fa-save"></i>
                                </button>
                            </td>
                        </tr>
                        @for ($i = 1; $i < count($brands); $i++)
                            <tr>
                                <td class="border border-gray-300 px-2 py-1">
                                    @if (isset($brands[$i]))
                                        <label class="inline-block mr-4">
                                            <input type="checkbox" wire:model="brandIds" value="{{ $brands[$i]->id }}">
                                            {{ $brands[$i]->name }}
                                        </label>
                                    @endif
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </form>
        @endif

        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-2 py-1">ID</th>
                    <th class="border border-gray-300 px-2 py-1">Название</th>
                    <th class="border border-gray-300 px-2 py-1">Бренды</th>
                    @if (Auth::check() && Auth::user()->isAdmin())
                        <th class="border border-gray-300 px-2 py-1">Действия</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($ranges as $range)
                    <tr>
                        <td class="border border-gray-300 px-2 py-1">{{ $range->id }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $range->name }}</td>
                        <td class="border border-gray-300 px-2 py-1">
                            {{ implode(', ', $range->brands) }}
                        </td>
                        @if (Auth::check() && Auth::user()->isAdmin())
                            <td class="border border-gray-300 px-2 py-1">
                                <button wire:click="edit({{ $range->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $range->id }})" class="bg-red-500 text-white px-2 py-1 rounded">
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
