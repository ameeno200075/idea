@props([
    'label' => 'Field Label',
    'name' => 'field_name',
    'type' => 'text',
])

<div class="space-y-2 mb-4">

    <label for="{{ $name }}" class="label">
        {{ $label }}
    </label>

    <input
        type="{{ $type }}"
        class="input"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name) }}"
        {{ $attributes }}
    >

    @error($name)
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror

</div>
