<x-user-app-layout>

    @push('scripts')
        @vite('resources/js/user/form/mail_template.js')
    @endpush

    <div class="custom-container py-4">

        <div class="contents-area mx-auto p-4" style="width: 800px;">

            <form method="POST" action="{{ route('user_mail_template_store') }}">
                @csrf

                <div class="item-row">
                    <div class="item-title">テンプレート名</div>
                    <div class="item-contents flex-start-center">
                        <x-input-text name="template_name" id="template_name" class="w-full"
                                      :value="old('template_name', $template->template_name ?? '')"
                                      :error="$errors->get('template_name')"
                                      placeholder='プレゼントキャンペーン自動返信メール'
                        />
                    </div>
                </div>

                <div class="item-row">
                    <div class="item-title">件名</div>
                    <div class="item-contents flex-start-center">
                        <x-input-text name="subject" id="subject" class="w-full"
                                      :value="old('subject', $template->subject ?? '')"
                                      :error="$errors->get('subject')"
                                      placeholder='ご応募ありがとうございました'
                        />
                    </div>
                </div>

                <div class="item-row">
                    <div class="item-title">本文</div>
                    <div class="item-contents flex-start-center">
                        <textarea id="sample" name="body" class="tinymce w-full">{{ $template->body ?? '' }}</textarea>
                    </div>
                </div>

                <input type="hidden" name="id" value="{{ $template->id ?? '' }}">

                <div class="flex-center-center mt-2">
                    <button type="submit" class="btn">
                        テンプレート保存
                    </button>
                </div>

            </form>

        </div>

    </div>
</x-user-app-layout>
