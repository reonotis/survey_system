@php
    $value_list = json_decode($form_item->value_list ?? '[]', true);
@endphp

<div class="support-msg">
    {!! nl2br($form_item->annotation_text) !!}
</div>

<div>
    <div class="form-item-detail-content">
        <div class="flex flex-wrap gap-2">
            @foreach($value_list as $value)
                <label>
                    <input type="checkbox" class="form-check-input" name="checkbox_{{ $form_item->id }}">
                    {{ $value }}
                </label>
            @endforeach
        </div>
    </div>
</div>
