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
@endsection

@section('body_main_conten')
    <div class="col-12 grid-margin">
        <div>
            <div class="card-body">
                <h4 class="card-title">add new source</h4>
                <form class="form-sample" method="POST" enctype="multipart/form-data" action={{ route('admin_paper_save') }}>
                    @csrf

                    <input class="form-control" type="text" name="request_url"
                        value="{{ isset($request_url) ? $request_url : '' }}" hidden>

                    @if ($message = session('success'))
                        <?php alert()->success('server message', $message); ?>
                    @elseif ($error = session('error'))
                        <?php alert()->warning('server mesage', $error); ?>
                    @endif

                    @render(App\ViewBlock\PaperCreateForm::class)

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info btn-lg"
                                    style="width: -webkit-fill-available;">save new paper</button>
                            </div>
                        </div>
                    </div>
            </div>

            </form>
        </div>
    </div>
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
            tokenSeparators: [',']
        });

        $("#category_option").select2({
            placeholder: 'Select an option',
            tags: true,
            tokenSeparators: [',', ' ']
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
