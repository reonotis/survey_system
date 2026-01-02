<x-user-app-layout>

    @push('scripts')
        @vite('resources/js/user/form/mail_setting.js')
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        {{ $form_setting->title }} - 応募分析
    </x-slot>

    {{-- ぱんくず --}}
    <x-slot name="breadcrumbs">
        <ol class="custom-container">
            <li><a href="{{ route('user_dashboard') }}" class="anchor-link">ダッシュボード</a></li>
            <li><a href="{{ route('user_form_index') }}" class="anchor-link">応募フォーム一覧</a></li>
            <li><a href="{{ route('user_form_application_list', ['form_setting' => $form_setting->id]) }}"
                   class="anchor-link">{{ $form_setting->title }} - 応募者一覧</a></li>
            <li><a href="" class="anchor-link">応募分析</a></li>
        </ol>
    </x-slot>


    <div class="custom-container py-4">
        @include('layouts.user.form.navigation', ['number' => \App\Consts\UserConst::NAV_MANU_ANALYTICS])

        <div class="dashboard common-dashboard mb-4">
            <div class="widget">
                <div class="widget-title">申込み総件数</div>
                <div class="widget-content">
                    <span class="counter">{{ $total_count }}<span class="unit">件</span></span>
                </div>
            </div>
            <div class="widget">
                <div class="widget-title gap-8">
                    期間内応募
                    <x-input-radio
                        name="name_type_kana"
                        :options="\App\Consts\CommonConst::ANALYTICS_TYPE_LIST"
                        :checked="1"
                    />
                </div>
                <div class="widget-content">
                    <div class="my-8">この機能は現在作成中です</div>
                </div>
            </div>
        </div>


        <div class="">集計</div>
        <div class="dashboard original-dashboard">
            <div class="widget">コンテンツが選択されていません</div>
            <div class="widget">コンテンツが選択されていません</div>
            <div class="widget">コンテンツが選択されていません</div>
            <div class="widget">コンテンツが選択されていません</div>
            <div class="widget">コンテンツが選択されていません</div>
            <div class="widget">コンテンツが選択されていません</div>
            <div class="widget">コンテンツが選択されていません</div>
            <div class="widget">コンテンツが選択されていません</div>
        </div>

    </div>

</x-user-app-layout>

<style>
    .dashboard {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        grid-auto-rows: 120px;
        gap: 16px;

        /* 共通ウィジェット */
        .widget {
            background: #ffefed;
            border: solid 1px #1f4f78;
            border-radius: 6px;
            display: flex;
            flex-flow: column;

            & .widget-title {
                padding: .25rem 1rem;
                background: #ffd1d1;
                border-radius: 6px 6px 0 0;
                border-bottom: solid 1px #333;
                font-weight: bold;
                display: flex;
                align-items: center;
            }

            & .widget-content {
                padding: .5rem 1rem;
                flex: 1;
                display: flex;
                justify-content: center;
                align-items: center;

                & .counter {
                    font-size: 3rem;
                }

                & .unit {
                    font-size: 1rem;
                }
            }
        }
    }

    .common-dashboard {
        & > .widget:nth-child(1) {
            grid-column: 1 / span 1; /* 1列目から1列分 */
            grid-row: 1 / span 1; /* 1行目から1行分 */
        }

        & > .widget:nth-child(2) {
            grid-column: 2 / span 5; /* 2列目から5列分 */
            grid-row: 1 / span 3; /* 1行目から3行分 */
        }
    }

    .original-dashboard {
        & > .widget:nth-child(1) {
            grid-column: 1 / span 2;
            grid-row: 1 / span 1;
        }

        & > .widget:nth-child(2) {
            grid-column: 3 / span 4;
            grid-row: 1 / span 1;
        }
    }

</style>



