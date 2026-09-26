@props(['is'=>'a'])

<{{$is}} {{ $attributes->merge([
    'class' => 'block border border-border rounded-lg bg-card p-6'
]) }}>
    {{ $slot }}
</{{$is}}>
