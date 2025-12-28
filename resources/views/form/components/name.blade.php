@php
    $details = $form_item->details;
    $name_separate_type = $details['name_separate_type'] ?? 1;
@endphp

<div class="support-msg">
    {!! nl2br($form_item->annotation_text) !!}
</div>

<div>
    @if($name_separate_type == 1)
        <div style="display: flex; gap: .5rem;">
            <x-input-text name="sei" class="w-full" :error="$errors->has('sei')" :value="old('sei')"
                          placeholder="田中"/>
            <x-input-text name="mei" class="w-full" :error="$errors->has('mei')" :value="old('mei')"
                          placeholder="太郎"/>
        </div>
        <x-input-error :messages="$errors->get('sei')" class="mt-1"/>
        <x-input-error :messages="$errors->get('mei')" class="mt-1"/>
    @else
        <x-input-text name="name" class="w-full" :error="$errors->has('name')" :value="old('name')"
                      placeholder="田中太郎"/>
        <x-input-error :messages="$errors->get('name')" class="mt-1"/>
    @endif
</div>
