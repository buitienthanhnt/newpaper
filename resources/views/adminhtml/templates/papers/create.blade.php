@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    paper create
@endsection

@section('head_js_after')
    <script src="{{ asset('assets/all/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/all/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('/vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
    <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
@endsection

@section('after_css')
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .select2-selection--multiple {
            .select2-selection__choice {
                color: color(white);
                border: 0;
                border-radius: 3px;
                padding: 6px;
                font-size: larger !important;
                font-family: inherit;
                line-height: 1;
            }
        }

        .sliderImages {
            max-height: 480px !important;
        }
    </style>

    <meta charset='utf-8'>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="{{ asset('assets/frontend/css/dragula/dragula.css') }}" rel='stylesheet' type='text/css' />
    <link href="{{ asset('assets/frontend/css/dragula/example.css') }}" rel='stylesheet' type='text/css' />
@endsection

@section('body_main_conten')
    <script src="{{ asset('assets/frontend/js/dragula/dragula.js') }}"></script>
    <div class="col-12 grid-margin">
        <div >
            <label for='hy'>Move stuff between these two containers. Note how the stuff gets inserted near the mouse
                pointer? Great stuff.</label>
            <div class='wrapper'>
                <div id='left-defaults' class='container'>
                    <div>You can move these elements between these two containers</div>
                    <div>Moving them anywhere else isn't quite possible</div>
                    <div>Anything can be moved around. That includes images, <a
                            href='https://github.com/bevacqua/dragula'>links</a>, or any other nested elements.
                        <div class='image-thing'><img src="{{ asset('assets/frontend/img/dragula/resources/icon.svg') }}"
                                alt='dragula' /></div><sub>(You can still click on links, as usual!)</sub>
                    </div>
                </div>
                <div id='right-defaults' class='container'>
                    <div>There's also the possibility of moving elements around in the same container, changing their
                        position</div>
                    <div>This is the default use case. You only need to specify the containers you want to use</div>
                    <div>More interactive use cases lie ahead</div>
                    <div>Moving <code>&lt;input/&gt;</code> elements works just fine. You can still focus them, too.
                        <input placeholder='See?' />
                    </div>
                    <div>Make sure to check out the <a href='https://github.com/bevacqua/dragula#readme'>documentation
                            on
                            GitHub!</a></div>
                </div>
            </div>
            <pre>
                <code>
                    dragula([
                        document.getElementById('left-defaults'), 
                        document.getElementById('right-defaults')
                    ]);
                </code>
            </pre>
        </div>
        <script>
            dragula([document.getElementById('left-defaults'), document.getElementById('right-defaults')]);
        </script>
    </div>
@endsection

@section('before_bottom_js')
    <script type="text/javascript">
        var url_base = '{!! $filemanager_url_base !!}';
        $('#lfm').filemanager('file', {
            prefix: url_base
        });
        // slider_image
        $('#slider_image').filemanager('file', {
            prefix: url_base
        });

        $(".paper_tag").select2({
            tags: true,
            tokenSeparators: [','],
            placeholder: 'liên kết'
        });

        $("#time_line_type").select2({
            placeholder: 'Select an value',
            maximumSelectionLength: 1
        });

        $("#category_option").select2({
            placeholder: 'Select an option',
            tags: true,
            tokenSeparators: [',', ' '],
            placeholder: 'danh mục bài viết'
        });
    </script>
@endsection

@section('after_js')
    <script type="text/javascript">
        encodeURI('\uD800\uDFFF');
        tinymce.init({
            convert_urls: false,
            selector: "textarea#conten",
            plugins: ["image", "table", "code", "codesample", "addcomment", "showcomments", "media"],
            toolbar1: 'undo redo | fontfamily fontsize styles bold italic underline | alignleft aligncenter alignright alignjustify alignnone | indent outdent | wordcount | lineheight help image media',
            toolbar2: 'anchor | blockquote | backcolor forecolor | copy | cut | paste pastetext | hr | language | newdocument | print | remove removeformat | selectall | strikethrough | subscript superscript | visualaid | a11ycheck typopgraphy anchor restoredraft casechange charmap checklist ltr rtl editimage fliph flipv imageoptions rotateleft rotateright emoticons export footnotes footnotesupdate formatpainter fullscreen insertdatetime link openlink unlink bullist numlist mergetags mergetags_list nonbreaking pagebreak pageembed permanentpen preview quickimage quicklink cancel save searchreplace spellcheckdialog spellchecker | template typography | insertfile | visualblocks visualchars',
            file_picker_callback: function(callback, value, meta) {
                let x = window.innerWidth || document.documentElement.clientWidth || document
                    .getElementsByTagName('body')[0].clientWidth;
                let y = window.innerHeight || document.documentElement.clientHeight || document
                    .getElementsByTagName('body')[0].clientHeight;

                let type = 'image' === meta.filetype ? 'Images' : 'Files',
                    url = '{!! $filemanager_url !!}';

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
    </script>
@endsection
