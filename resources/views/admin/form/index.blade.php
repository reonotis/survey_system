<x-admin-app-layout>

    @push('scripts')
        @vite('resources/js/admin/form_list.js')
    @endpush

    <x-slot name="page_name">
        アンケート管理
    </x-slot>


    <div class=" mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex-between-center mb-4">
            <div class="flex-start-center gap-4">
                <input type="text" name="title" id="title" class="input-box w-60" placeholder="タイトル"/>
                <input type="text" name="status" id="status" class="input-box w-60" placeholder="状態"/>

                <button type="button" class="btn">検索</button>
            </div>
            <a href="{{ route('admin_surveys_create') }}" class="btn">新規作成はこちら</a>
        </div>

        <div class="mb-8">
            <table class="list-tbl" id="survey_list_tbl" data-url="{{ route('admin_form_data') }}" data-edit-url="{{ route('admin_surveys_edit', ['survey' => '__ID__']) }}" ></table>
        </div>
    </div>

</x-admin-app-layout>
