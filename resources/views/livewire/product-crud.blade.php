<div class="container">

    <div>

        <h1 class="text-xl font-bold mb-4">Товары</h1>

        @if (Auth::check() && Auth::user()->isAdmin())
            <!-- Create / Edit Form -->
            <form wire:submit.prevent="{{ $editId ? 'update' : 'create' }}" class="mb-4">
                <div class="flex mb-2 gap-3 align-items-center">
                    <div class="w-full md:w-1/2 mb-4 md:mb-0">
                        <label for="name" class="flex text-gray-700 font-bold mb-2">Название</label>
                        <input type="text" id="name" wire:model="name" placeholder="Введите название товара"
                            class="border rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 h-full">
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/2">
                        <label for="description" class="flex text-gray-700 font-bold mb-2">Описание</label>
                        <textarea id="description" wire:model="description" placeholder="Введите описание товара"
                            class="border rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 h-full"></textarea>
                        @error('description')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="w-full md:w-1/2">
                    <label for="image" class="flex text-gray-700 font-bold mb-2">Изображение</label>
                    <input type="file" id="image" wire:model="image" class="border rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500 h-full">
                
                    @if ($currentImage)
                        <div class="mt-2">
                            <p>Текущее изображение:</p>
                            <img src="{{ asset('storage/' . $currentImage) }}" alt="Изображение товара" style="max-height: 100px;">
                        </div>
                    @endif
                
                    @if ($image)
                        <div class="mt-2">
                            <p>Новое изображение:</p>
                            <img src="{{ $image->temporaryUrl() }}" alt="Новое изображение" style="max-height: 100px;">
                        </div>
                    @endif
                
                    @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                


                <h3 class="mt-2">Выберите раздел</h3>
                @foreach ($sections as $section)
                    <label class="inline-block mr-4">
                        <input type="checkbox" wire:model="sectionIds" value="{{ $section->id }}">
                        {{ $section->name }}
                    </label>
                @endforeach

                <h3 class="mt-2">Выберите категорию</h3>
                @foreach ($categories as $category)
                    <label class="inline-block mr-4">
                        <input type="checkbox" wire:model="categoryIds" value="{{ $category->id }}">
                        {{ $category->name }}
                    </label>
                @endforeach

                <h3 class="mt-2">Выберите подкатегорию</h3>
                @foreach ($subcategories as $subcategory)
                    <label class="inline-block mr-4">
                        <input type="checkbox" wire:model="subcategoryIds" value="{{ $subcategory->id }}">
                        {{ $subcategory->name }}
                    </label>
                @endforeach

                <h3 class="mt-2">Выберите бренд</h3>
                @foreach ($brands as $brand)
                    <label class="inline-block mr-4">
                        <input type="checkbox" wire:model="brandIds" value="{{ $brand->id }}">
                        {{ $brand->name }}
                    </label>
                @endforeach

                <h3 class="mt-2">Выберите фильтры</h3>
                @foreach ($filters as $filter)
                    <div class="mb-2">
                        <h4 class="font-bold">{{ $filter->name }}</h4>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($filter->values as $value)
                                <label class="inline-block mr-4">
                                    <input type="checkbox"
                                        wire:model="filterValues.{{ $filter->id }}.{{ $value }}"
                                        id="filter-{{ $filter->id }}-{{ $value }}">
                                    {{ $value }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach



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
                    <th class="border border-gray-300 px-2 py-1">Описание</th>
                    <th class="border border-gray-300 px-2 py-1">Изображение</th>
                    @if (Auth::check() && Auth::user()->isAdmin())
                        <th class="border border-gray-300 px-2 py-1">Действия</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td class="border border-gray-300 px-2 py-1">{{ $product->id }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $product->name }}</td>
                        <td class="border border-gray-300 px-2 py-1">{{ $product->description }}</td>
                        <td class="border border-gray-300 px-2 py-1">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" style="max-height: 50px;">
                            @else
                                Нет изображения
                            @endif
                        </td>
                        @if (Auth::check() && Auth::user()->isAdmin())
                            <td class="border border-gray-300 px-2 py-1">
                                <button wire:click="edit({{ $product->id }})"
                                    class="bg-yellow-500 text-white px-2 py-1 rounded">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button wire:click="delete({{ $product->id }})"
                                    class="bg-red-500 text-white px-2 py-1 rounded">
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
