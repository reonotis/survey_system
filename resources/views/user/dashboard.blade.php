<x-user-app-layout>

    @push('scripts')
        @vite('resources/js/user/form/form_list.js')
    @endpush

    @php
        $options = [
            [
                'label'=> \App\Models\FormSetting::PUBLICATION_STATUS_LIST[\App\Models\FormSetting::PUBLICATION_STATUS_DISABLE],
                'value'=> \App\Models\FormSetting::PUBLICATION_STATUS_DISABLE,
            ],[
                'label'=> \App\Models\FormSetting::PUBLICATION_STATUS_LIST[\App\Models\FormSetting::PUBLICATION_STATUS_ENABLE],
                'value'=> \App\Models\FormSetting::PUBLICATION_STATUS_ENABLE,
            ]
        ];
    @endphp

    <div class="custom-container py-4">

        <div class="flex-between-center mb-4">
            <div class="flex-start-center gap-4">
                <input type="text" name="form_name" id="form_name" class="input-box w-60" placeholder="管理名"/>

                <x-input-drop-down-checkbox name="status[]" id="status" placeholder="状態"
                    :options="$options" :values="old('status', [])"/>
                <button type="button" id="form_list_search_btn" class="btn">検索</button>
            </div>

            <div class="flex-start-center gap-4">
                <a href="{{ route('user_form_create') }}" class="btn">新しいフォームを作成</a>
            </div>

        </div>

        <div class="mb-8">
            <table class="list-tbl" id="form_list_tbl"
                   data-url="{{ route('user_get_form_list') }}"
                   data-form-setting-url="{{ route('user_form_basic_setting', ['form_setting' => '__ID__']) }}"
                   data-show-url="{{ route('user_form_application_list', ['form_setting' => '__ID__']) }}"
                   data-delete-url="{{ route('user_form_delete', ['form_setting' => '__ID__']) }}">
            </table>
        </div>

    </div>
</x-user-app-layout>
