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
@endsection

@section('after_css')
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
    </style>
@endsection

@section('body_main_conten')
    <div class="col-12 grid-margin">
        <div>
            <div class="card-body">
                <h4 class="card-title">add new source</h4>
                <form class="form-sample" method="POST" enctype="multipart/form-data" action={{ route('admin_paper_save') }}>
                    @csrf

                    <input class="form-control" type="text" name="request_url" value="{{ isset($request_url) ? $request_url : "" }}" hidden>

                    @if ($message = session('success'))
                        <?php alert()->success('server message', $message); ?>
                    @elseif ($error = session('error'))
                        <?php alert()->warning('server mesage', $error); ?>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Title:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="page_title" required value="@isset($title){{ $title }}@endisset" required placeholder="page title"/>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="active" class="col-sm-1">Active:</label>
                                <div class="col-sm-1">
                                    <input id="active" class="form-check-input" type="checkbox" name="active"
                                        value="@if (isset($active)){{ $active ? 'checked' : '' }} @else checked @endif"
                                    >
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="url-alias" class="col-sm-2">url alias:</label>
                                <div class="col-sm-8">
                                    <input id="url-alias" class="form-control" type="text" name="alias" placeholder="use page title if this value is null" value="@isset($url_alias){{ $url_alias }}@endisset">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 row">
                            <label for="show" class="col-sm-1">show:</label>
                            <div class="col-sm-1">
                                <input id="show" class="form-check-input" type="checkbox" name="show" value="@if (isset($show)) {{ $show ? 'checked' : '' }} @else checked @endif">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="short_conten" class="col-sm-2">short conten:</label>
                            <div class="col-sm-10">
                                <textarea id="short_conten" name="short_conten" class="form-control" rows="4" style="padding: 10px; height: 100%;">@isset($short_conten){{ $short_conten }}@endisset</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="auto_hide">auto hide: </label>
                                <input id="auto_hide" class="form-check-input" type="checkbox" name="auto_hide" value=" @if (isset($show)){{ $show ? 'checked' : '' }} @else checked @endif">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="category" class="col-sm-2">category:</label>
                            <div class="col-sm-10">
                                <div class="form-group">
                                    <select id="category_option" class="form-control" name="category_option[]"
                                        multiple="multiple">
                                        {!! $category_option !!}
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">image:</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <a id="lfm" data-input="thumbnail" data-preview="holder"
                                                class="btn btn-primary">
                                                <i class="fa fa-picture-o"></i> Choose
                                            </a>
                                        </span>
                                        <input id="thumbnail" class="form-control" type="text" name="image_path">
                                    </div>
                                    <img id="holder" style="margin-top:15px;max-height:100px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="conten" class="col-sm-2">page conten:</label>
                                <textarea style="height: 1000px" id="conten" name="conten" class="form-control">@if (isset($conten))
                                    {!! $conten !!}
                                @else
                                {!! old('content', '') !!}
                                @endif</textarea>
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
                            {{-- <input id="paper_writer" class="form-control" type="text" name="writer"> --}}
                            <div class="form-group">
                                <label for="paper_writer">writer:</label>
                                <select class="form-control" name="writer" id="paper_writer">
                                    @if ($writers)
                                        @foreach ($writers as $writer)
                                            <option value="{{ $writer->id }}">{{ $writer->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

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
    <script>
        var url_base = '{!! $filemanager_url_base !!}';
        $('#lfm').filemanager('file', {
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
    <script>
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
