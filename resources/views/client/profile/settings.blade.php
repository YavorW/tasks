<x-layout title="Профил">
    <div class="container">
        <x-form method="post" action="{{ route('profile') }}" enctype="multipart/form-data">
            @method('put')

            <div>
                <label for="name">Име</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="име" required="required"
                    value="{{ $user->name }}">
            </div>
            <div>
                <label for="email">Email</label>
                <input type="text" class="form-control disabled" id="email" disabled value="{{ $user->email }}">
            </div>
            <div class="{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="control-label">Парола</label>
                <input id="password" type="password" class="form-control" name="password">

                @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
            <div>
                <label for="password-confirm" class="control-label">Повтори парола</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
            </div>
        </x-form>
    </div>
</x-layout>