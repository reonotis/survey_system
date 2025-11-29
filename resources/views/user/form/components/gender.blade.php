
@php
    $details = json_decode($form_item->details ?? '{}', true);
    $gender_list = $details['gender_list'] ?? [];
@endphp

<div class="form-item-detail-contents">
    <div class="form-item-detail-title">
        【選択可能項目】
    </div>
    <div class="form-item-detail-content">
        <x-input-checkbox-list
            name="gender_list"
            :options="\App\Consts\CommonConst::GENDER_LIST"
            :checked_list="$gender_list"
        />
    </div>
</div>
