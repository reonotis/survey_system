<x-user-app-layout>

    @push('scripts')
        @vite('resources/js/user/mail_template/list.js')
    @endpush

    <div class="custom-container py-4">
        <div class="contents-area mx-auto p-4" style="max-width: 1200px;">

            <div class="flex-end-center">
                <a href="{{ route('user_mail_template_upsert') }}" class="btn">新しくテンプレートを作成</a>
            </div>

            <div class="mb-8">
                <table class="list-tbl" id="template_list_tbl"
                       data-url="{{ route('user_mail_template_get_list') }}"
                       data-upsert-url="{{ route('user_mail_template_upsert') }}"
                       data-delete-url="{{ route('user_mail_template_delete', ['mail_template' => '__ID__']) }}"
                >
                </table>
            </div>

        </div>
    </div>
</x-user-app-layout>
