
@php
    $details = json_decode($form_item->details ?? '{}', true);
    $name_type = $details['name_type']?? 1;
@endphp

<div class="form-item-detail-contents">
    <div class="form-item-detail-title">
        【セイとメイを別々にするか】
    </div>
    <div class="form-item-detail-content">
        <x-input-radio
            name="name_type"
            :options="\App\Consts\CommonConst::KANA_SEPARATE_LIST"
            :checked="$name_type"
        />
    </div>
</div>
