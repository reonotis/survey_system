@php
    $details = json_decode($form_item->details ?? '{}', true);
    $hyphen_flg = $details['hyphen_flg']?? 1;

    $placeholder = ($hyphen_flg == 1) ? '090-1234-5678' : '09012345678' ;
@endphp

<div class="support-msg">
    {!! nl2br($form_item->annotation_text) !!}
</div>

<div>
    <x-input-text name="tel" class="w-full" :error="$errors->has('tel')" :value="old('tel')"
                  placeholder="{{ $placeholder }}"/>

    <x-input-error :messages="$errors->get('tel')" class="mt-1"/>
</div>
