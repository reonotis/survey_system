<x-form-layout :form_setting="$form_setting">

    @push('scripts')
    @endpush

    <div class="custom-container py-8 mx-auto" style="width: 95%;max-width: 600px;">
        <div class="form-title">{{ $form_setting->title }}</div>

        @if($error_type === 1)
            {!! nl2br(($form_setting->message->outside_period_message)?? '現在申込は出来ません') !!}
        @elseif($error_type === 2)
            現在公開されていません
        @elseif($error_type === 3)
            申込上限に達しています
        @endif
    </div>

</x-form-layout>
