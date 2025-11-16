@php
    $details = json_decode($form_item->details ?? '{}', true);
    $post_code_use_type = $details['post_code_use_type']?? \App\Consts\CommonConst::POST_CODE_DISABLED;
    $address_separate_type = $details['address_separate_type']?? \App\Consts\CommonConst::ADDRESS_SEPARATE;
@endphp

<div class="form-item-detail-contents">
    <div class="form-item-detail-title">
        【郵便番号を入力させる】
    </div>
    <div class="form-item-detail-content">
        <div class="form-item-detail-content">
            <x-input-radio
                name="post_code_use_type"
                :options="\App\Consts\CommonConst::POST_CODE_USE_LIST"
                :checked="$post_code_use_type"
            />
            <p class="support-msg">郵便番号を入力させる場合、郵便番号に沿った住所が自動で入力されます。</p>
        </div>
    </div>
</div>

<div class="form-item-detail-contents">
    <div class="form-item-detail-title">
        【住所の項目を分けるか】
    </div>
    <div class="form-item-detail-content">
        <x-input-radio
            name="address_separate_type"
            :options="\App\Consts\CommonConst::ADDRESS_SEPARATE_LIST"
            :checked="$address_separate_type"
        />
        <p class="support-msg">住所の項目を分ける場合、「都道府県」「市区町村」「町名丁名」「番地以降」で分かれます。</p>
    </div>
</div>
