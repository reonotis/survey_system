<x-user-app-layout>

    @push('scripts')
        @vite('resources/js/user/form/sample_mail.js')
    @endpush

    <div class="custom-container py-4">

        <div class="mx-auto my-16" style="width: 1000px;">

            <form method="POST" action="{{ route('user_sample_mail_tinymce_send') }}">
                @csrf
                <textarea id="sample" name="sample" class="tinymce"></textarea>

                <button type="submit" class="btn">送信</button>
            </form>

        </div>
    </div>
</x-user-app-layout>
