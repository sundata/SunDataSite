# SunDataサービス株式会社 Website

SunDataサービス株式会社の新しい静的サイト案です。

## 内容

- 会社トップページ
- 事業内容
- App Store公開中アプリ紹介
- ステモン妙典校
- 民宿事業
- お知らせ・相談受付
- 採用情報
- 会社概要
- アクセス
- 問い合わせフォーム
- 日本語・中国語・英語の静的ページ

## ローカル確認

`index.html` をブラウザで開くと確認できます。

問い合わせフォームは `send-contact.php` に送信し、サーバーから `info@sundata.co.jp` へメール送信します。PHPの `mb_send_mail` が使えるサーバーで公開してください。アプリ情報はAppleの公開App Store情報をもとに掲載しています。

## 多言語ページ

- 日本語: `index.html`
- 中国語: `zh/index.html`
- 英語: `en/index.html`

言語切り替えはJavaScriptではなく、通常のページリンクで行います。
