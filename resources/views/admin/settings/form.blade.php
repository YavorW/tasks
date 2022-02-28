<x-layout :page=$page>
    <div class="container">
        <x-form :action="$action" :method="$method">
            <x-form.input name="key" label="Key" required
                value="{{ old('key', isset($setting) ? $setting->key : '') }}" />

            <x-form.textarea name="value" label="value" required>{{ old('value', isset($setting) ? $setting->value : '')
                }}</x-form.textarea>
        </x-form>
    </div>
</x-layout>