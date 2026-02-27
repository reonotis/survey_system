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
import "tinymce/skins/content/default/content.css";

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
    images_upload_url: "/user/tinymce/upload",
    automatic_uploads: true,
    remove_script_host: false,

    document_base_url: "{{ config('app.url') }}/",

    images_upload_handler: function (blobInfo, progress) {

        return new Promise((resolve, reject) => {

            const formData = new FormData();
            formData.append('file', blobInfo.blob());

            fetch('/user/tinymce/upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
                .then(res => res.json())
                .then(json => resolve(json.location))
                .catch(() => reject('Upload failed'));

        });

    }
});
