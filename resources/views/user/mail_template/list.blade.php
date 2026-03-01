<x-user-app-layout>

    @push('scripts')
        @vite('resources/js/user/mail_template/list.js')
    @endpush

    <div class="custom-container py-4">
        <div class="mx-auto my-16" style="width: 1000px;">

            <div class="mb-8">
                <table class="list-tbl" id="template_list_tbl"
                       data-url="{{ route('user_mail_template_get_list') }}"
                       data-upsert-url="{{ route('user_mail_template_upsert') }}"
                >
                    <th>saaa</th>
                </table>
            </div>

        </div>
    </div>
</x-user-app-layout>
