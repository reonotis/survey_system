
@php
    $value_list = json_decode($form_item->value_list ?? '[]', true);
    $checkbox_text = old('checkbox_list', is_array($value_list) ? implode(PHP_EOL, $value_list) : '');
@endphp

<div class="form-item-detail-contents">
    <div class="form-item-detail-title">
        【選択可能項目】
    </div>
    <div class="form-item-detail-content">
        <p class="support-msg">改行を利用して複数の選択肢を入力して下さい</p>
        <p class="support-msg">1行が一つの選択肢となります</p>
        <textarea name="checkbox_list" class="input-box w-full h-20">{{ $checkbox_text }}</textarea>
        <x-input-error :messages="$errors->get('checkbox_list')" class="mt-2" />
    </div>
</div>
