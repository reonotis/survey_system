<x-admin-app-layout>

    @push('scripts')
    @endpush

    <x-slot name="page_name">
        アンケート管理
    </x-slot>


    <div class=" mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="contents-box" style="width: 800px;">
            <div class="contents-box-header">アンケート作成</div>

            <div class="contents-box-body">
                <form method="POST" action="{{ route('admin_surveys_register') }}" id="survey_create_form" >
                    @csrf

                    <div class="item-row">
                        <div class="item-title">フォーム管理名</div>
                        <div class="item-contents">
                            <input type="text" name="form_name" id="form_name" class="input-box w-full" />
                            <x-input-error :messages="$errors->get('form_name')" class="mt-2" />
                        </div>
                    </div>
                    <div class="item-row">
                        <div class="item-title">タイトル</div>
                        <div class="item-contents">
                            <input type="text" name="title" id="title" class="input-box w-full"/>
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                    </div>
                    <div class="item-row">
                        <div class="item-title">ルーティング名</div>
                        <div class="item-contents">
                            <input type="text" name="route_name" id="route_name" class="input-box w-full"/>
                            <x-input-error :messages="$errors->get('route_name')" class="mt-2" />
                        </div>
                    </div>
                    <div class="item-row">
                        <div class="item-title">申込可能期間</div>
                        <div class="item-contents">
                            <input type="datetime-local" name="start_date" id="start_date" class="input-box w-60"/>
                            ~
                            <input type="datetime-local" name="end_date" id="end_date" class="input-box w-60"/>
                        </div>
                    </div>
                    <div class="item-row">
                        <div class="item-title">状態</div>
                        <div class="item-contents flex-start-center">
                        </div>
                    </div>

                    <div class="item-row">
                        <div class="item-contents flex-center-center">
                            <input type="submit" class="btn" value="登録" />
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

</x-admin-app-layout>

<style>
</style>
