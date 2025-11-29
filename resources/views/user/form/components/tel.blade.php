
@php
    $details = json_decode($form_item->details ?? '{}', true);
    $hyphen_flg = $details['hyphen_flg']?? 1;
@endphp

<div class="form-item-detail-contents">
    <div class="form-item-detail-title">
        【ハイフンを入力させるか】
    </div>
    <div class="form-item-detail-content">
        <x-input-radio
            name="hyphen_flg"
            :options="\App\Consts\CommonConst::TEL_HYPHEN_LIST"
            :checked="$hyphen_flg"
        />
    </div>
</div>
