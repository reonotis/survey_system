import tinymce from "tinymce";

import "tinymce/icons/default";
import "tinymce/themes/silver";
import "tinymce/models/dom";

import "tinymce-i18n/langs6/ja";

import "tinymce/plugins/link";
import "tinymce/plugins/lists";
import "tinymce/plugins/table";
import "tinymce/plugins/image";

import "tinymce/skins/ui/oxide/skin.css";

tinymce.init({
    selector: ".tinymce",
    height: 400,
    license_key: "gpl",
    skin: false,
    content_css: false,

    language: "ja",

    promotion: false,
    branding: false,

    plugins: "link lists table image",
    toolbar: "undo redo | bold italic | alignleft aligncenter alignright | bullist numlist | link image table",

    relative_urls: false,
    remove_script_host: false,
    document_base_url: window.location.origin + "/",
});

document.getElementById('template-select').addEventListener('change', function () {

    const option = this.options[this.selectedIndex];

    const body = option.value;
    const subject = option.dataset.subject;

    if (body) {
        tinymce.get('sample').setContent(body);
    }

    const subjectInput = document.querySelector('input[name="subject"]');
    if (subjectInput) {
        subjectInput.value = subject;
    }

});
