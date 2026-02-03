@php
    $value_list = $form_item->value_list;
@endphp

<div class="support-msg">
    {!! nl2br($form_item->annotation_text) !!}
</div>

@dump($errors)
<div>
    <div class="form-item-detail-content">
        <select name="select_box_{{ $form_item->id }}" id="" class="input-box w-full">

            @if(!$form_item->field_required)
                <option value="">選択してください</option>
            @endif

            @foreach($value_list as $value)
                <option value="{{ $value['name'] }}">
                    {{ $value['name'] }}
                </option>
            @endforeach

        </select>
    </div>
</div>
