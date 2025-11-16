<x-admin-app-layout>

    @push('scripts')
    @endpush

    <x-slot name="page_name">
        管理者ダッシュボード
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
                <h5 class="text-xl font-semibold text-gray-900 mb-3">オーナー管理</h5>
                <p class="text-gray-600 mb-4">オーナーの一覧と管理を行います。</p>
                <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors duration-150">
                    管理画面へ
                </a>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
                <h5 class="text-xl font-semibold text-gray-900 mb-3">アンケート管理</h5>
                <p class="text-gray-600 mb-4">アンケートの作成と管理を行います。</p>
                <a href="{{ route('admin_form_index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors duration-150">
                    管理画面へ
                </a>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-200">
                <h5 class="text-xl font-semibold text-gray-900 mb-3">統計情報</h5>
                <p class="text-gray-600 mb-4">システムの統計情報を確認します。</p>
                <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition-colors duration-150">
                    統計画面へ
                </a>
            </div>
        </div>
    </div>

</x-admin-app-layout>
