@props([
    'label' => 'Field Label',
    'name' => 'field_name',
    'type' => 'text',
    'value' => '',
    'required' => true,
])

<div class="space-y-2 mb-5">
    @if ($label)
    <label for="{{ $name }}" class="label">
        {{ $label }}
    </label>
    @endif

    @if ($type === "textarea")
    <textarea
        class="input"
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->except('required') }}
        @if($required) required @endif
    >{{ old($name, $value) }}</textarea>

    @else
    <input
        type="{{ $type }}"
        class="input"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->except('required') }}
        @if($required) required @endif
    >
    @endif

    <x-form.error name="{{ $name }}" />
</div>
