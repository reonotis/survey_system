
---
## 決済の為のStripeをローカル環境でできるようにする時の手順

1. ngrokを利用してローカル環境をリモートで利用できるようにし Webhookを使えるようにする  
  ##### GitBashで下記を実行する
  `ngrok http 8083`  
  ※GitBashを閉じたら利用できなくなるため開きっぱなしにしておく事  

2. 下記を実行しておかないと Webhook が機能しないかも

`stripe listen --forward-to https://volitionary-nonanimated-hilaria.ngrok-free.dev/stripe/webhook`

3. STRIPE_WEBHOOK_SECRETの値を変更する 
テスト用の環境ではWebhookが機能しない場合の手順を作成する事


-----------------------

-  接続できているか調査する場合

1. GitBash で下記を実行しログを表示しておく  
`stripe listen --forward-to https://volitionary-nonanimated-hilaria.ngrok-free.dev/stripe/webhook`


2. 別の GitBash で下記を実行する  
`stripe trigger customer.subscription.created`





