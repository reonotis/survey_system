
@php
    $details = json_decode($form_item->details ?? '{}', true);
    $confirm_flg = $details['confirm_flg']?? 1;
@endphp

<div class="form-item-detail-contents">
    <div class="form-item-detail-title">
        【確認用のメールアドレス項目を設ける】
    </div>
    <div class="form-item-detail-content">
        <label>
            <input type="radio" name="confirm_flg" value="1" @if($confirm_flg == 1) checked="checked" @endif>
            確認用の項目を設ける
        </label>
        <label>
            <input type="radio" name="confirm_flg" value="2" @if($confirm_flg == 2) checked="checked" @endif>
            確認用の項目を設けない
        </label>
    </div>
</div>
