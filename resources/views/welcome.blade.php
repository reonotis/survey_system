<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>誰でも作れるアンケート作成アプリ</title>
    <style>
        body {
            margin: 0;
            font-family: "Hiragino Sans", "Helvetica Neue", Arial, sans-serif;
            background: #f7fbff;
            color: #333;
            line-height: 1.6;
        }

        header {
            background: linear-gradient(135deg, #5ec8ff, #3ea9ff);
            color: #fff;
            text-align: center;
            padding: 80px 20px;
        }

        header h1 {
            font-size: 42px;
            margin: 0;
            letter-spacing: 1px;
            font-weight: 700;
        }

        header p {
            font-size: 20px;
            margin-top: 20px;
            opacity: 0.95;
        }

        .container {
            width: 100%;
            max-width: 980px;
            margin: auto;
            padding: 60px 20px;
        }

        .section {
            margin-bottom: 80px;
        }

        .section h2 {
            font-size: 32px;
            color: #2c8cd9;
            margin-bottom: 20px;
            text-align: center;
        }

        .features {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .feature-box {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            border-left: 6px solid #2c8cd9;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
        }

        .feature-box h3 {
            margin-top: 0;
            font-size: 26px;
            color: #2c8cd9;
        }

        .cta {
            text-align: center;
            margin-top: 60px;
        }

        .cta a {
            display: inline-block;
            background: #2c98ff;
            padding: 18px 40px;
            color: #fff;
            font-size: 22px;
            font-weight: bold;
            border-radius: 50px;
            text-decoration: none;
            box-shadow: 0 6px 18px rgba(46, 146, 255, 0.3);
            transition: 0.3s;
        }

        .cta a:hover {
            transform: translateY(-3px);
            background: #1b89ec;
        }

        footer {
            background: #dff2ff;
            padding: 20px;
            text-align: center;
            color: #4c4c4c;
            margin-top: 80px;
        }

        /* 画像エリア */
        .hero-img {
            width: 100%;
            max-width: 900px;
            margin: 40px auto 0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .hero-img img {
            width: 100%;
            display: block;
        }
    </style>
</head>

<body>

<header>
    <h1>誰でもかんたんに作れる！<br>アンケート作成アプリ</h1>
    <p>会員登録するだけで、自由にアンケートフォームを作成。<br>
        受付期間設定・自動返信メール・当選機能など “全部” 思いのまま。</p>

    <div class="hero-img">
        <img src="https://images.unsplash.com/photo-1556761175-4b46a572b786" alt="アンケート作成">
    </div>
</header>

<div class="container">

    <div class="section">
        <h2>機能一覧</h2>

        <div class="features">

            <div class="feature-box">
                <h3>自由な項目設定</h3>
                <p>会員登録するだけで、項目を好きなように追加・編集可能。
                    テキスト・ラジオ・チェックボックスなど、用途に合わせて自由に設計できます。</p>
            </div>

            <div class="feature-box">
                <h3>申込期間の設定</h3>
                <p>受付開始日・締切日を自由に設定できるので、キャンペーンやイベントにも最適。
                    申込完了後に表示されるメッセージも管理画面から変更できます。</p>
            </div>

            <div class="feature-box">
                <h3>自動返信・通知メールを自由に編集</h3>
                <p>申込者への自動返信メール、管理者への通知メールなど、すべて文章を自由にカスタマイズ可能。</p>
            </div>

            <div class="feature-box">
                <h3>申込み結果のCSVダウンロード</h3>
                <p>管理画面では申し込み一覧をいつでも閲覧でき、ワンクリックで CSV としてダウンロードできます。</p>
            </div>

            <div class="feature-box">
                <h3>豊富なフォームデザイン設定</h3>
                <p>背景色・文字色・装飾など、フォーム全体のデザインを好きなようにカスタマイズ。
                    ブランドカラーに合わせたフォームも即座に作成できます。</p>
            </div>

            <div class="feature-box">
                <h3>当選機能付き</h3>
                <p>アンケートやキャンペーンにぴったりの “当選機能” を標準搭載。
                    当選数や条件を設定して、自動抽選も楽々。</p>
            </div>

        </div>
    </div>

    <div class="cta">
        <a href="{{ route('user_register') }}">今すぐ無料で始める</a>
    </div>
</div>

<footer>
    © 2025 アンケート作成アプリ All Rights Reserved.
</footer>

</body>
</html>
