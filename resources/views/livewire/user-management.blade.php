<div class="container my-4">
    <h1 class="mb-4">Пользователи</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Форма создания/редактирования пользователя -->
    <form wire:submit.prevent="{{ $editId ? 'update' : 'create' }}">
        <div class="form-group">
            <label for="name">Имя</label>
            <input type="text" id="name" wire:model="name" class="form-control">
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" wire:model="email" class="form-control">
            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="password" id="password" wire:model="password" class="form-control">
            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Пароль повторно</label>
            <input type="password" id="password_confirmation" wire:model="password_confirmation" class="form-control">
        </div>

        <div class="form-group">
            <label for="is_admin">Роль</label>
            <select id="is_admin" wire:model="is_admin" class="form-control">
                <option value="0">Демонстрация</option>
                <option value="1">Админ</option>
            </select>
            @error('is_admin') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-success mt-3">{{ $editId ? 'Update' : 'Create' }}</button>
        @if ($editId)
            <button type="button" wire:click="resetForm" class="btn btn-secondary mt-3">Cancel</button>
        @endif
    </form>

    <hr>

    <!-- Таблица пользователей -->
    <table class="table mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Эмейл</th>
                <th>Роль</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->is_admin ? 'Admin' : 'User' }}</td>
                    <td>
                        <button wire:click="редактировать({{ $user->id }})" class="btn btn-warning btn-sm">Edit</button>
                        <button wire:click="удалить({{ $user->id }})" class="btn btn-danger btn-sm"
                                onclick="return confirm('Вы уверены?')">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
