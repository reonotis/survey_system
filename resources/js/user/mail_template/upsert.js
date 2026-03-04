import tinymce, { initTinymce } from "../../common/tinymce.js";

initTinymce({
    /* 対象 */
    selector: ".tinymce", // このclassを持つtextareaをエディタ化

    /* 使用プラグイン */
    plugins: "link lists table image",

    /* ツールバー構成 */
    toolbar: "undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image table",

    /* URL設定 */
    relative_urls: false,
    remove_script_host: false,
    document_base_url: window.location.origin + "/",
});

