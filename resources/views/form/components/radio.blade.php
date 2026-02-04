@props(['selected_count'])

@php
    $value_list = $form_item->value_list;
    $selected_count = $selected_count[$form_item->id]?? [];
    $message_list = collect($errors->get('radio_' . $form_item->id . '*'))
        ->flatten()
        ->values()
        ->all();
@endphp

<div class="support-msg">
    {!! nl2br($form_item->annotation_text) !!}
</div>

<div>
    <div class="form-item-detail-content">
        <div class="flex flex-wrap gap-2">
            @foreach($value_list as $value)
                <label>
                    <input type="radio" class="form-check-input" name="radio_{{ $form_item->id }}" value="{{ $value['name'] }}"
                       @if(!is_null($value['count']) && isset($selected_count[$value['name']]) && $selected_count[$value['name']] >= $value['count'])
                           disabled
                        @endif>
                    {{ $value['name'] }}
                </label>
            @endforeach
        </div>

        <x-input-error :messages="$message_list" class="mt-1"/>
    </div>
</div>
