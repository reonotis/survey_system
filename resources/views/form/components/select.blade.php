@props(['selected_count'])

@php
    $value_list = $form_item->value_list;
    $selected_count = $selected_count[$form_item->id]?? [];
    $message_list = collect($errors->get('select_box_' . $form_item->id . '*'))
        ->flatten()
        ->values()
        ->all();
@endphp

<div class="support-msg">
    {!! nl2br($form_item->annotation_text) !!}
</div>

<div>
    <div class="form-item-detail-content">
        <select name="select_box_{{ $form_item->id }}" id="" class="input-box w-full">

            @if(!$form_item->field_required)
                <option value="">選択してください</option>
            @endif

            @foreach($value_list as $value)
                @php
                    $disabled = false;
                    if(!is_null($value['count']) && $value['count'] >= 0 ) {
                        if($selected_count[$value['name']] >= $value['count']) {
                            $disabled = true;
                        }
                    }
                @endphp

                <option value="{{ $value['name'] }}"
                        @if($disabled) disabled @endif
                    >
                    {{ $value['name'] }}
                </option>
            @endforeach

        </select>

        <x-input-error :messages="$message_list" class="mt-1"/>
    </div>
</div>
