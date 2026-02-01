@props(['max_count'])

@php
    $value_list = $form_item->value_list;
    $max_count = $max_count[$form_item->id]?? [];
@endphp

<div class="support-msg">
    {!! nl2br($form_item->annotation_text) !!}
</div>

<div>
    <div class="form-item-detail-content">
        <div class="flex flex-wrap gap-2">
            @foreach($value_list as $value)
                <label>
                    <input type="checkbox" class="form-check-input" name="checkbox_{{ $form_item->id }}[]" value="{{ $value['name'] }}"
                        @if(!is_null($value['count']) && isset($max_count[$value['name']]) && $max_count[$value['name']] >= $value['count'])
                            disabled
                        @endif
                        >
                    {{ $value['name'] }}
                </label>
            @endforeach
        </div>
    </div>
</div>
