アンケート作成アプリ 【{{ config('app.name') }}】へようこそ<br>
<br>
{{ $owner_name }}さんが管理する {{ $form_name }} のメンバーに招待されました。<br>
<br>
下記URLよりログインし、初期登録を行ってください。<br>
<a href="{{ route('user_dashboard') }}">{{ route('user_dashboard') }}</a><br>
ログイン用メールアドレス：{{ $to_mail_address }}<br>
初期パスワード：{{ $password }}<br>
<br>
------------------------------<br>
<br>
このメールはシステムからの自動送信です。<br>
このメールに心当たりがない場合は破棄していただきますようお願いいたします。<br>
<br>
