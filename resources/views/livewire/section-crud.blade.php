<div class="container">
    <div>
      
        <h1 class="text-xl font-bold mb-4">Manage Sections</h1>

        @if (Auth::check() && Auth::user()->isAdmin())
            <!-- Create / Edit Form -->
            <form wire:submit.prevent="{{ $editId ? 'update' : 'create' }}" class="mb-4">
                <input type="text" wire:model="name" placeholder="Enter section name" class="border rounded px-2 py-1">
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
                    <th class="border border-gray-300 px-2 py-1">Name</th>
                    @if (Auth::check() && Auth::user()->isAdmin())
                        <th class="border border-gray-300 px-2 py-1">Actions</th>
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
