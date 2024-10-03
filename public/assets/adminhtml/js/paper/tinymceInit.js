tinymce.init({
    convert_urls: false,
    selector: "textarea#conten",
    plugins: ["image", "table", "code", "codesample", "addcomment", "showcomments", "media"],
    toolbar1: 'undo redo | fontfamily fontsize styles bold italic underline | alignleft aligncenter alignright alignjustify alignnone | indent outdent | wordcount | lineheight help image media',
    toolbar2: 'anchor | blockquote | backcolor forecolor | copy | cut | paste pastetext | hr | language | newdocument | print | remove removeformat | selectall | strikethrough | subscript superscript | visualaid | a11ycheck typopgraphy anchor restoredraft casechange charmap checklist ltr rtl editimage fliph flipv imageoptions rotateleft rotateright emoticons export footnotes footnotesupdate formatpainter fullscreen insertdatetime link openlink unlink bullist numlist mergetags mergetags_list nonbreaking pagebreak pageembed permanentpen preview quickimage quicklink cancel save searchreplace spellcheckdialog spellchecker | template typography | insertfile | visualblocks visualchars',
    file_picker_callback: function (callback, value, meta) {
        let x = window.innerWidth || document.documentElement.clientWidth || document
            .getElementsByTagName('body')[0].clientWidth;
        let y = window.innerHeight || document.documentElement.clientHeight || document
            .getElementsByTagName('body')[0].clientHeight;

        let type = 'image' === meta.filetype ? 'Images' : 'Files',
            url = filemanager_url;

        tinymce.activeEditor.windowManager.openUrl({
            url: url,
            title: 'Filemanager',
            width: x * 0.8,
            height: y * 0.8,
            onMessage: (api, message) => {
                callback(message.content);
            }
        });
    }
});