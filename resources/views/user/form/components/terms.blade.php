<div>
    @php
        $details = json_decode($form_item->details ?? '{}', true);
        $value_list = json_decode($form_item->value_list ?? '{}', true);
        $terms_text = $value_list[0] ?? '';
        $consent_name = $details['consent_name'] ?? '';
    @endphp

    <div class="form-item-detail-contents">
        <div class="form-item-detail-title">
            【規約文言】
        </div>
        <div class="form-item-detail-content">
            <textarea name="terms_text" class="input-box w-full h-36">{{ $terms_text }}</textarea>
        </div>
    </div>
    <div class="form-item-detail-contents">
        <div class="form-item-detail-title">
            【承諾ラベル名】
        </div>
        <div class="form-item-detail-content">
            <p class="support-msg">チェックボックスを表示する場合は文言を設定してください。</p>
            <p class="support-msg">文言を設定しない場合、チェックボックスは表示されません。</p>
            <input type="text" name="consent_name" value="{{ $consent_name }}" class="input-box" placeholder="承諾する">
        </div>
    </div>
</div>
