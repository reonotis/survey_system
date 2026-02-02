@props(['selectable_count'])

@php
    $value_list = $form_item->value_list;
    $selectable_count = $selectable_count[$form_item->id]?? [];
@endphp

<div class="support-msg">
    {!! nl2br($form_item->annotation_text) !!}
</div>

<div class="checkbox-group" data-form-item-id="{{ $form_item->id }}" data-max-count="{{ $form_item->details['max_count'] ?? '' }}">
    <div class="form-item-detail-content">
        <div class="flex flex-wrap gap-2">
            @foreach($value_list as $value)
                @php
                    $is_initially_disabled = !is_null($value['count']) && isset($selectable_count[$value['name']]) && $selectable_count[$value['name']] >= $value['count'];
                @endphp
                <label>
                    <input type="checkbox" class="form-check-input" name="checkbox_{{ $form_item->id }}[]" value="{{ $value['name'] }}"
                        @if($is_initially_disabled)
                            disabled data-initially-disabled="1"
                        @endif
                        >
                    {{ $value['name'] }}
                </label>
            @endforeach
        </div>
    </div>
</div>
