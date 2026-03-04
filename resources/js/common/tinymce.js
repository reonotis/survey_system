import tinymce from "tinymce";

/* ===============================
   コア読み込み
================================ */
import "tinymce/icons/default";      // アイコン
import "tinymce/themes/silver";      // テーマ
import "tinymce/models/dom";        // DOMモデル

/* ===============================
   言語設定
================================ */
import "tinymce-i18n/langs6/ja";    // 日本語化

/* ===============================
   使用プラグイン
================================ */
import "tinymce/plugins/link";      // リンク機能
import "tinymce/plugins/lists";     // 箇条書き
import "tinymce/plugins/table";     // テーブル
import "tinymce/plugins/image";     // 画像挿入

/* ===============================
   スキンCSS
================================ */
import "tinymce/skins/ui/oxide/skin.css";

/**
 * TinyMCE 用のデフォルト設定
 * 画面ごとの差分は options で上書きする前提
 */
const baseOptions = {
    /* 共通 UI / ライセンス */
    language: "ja",
    license_key: "gpl",
    promotion: false,
    branding: false,

    /* デフォルトサイズ */
    width: "100%",
    height: 400,

    relative_urls: false, // 相対URLに変換しない
    remove_script_host: false, // ドメインを削除しない
    convert_urls: true, // URL変換機能は有効

    /* 共通スキン設定（アプリ側で CSS を当てる想定） */
    skin: false,
    content_css: false,

    /* 使用プラグイン */
    plugins: "link lists table image",

    /* ツールバー構成 */
    toolbar: "undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image table",

    /* 画像アップロード  */
    images_upload_credentials: true,
    images_upload_handler: imageUploadHandler,
};

/**
 * TinyMCE 用の画像アップロードハンドラ（Laravel 用）
 */
export function imageUploadHandler(blobInfo) {
    return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append('file', blobInfo.blob());
        formData.append(
            '_token',
            document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content')
        );

        fetch('/user/tinymce/image/upload', {
            method: 'POST',
            body: formData,
        })
            .then(res => res.json())
            .then(json => {
                if (!json.location) {
                    reject('Upload failed');
                    return;
                }
                resolve(json.location); // 画像URLをTinyMCEへ返す
            })
            .catch(() => reject('Upload error'));
    });
}

/**
 * TinyMCE 初期化
 * @param {Object} options tinymce.init に渡すオプション
 */
export function initTinymce(options = {}) {
    return tinymce.init({
        ...baseOptions,
        ...options,
    });
}

export default tinymce;
