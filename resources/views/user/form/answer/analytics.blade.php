<x-user-app-layout>

    @push('scripts')
        @viteReactRefresh
        @vite([
            'resources/js/user/form/analytics/analytics.jsx'
        ])
    @endpush

    {{-- 画面名 --}}
    <x-slot name="page_name">
        <div class="flex-between-center gap-2">
            <div class="page-name">{{ $form_setting->title }} - 応募分析 </div>
            @include('layouts.user.form.form-navigation', ['number' => \App\Consts\UserConst::FORM_NAV_ITEM_APPLICATION])
        </div>
    </x-slot>

    <div class="custom-container py-4">
        @include('layouts.user.form.navigation', ['number' => \App\Consts\UserConst::NAV_MANU_ANALYTICS])

        {{-- Reactコンポーネント用のコンテナ --}}
        <div class="">集計</div>
        <div
            id="react-analytics-container"
            data-analytics='@json($analytics_list)'
            data-form-items='@json($form_items)'
            data-widget-type-list='@json($widget_type_list)'
            data-url-add-widget-row='@json(route('user_form_analytics_add_widget_row', ['form_setting' => $form_setting->id]))'
            data-url-widget-add='@json(route('user_form_analytics_add_widget', ['form_setting' => $form_setting->id]))'
        ></div>

    </div>

</x-user-app-layout>

<script>
    window.graphTypes = @json(\App\Consts\CommonConst::GRAPH_TYPE_LIST);
    window.urlWidgetRowDelete = @json(route('user_form_analytics_widget_row_delete', ['form_setting' => $form_setting->id]));
</script>

<style>
    .dashboard,
    .dashboard-row{
        display: grid;
        grid-template-columns: repeat(61, 1fr);
        gap: 5px;
        grid-auto-rows: minmax(80px, auto);
        grid-auto-flow: row;

        /* 共通ウィジェット */
        .widget {
            background: #ffefed;
            border: solid 1px #1f4f78;
            border-radius: 6px;
            display: flex;
            flex-flow: column;
            min-height: 80px;
            grid-auto-rows: minmax(80px, auto);

            &.add-widget {
                border: dashed 3px #999;
            }

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



</style>



