@props(['form_item'])

@php
    $details = $form_item->details;
@endphp

<div>
    <div class="support-msg">
        {!! nl2br($form_item->annotation_text) !!}
    </div>

    <div class="w-full" style="max-height: 200px;padding: .25rem;border: solid 1px #bbb;overflow-y: scroll;">{!! nl2br($form_item->long_text) !!}</div>

    <div style="padding:.5rem;">
        <label>
            <input type="checkbox" name="terms" value="1" @if(old('terms')) checked @endif>{{ $details['label_name']?? '同意する' }}
        </label>
        <x-input-error :messages="$errors->get('terms')" class="mt-1"/>
    </div>
</div>
