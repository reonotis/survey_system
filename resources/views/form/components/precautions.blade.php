@props(['form_item'])

<div>
    <div class="support-msg">
        {!! nl2br($form_item->annotation_text) !!}
    </div>

    <div class="w-full" style="max-height: 200px;padding: .25rem;border: solid 1px #bbb;overflow-y: scroll;">{!! nl2br($form_item->long_text) !!}</div>

</div>
