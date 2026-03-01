<x-user-app-layout>

    @push('scripts')
        @vite('resources/js/user/form/mail_template.js')
    @endpush

    <div class="custom-container py-4">

        <div class="mx-auto my-16" style="width: 800px;">

            <form method="POST" action="{{ route('user_mail_template_store') }}">
                @csrf

                <div>
                    <label>テンプレート名</label>
                    <x-input-text name="template_name" id="template_name" class="w-60"
                                  :value="old('template_name', $template->template_name ?? '')"
                                  :error="$errors->get('template_name')"
                                  placeholder=''
                    />
                </div>

                <div>
                    <label>件名</label>
                    <x-input-text name="subject" id="subject" class="w-60"
                                  :value="old('subject', $template->subject ?? '')"
                                  :error="$errors->get('subject')"
                                  placeholder=''
                    />
                </div>

                <div>
                    <label>本文</label>
                    <textarea id="sample" name="body" class="tinymce">{{ $template->body ?? '' }}</textarea>
                </div>

                <input type="hidden" name="id" value="{{ $template->id ?? '' }}">

                <button type="submit" class="btn mt-2">
                    テンプレート保存
                </button>

            </form>

        </div>

    </div>
</x-user-app-layout>
