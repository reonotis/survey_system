<x-form-layout :form_setting="$form_setting">

    @push('scripts')
    @endpush

    <div class="custom-container py-8 mx-auto" style="width: 95%;max-width: 600px;">

        <div class="form-title">{{ $form_setting->title }}</div>

        {!! nl2br($message->complete_message)?? '申し込みが完了しました' !!}

    </div>
</x-form-layout>
