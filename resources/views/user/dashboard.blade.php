<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザーダッシュボード - アンケートシステム</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">アンケートシステム - ユーザー</a>
            <div class="navbar-nav ms-auto">
                <form method="POST" action="{{ route('user.logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">ログアウト</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1>ユーザーダッシュボード</h1>
                <p>ユーザーとしてログインしています。</p>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">アンケート一覧</h5>
                                <p class="card-text">参加可能なアンケートを確認します。</p>
                                <a href="#" class="btn btn-primary">アンケート一覧へ</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">回答履歴</h5>
                                <p class="card-text">過去に回答したアンケートを確認します。</p>
                                <a href="#" class="btn btn-primary">履歴画面へ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
