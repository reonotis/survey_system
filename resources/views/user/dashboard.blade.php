<x-user-app-layout>

    @push('scripts')
    @endpush

    <x-slot name="page_name">
        管理者ダッシュボード
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
                <h5 class="text-xl font-semibold text-gray-900 mb-3">応募フォーム管理</h5>
                <p class="text-gray-600 mb-4">応募フォームの一覧と管理を行います。</p>
                <a href="{{ route('user_form_index') }}" class="btn">
                    管理画面へ
                </a>
            </div>

            @if(!$is_client_domain)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
                    <h5 class="text-xl font-semibold text-gray-900 mb-3">応募フォーム作成</h5>
                    <p class="text-gray-600 mb-4">新しく応募フォームの作成を行います。</p>
                    <a href="{{ route('user_form_create') }}" class="btn">
                        管理画面へ
                    </a>
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
                <h5 class="text-xl font-semibold text-gray-900 mb-3">ユーザー管理</h5>
                <p class="text-gray-600 mb-4">アプリ管理者の管理します。</p>
                <a href="" class="btn">
                    管理画面へ
                </a>
            </div>
        </div>
    </div>
</x-user-app-layout>
