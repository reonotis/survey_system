<div>
    @php
        $details = json_decode($form_item->details ?? '{}', true);
        $value_list = json_decode($form_item->value_list ?? '{}', true);
    @endphp

    <div class="support-msg">
        {!! nl2br($form_item->annotation_text) !!}
    </div>

    <div class="w-full" style="max-height: 200px;padding: .25rem;border: solid 1px #bbb;overflow-y: scroll;">{!! nl2br($value_list[0] ?? '') !!}</div>

    @if(isset($details['consent_name']))
        <div style="padding:.5rem;">
            <label>
                <input type="checkbox" name="consent_name" value="1">{{ $details['consent_name'] }}
            </label>
        </div>
    @endif
</div>
