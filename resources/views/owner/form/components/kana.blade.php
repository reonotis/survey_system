
@php
    $details = json_decode($form_item->details ?? '{}', true);
    $name_type = $details['name_type']?? 1;
@endphp

<div class="form-item-detail-contents">
    <div class="form-item-detail-title">
        【セイとメイを別々にするか】
    </div>
    <div class="form-item-detail-content">
        <label>
            <input type="radio" name="name_type" value="1" @if($name_type == 1) checked="checked" @endif>
            セイメイを分ける
        </label>
        <label>
            <input type="radio" name="name_type" value="2" @if($name_type == 2) checked="checked" @endif>
            セイメイを分けない
        </label>
    </div>
</div>
