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
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.4.1/tinymce.min.js"></script> --}}
@endsection

@section('after_css')
    <style type="text/css">
        /* ul,
                    ol,
                    dl {
                        padding-left: 1rem;
                        font-size: $default-font-size;

                        li {
                            line-height: 1.8 !important;
                        }
                    } */

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
    </style>
@endsection

@section('body_main_conten')
    <div class="col-12 grid-margin">
        <div class="">
            <div class="card-body">
                <h4 class="card-title">add new source</h4>
                <form class="form-sample" method="POST" enctype="multipart/form-data" action={{ route('admin_paper_save') }}>
                    @csrf
                    
                    @if ($message = session('success'))
                        <?php alert()->success('server message', $message); ?>
                    @elseif ($error = session('error'))
                        <?php alert()->fail('server mesage', $error); ?>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Title:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="page_title" />
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="active" class="col-sm-1">Active:</label>
                                <div class="col-sm-1">
                                    <input id="active" class="form-check-input" type="checkbox" name="active" checked>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="url-alias" class="col-sm-2">url alias:</label>
                                <div class="col-sm-8">
                                    <input id="url-alias" class="form-control" type="text" name="alias">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 row">
                            <label for="show" class="col-sm-1">show:</label>
                            <div class="col-sm-1">
                                <input id="show" class="form-check-input" type="checkbox" name="show" checked>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="short_conten" class="col-sm-2">short conten:</label>
                            <div class="col-sm-10">
                                {{-- <input id="short_conten" class="form-control" type="text" name="short_conten" checked> --}}
                                <textarea id="short_conten" name="short_conten" class="form-control" rows="4"
                                    style="padding: 10px; height: 100%;"></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">image:</label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control pb-10" id="save_image" name="save_image" />
                                    <img src="#" style="width: 100%; height: 240px; resize: cover; display: none"
                                        class="form-control" alt="your image" id="priview_image" />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="row">
                        
                    </div> --}}

                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="conten" class="col-sm-2">page conten:</label>
                                <textarea id="conten" name="conten" class="form-control">{!! old('content', '') !!}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="paper-tag">tag for links</label>
                                <select class="paper_tag form-control" name="paper_tag[]" multiple="multiple">

                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="auto_hide">auto hide: </label>
                                <input id="auto_hide" class="form-check-input" type="checkbox" name="auto_hide">
                            </div>
                        </div>
                    </div>
            </div>

            {{-- <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">file name:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="file_name" />
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="row">
                <div class="col-md-6 justify-content-center row">
                    {{-- <div class="col-md-4 row"> --}}
                    <button type="submit" class="btn btn-info btn-lg btn-block">save file</button>
                    {{-- </div> --}}
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>

    <script>
        $(".paper_tag").select2({
            tags: true,
            tokenSeparators: [',', ' ']
        });

        $(document).ready(function() {
            save_image.onchange = evt => {
                const [file] = save_image.files
                if (file) {
                    $(priview_image).show();
                    priview_image.src = URL.createObjectURL(file)
                } else {
                    $(priview_image).hide();
                }
            }
        });
    </script>
@endsection

@section('after_js')
    <script>
        // CKEDITOR.replace('myeditor'); // dùng cho CKEDITOR.

        // dùng cho tiny mce.
        tinymce.init({
            selector: "textarea#conten",
            plugins: ["image", "table", "code"],
            toolbar1: 'undo redo | fontfamily fontsize styles bold italic underline | alignleft aligncenter alignright alignjustify alignnone | indent outdent | wordcount | lineheight help image',
            toolbar2: 'anchor | blockquote | backcolor forecolor | copy | cut | paste pastetext | hr | language | newdocument | print | remove removeformat | selectall | strikethrough | subscript superscript | visualaid | a11ycheck typopgraphy anchor restoredraft casechange charmap checklist code codesample addcomment showcomments ltr rtl editimage fliph flipv imageoptions rotateleft rotateright emoticons export footnotes footnotesupdate formatpainter fullscreen insertdatetime link openlink unlink bullist numlist media mergetags mergetags_list nonbreaking pagebreak pageembed permanentpen preview quickimage quicklink cancel save searchreplace spellcheckdialog spellchecker | template typography | insertfile | visualblocks visualchars',
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
