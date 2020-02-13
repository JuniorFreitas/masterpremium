export default {
    toolbar: ['bold italic underline'],
    menubar: false,
    statusbar: true,
    schema: 'html5',
    height: 200,
    resize: true,
    language: 'pt_BR',
    language_url: `${URL_SITE}/js/tinymce/langs/pt_BR.js`,
    branding: false,
    fontsize_formats: "12pt 14pt 18pt 24pt 36pt",
    setup: function (ed) {
        ed.on('init', function (e) {
            ed.execCommand("fontName", false, "Arial");
            ed.execCommand("fontSize", false, "10pt");
        });
    },
    key: 'n166eatov70tbex79f7fll7p0fmngp7n7dosnk8rhxn5cdng',
}
