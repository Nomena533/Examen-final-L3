{{--
    Champ de formulaire réutilisable.
    Paramètres : name, label, type (text), value (null), required (false), placeholder (''), hint (null), min (null)
--}}
@php
    $type = $type ?? 'text';
    $required = $required ?? false;
    $value = old($name, $value ?? null);
@endphp
<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-ink mb-1">
        {{ $label }} @if($required)<span class="text-late" aria-hidden="true">*</span>@endif
    </label>
    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" value="{{ $value }}"
           @if($required) required @endif
           @isset($min) min="{{ $min }}" @endisset
           @isset($placeholder) placeholder="{{ $placeholder }}" @endisset
           class="w-full rounded-md border px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-brand/40 focus:border-brand
                  {{ $errors->has($name) ? 'border-late' : 'border-ink-faint/50' }}">
    @isset($hint)
        <p class="mt-1 text-xs text-ink-soft">{{ $hint }}</p>
    @endisset
    @error($name)
        <p class="mt-1 text-xs text-late"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
    @enderror
</div>
