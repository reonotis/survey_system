@php
    $details = json_decode($form_item->details ?? '{}', true);
    $name_type = $details['name_type'] ?? 1;
@endphp

<div class="support-msg">
    {!! nl2br($form_item->annotation_text) !!}
</div>

<div>
    @if($name_type == 1)
        <div style="display: flex; gap: .5rem;">
            <x-input-text name="sei_kana" class="w-full" :error="$errors->has('sei_kana')" :value="old('sei_kana')" placeholder="タナカ"/>
            <x-input-text name="mei_kana" class="w-full" :error="$errors->has('mei_kana')" :value="old('mei_kana')" placeholder="タロウ"/>
        </div>
        <x-input-error :messages="$errors->get('sei_kana')" class="mt-1"/>
        <x-input-error :messages="$errors->get('mei_kana')" class="mt-1"/>
    @else
        <x-input-text name="kana" class="w-full" :error="$errors->has('kana')" :value="old('kana')" placeholder="タナカタロウ"/>
        <x-input-error :messages="$errors->get('kana')" class="mt-1"/>
    @endif
</div>
