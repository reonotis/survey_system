@php
    $gender_list = $form_item->details['gender_list'] ?? [];

    // gender_listに含まれる値だけをGENDER_LISTから抽出
    $available_options = [];
    foreach ($gender_list as $gender_id) {
        if (isset(\App\Consts\CommonConst::GENDER_LIST[$gender_id])) {
            $available_options[$gender_id] = \App\Consts\CommonConst::GENDER_LIST[$gender_id];
        }
    }

    $selected_value = old('gender_list', null);
@endphp

<div class="support-msg">
    {!! nl2br($form_item->annotation_text) !!}
</div>

<div>
    <div class="form-item-detail-content">
        <div class="flex flex-wrap gap-2">

            @foreach($available_options as $value => $label)
                <div>
                    <input
                        type="radio"
                        name="gender"
                        id="gender_{{ $value }}"
                        value="{{ $value }}"
                        @if($selected_value == $value) checked="checked" @endif
                    >
                    <label for="gender_{{ $value }}">{{ $label }}</label>
                </div>
            @endforeach
        </div>

        <x-input-error :messages="$errors->get('gender')" class="mt-1"/>
    </div>
</div>
