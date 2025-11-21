
@php
    $details = json_decode($form_item->details ?? '{}', true);
    $confirm_flg = $details['confirm_flg']?? 1;
@endphp

<div class="form-item-detail-contents">
    <div class="form-item-detail-title">
        【確認用のメールアドレス項目を設ける】
    </div>
    <div class="form-item-detail-content">
        <x-input-radio
            name="confirm_flg"
            :options="\App\Consts\CommonConst::EMAIL_CONFIRM_LIST"
            :checked="$confirm_flg"
        />
    </div>
</div>
