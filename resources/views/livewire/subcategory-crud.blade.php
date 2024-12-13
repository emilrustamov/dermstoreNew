<div class="container">
    <div>
     
        <h1 class="text-xl font-bold mb-4">Manage Subcategories</h1>

        @if (Auth::check() && Auth::user()->isAdmin())
            <form wire:submit.prevent="{{ $editId ? 'update' : 'create' }}" class="mb-4">
                <input type="text" wire:model="name" placeholder="Enter subcategory name" class="border rounded px-2 py-1">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <h3 class="mt-2">Select Categories</h3>
                @foreach ($categories as $category)
                    <label class="inline-block mr-4">
                        <input type="checkbox" wire:model="categoryIds" value="{{ $category->id }}">
                        {{ $category->name }}
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
                    <th class="border border-gray-300 px-2 py-1">Name</th>
                    <th class="border border-gray-300 px-2 py-1">Categories</th>
                    @if (Auth::check() && Auth::user()->isAdmin())
                        <th class="border border-gray-300 px-2 py-1">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($subcategories as $subcategory)
                    <tr>
                        <td class="border border-gray-300 px-2 py-1">{{ $subcategory->id }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $subcategory->name }}</td>
                        <td class="border border-gray-300 px-2 py-1">
                            {{ implode(', ', $subcategory->categories) }}
                        </td>
                        @if (Auth::check() && Auth::user()->isAdmin())
                            <td class="border border-gray-300 px-2 py-1">
                                <button wire:click="edit({{ $subcategory->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $subcategory->id }})" class="bg-red-500 text-white px-2 py-1 rounded">
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
