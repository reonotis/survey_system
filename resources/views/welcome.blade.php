<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
          content="誰でもブラウザだけでアンケート・申込フォームを作成できる無料ツール。自動返信メール、受付期間設定、当選機能、CSV出力でキャンペーンやイベントの集計・管理を効率化します。">

    <title>誰でも簡単に作れるアンケート・申込フォーム作成ツール</title>
    <meta property="og:title" content="誰でも簡単に作れるアンケート・申込フォーム作成ツール">
    <meta property="og:description"
          content="誰でもブラウザだけでアンケート・申込フォームを作成できる無料ツール。自動返信メール、受付期間設定、当選機能、CSV出力でキャンペーンやイベントの集計・管理を効率化します。">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="https://images.unsplash.com/photo-1556761175-4b46a572b786">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">

    @vite([
        'resources/scss/welcome.scss',
    ])

</head>

<body>

<header>
    <h1>誰でもかんたんに作れる！<br>アンケート作成アプリ</h1>
    <p>会員登録するだけで、自由にアンケートフォームを作成。<br>
        受付期間設定・自動返信メール・当選機能など “全部” 思いのまま。</p>

    <div class="hero-img">
        <img src="/image/form/form_image.png" alt="アンケート作成">
    </div>
</header>

<div class="container">

    <div class="section">
        <h2 data-title-name="FEATURE">機能一覧</h2>
        <div class="features">

            <div class="feature-box">
                <div class="feature-content">
                    <div class="feature-text">
                        <h3>自由な項目設定</h3>
                        <p>
                            会員登録するだけで、項目を好きなように追加・編集可能。
                            テキスト・ラジオ・チェックボックスなど、用途に合わせて自由に設計できます。
                        </p>
                    </div>

                    <div class="feature-image" style="width: 800px;">
                        <img src="/image/form/form_item_setting.png" alt="項目設定画面">
                    </div>
                </div>
            </div>

            <div class="feature-box">
                <div class="feature-content">
                    <div class="feature-text">
                        <h3>申込可能期間や上限値の設定</h3>
                        <p>
                            受付開始日・締切日、先着申込人数を自由に設定できるので、キャンペーンやイベントにも最適。
                            申込完了後メッセージも変更できます。
                        </p>
                    </div>

                    <div class="feature-image" style="width: 600px;">
                        <img src="/image/form/period.png" alt="申込可能期間設定画面">
                    </div>
                </div>
            </div>

            <div class="feature-box">
                <div class="feature-content">
                    <div class="feature-text">
                        <h3>自動返信・通知メールを自由に編集</h3>
                        <p>
                            申込者・管理者向けメールをすべて自由にカスタマイズ可能。
                        </p>
                    </div>

                    <div class="feature-image" style="width: 600px;">
                        <img src="/image/form/mail.png" alt="メール設定画面">
                    </div>
                </div>
            </div>

            <div class="feature-box">
                <div class="feature-content">
                    <div class="feature-text">
                        <h3>回答結果をCSVダウンロード</h3>
                        <p>
                            申込フォームに対する回答一覧ををいつでも確認でき、CSVでダウンロードできます。
                        </p>
                    </div>

                    <div class="feature-image" style="width: 600px;">
                        <img src="/image/form/csv.png" alt="CSVダウンロード画面">
                    </div>
                </div>
            </div>

            <div class="feature-box">
                <div class="feature-content">
                    <div class="feature-text">
                        <h3>自由なフォームデザイン設定</h3>
                        <p>背景色など、フォーム全体のデザインを好きなようにカスタマイズ。個別CSSを記載することも可能。
                            ブランドカラーに合わせたフォームも即座に作成できます。</p>
                    </div>

                    <div class="feature-image" style="width: 400px;">
                        <img src="/image/form/mobile.png" alt="CSVダウンロード画面">
                    </div>
                </div>
            </div>

            <div class="feature-box">
                <div class="feature-content">
                    <div class="feature-text">
                        <h3>柔軟なサポート</h3>
                        <p>操作について不明な場合は専門のスタッフがサポートいたします。</p>
                    </div>

                    <div class="feature-image" style="width: 600px;">
                        <img src="/image/form/support.png" alt="CSVダウンロード画面">
                    </div>
                </div>
            </div>

{{--            <div class="feature-box">--}}
{{--                <div class="feature-content">--}}
{{--                    <div class="feature-text">--}}
{{--                        <h3>当選機能付き</h3>--}}
{{--                        <p>アンケートやキャンペーンにぴったりの “当選機能” を標準搭載。--}}
{{--                            当選数や条件を設定して、自動抽選も楽々。</p>--}}
{{--                    </div>--}}

{{--                    <div class="feature-image" style="width: 800px;">--}}
{{--                        <img src="/image/form/form_image.png" alt="CSVダウンロード画面">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

        </div>
    </div>

{{--    <div class="section">--}}
{{--        <h2 data-title-name="PRICE">料金プラン</h2>--}}
{{--        <div class="">--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="cta">
        <a href="{{ route('user_register') }}">今すぐ無料で始める</a>
    </div>

    <div class="section">
        <h2 data-title-name="Q & A">よくある質問</h2>
        <div class="faq-list">
            <div class="faq-item">
                <h3 class="faq-question">無料プランでもすべての機能を使えますか？</h3>
                <div class="faq-answer">無料プランでも基本的な機能は利用可能ですが、一部機能を制限しております。詳しくは料金プランをご確認ください。</div>
            </div>
            <div class="faq-item">
                <h3 class="faq-question">作成できるフォーム数に制限はありますか？</h3>
                <div class="faq-answer">作成できるフォーム数に制限はありません。ただし、1つのフォームで利用できる項目数に制限があります
                    有料プランでは無制限にフォームを作成できます。</div>
            </div>
            <div class="faq-item">
                <h3 class="faq-question">スマートフォンからも利用できますか？</h3>
                <div class="faq-answer">申込フォームはPC・タブレット・スマートフォンすべてに対応しています。
                    ただし、管理画面はPCからしか利用出来ません。スマートフォンなどの画面幅が狭い端末では正しく表示されない事があります</div>
            </div>
            <div class="faq-item">
                <h3 class="faq-question">CSVダウンロードはいつでも可能ですか？</h3>
                <div class="faq-answer">管理画面からいつでもCSV形式でデータをダウンロードできます。
                    フィルタや期間指定も可能です</div>
            </div>
            <div class="faq-item">
                <h3 class="faq-question">途中で有料プランに変更できますか？</h3>
                <div class="faq-answer">はい、管理画面からいつでもアップグレード可能です。
                    データはそのまま引き継がれます。</div>
            </div>
        </div>
    </div>

    <div class="cta">
        <a href="{{ route('user_register') }}">今すぐ無料で始める</a>
    </div>

{{--    <div class="section">--}}
{{--        <h2 data-title-name="CONTACT">お問い合わせ</h2>--}}
{{--        <div class=""></div>--}}
{{--    </div>--}}

</div>

<footer>
    © 2026 アンケート作成アプリ フォームメーカー All Rights Reserved.
</footer>

</body>


<script>
    document.querySelectorAll('.faq-question').forEach(btn => {
        btn.addEventListener('click', () => {
            btn.classList.toggle('active');
            const answer = btn.nextElementSibling;
            answer.classList.toggle('open');
        });
    });
</script>
</html>

