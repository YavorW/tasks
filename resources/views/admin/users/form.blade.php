<?php
use App\Models\User;
?>
<x-layout :page=$page>
    <div class="container">
        <x-form :action="$action" :method="$method">
            @isset($user)
            <h3>{{ $user->name }}</h3>

            <x-form.input iname="email" label="Email" disabled value="{{ $user->email }}" />

            <x-form.select iname="acc_type" label="User Role">
                <option value="0">Обикновен Потребител</option>
                <option value="{{ User::role_team }}" {{ $user->acc_type == User::role_team ? 'selected' : '' }}>
                    Част от екипа
                </option>
                <option value="{{ User::role_admin }}" {{ $user->acc_type == User::role_admin ? 'selected' : '' }}>
                    Admin
                </option>
            </x-form.select>
            @endisset
        </x-form>
    </div>
</x-layout>