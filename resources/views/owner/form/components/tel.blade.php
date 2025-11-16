
@php
    $details = json_decode($form_item->details ?? '{}', true);
    $hyphen_flg = $details['hyphen_flg']?? 1;
@endphp

<div class="form-item-detail-contents">
    <div class="form-item-detail-title">
        【ハイフンを入力させるか】
    </div>
    <div class="form-item-detail-content">
        <label>
            <input type="radio" name="hyphen_flg" value="1" @if($hyphen_flg == 1) checked="checked" @endif>
            ハイフンを入力させる
        </label>
        <label>
            <input type="radio" name="hyphen_flg" value="2" @if($hyphen_flg == 2) checked="checked" @endif>
            ハイフンを入力させない
        </label>
    </div>
</div>
