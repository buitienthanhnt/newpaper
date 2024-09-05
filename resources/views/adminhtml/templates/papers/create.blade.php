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
        <div>
            <label for='hy'>Move stuff between these two containers. Note how the stuff gets inserted near the mouse
                pointer? Great stuff.</label>
            <form class="form-sample" method="POST" enctype="multipart/form-data" action={{ route('admin_paper_save') }}>
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Title:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="page_title" required />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="active" class="col-sm-1">Active:</label>
                            <div class="col-sm-1">
                                <input id="active" class="form-check-input" type="checkbox" name="active"
                                    {{ 'checked' }}>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label for="url-alias" class="col-sm-2">url alias:</label>
                            <div class="col-sm-8">
                                <input id="url-alias" class="form-control" type="text" name="alias" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 row">
                        <label for="show" class="col-sm-1">show:</label>
                        <div class="col-sm-1">
                            <input id="show" class="form-check-input" type="checkbox" name="show"
                                {{ 'checked' }}>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="short_conten" class="col-sm-2">short conten:</label>
                        <div class="col-sm-10">
                            <textarea id="short_conten" name="short_conten" class="form-control" rows="4"
                                style="padding: 10px; height: 100%;"></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="auto_hide">auto hide: </label>
                            <input id="auto_hide" class="form-check-input" type="checkbox" name="auto_hide"
                                {{ 'checked' }}>
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
                                    <input id="thumbnail" class="form-control" type="text" name="image_path"
                                        value="">
                                </div>
                                <img id="holder" style="margin-top:15px;max-height:100px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class='wrapper col-md-12'>
                    <div id='left-defaults' class='container' style="width: 30%">
                        <div style="height: 150px" data-type="p-carousel" ondragover="">
                            <p>paper carousel</p>
                        </div>

                        <div style="" data-type="p-html">
                            <p>paper html content</p>
                            <div class="data-content">
                                <textarea id="conten" name="conten" class="form-control" style="height: 720px; display: none"></textarea>
                            </div>
                        </div>
                        <div data-type="p-timeline">
                            <p>paper timeline</p>
                            <div class="data-content form-group" style="display: none">
                                <input name="time_line_value" id="timelineInput" />
                                <script type="text/javascript">
                                    // https://gijgo.com/datetimepicker
                                    $("#timelineInput").datetimepicker({
                                        datepicker: {
                                            showOtherMonths: true,
                                            calendarWeeks: true,
                                            todayHighlight: true
                                        },
                                        footer: true,
                                        modal: true,
                                        header: true,
                                        value: '',
                                        format: 'yyyy-mm-dd HH:MM:ss',
                                    });
                                </script>
                            </div>
                        </div>
                        <div data-type="p-price">
                            <p>paper price</p>
                            <div class="data-content form-group row" style="display: none">
                                <label for="price" class="col-sm-2">Price:</label>
                                <div class="col-sm-8">
                                    <input type="number" name="price" id="price" placeholder="nghìn vnđ"
                                        class="form-control" min="0"
                                        value="@isset($price)
                            {{ $price }}
                           @endisset">
                                </div>
                            </div>
                        </div>
                        <div data-type="p-picture">
                            <p>paper picture</p>
                            <div class="data-content input-group" style="display: none">
                                <span class="input-group-btn">
                                    <a id="lfm2" data-input="thumbnail2" data-preview="holder"
                                        class="btn btn-primary">
                                        <i class="fa fa-picture-o"></i> Choose
                                    </a>
                                </span>
                                <input id="thumbnail2" class="form-control" type="text" name="image_path2">
                            </div>
                            <img id="holder" style="margin-top:15px;max-height:100px;">

                        </div>
                        <div style="height: 150px" data-type="p-video">
                            <p>paper video</p>
                        </div>
                    </div>
                    <div id='right-defaults' class='container col-md-7' style="background-color: rgb(115, 150, 136)">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-info btn-lg"
                                style="width: -webkit-fill-available;">save</button>
                        </div>
                    </div>
                </div>
            </form>
            <pre style="background-color: rgb(97, 53, 148)">
                <code>
                    dragula([
                        document.getElementById('left-defaults'), 
                        document.getElementById('right-defaults')
                    ]);
                </code>
            </pre>
        </div>
        <script>
            // https://github.com/bevacqua/dragula
            var dra = dragula(
                [document.getElementById('left-defaults'), document.getElementById('right-defaults')], {
                    isContainer: function(el) {
                        return false; // only elements in drake.containers will be taken into account
                    },
                    moves: function(el, source, handle, sibling) {
                        return true; // elements are always draggable by default
                    },
                    accepts: function(el, target, source, sibling) {
                        return true; // elements can be dropped in any of the `containers` by default
                    },
                    invalid: function(el, handle) {
                        return false; // don't prevent any drags from initiating by default
                    },
                    direction: 'vertical', // Y axis is considered when determining where an element would be dropped
                    copy: false, // elements are moved by default, not copied
                    copySortSource: true, // elements in copy-source containers can be reordered
                    revertOnSpill: false, // spilling will put the element back where it was dragged from, if this is true
                    removeOnSpill: false, // spilling will `.remove` the element, if this is true
                    mirrorContainer: document.body, // set the element that gets mirror elements appended
                    ignoreInputTextSelection: true, // allows users to select input text, see details below
                    slideFactorX: 0, // allows users to select the amount of movement on the X axis before it is considered a drag instead of a click
                    slideFactorY: 0, // allows users to select the amount of movement on the Y axis before it is considered a drag instead of a click
                }
            ).on('dragend', function(el) {
                console.log('dragend', $($(el).parent()[0]).attr('id'));
                if ($($(el).parent()[0]).attr('id') === 'left-defaults') {
                    $($(el).children()[0]).show();
                    $($(el).children()[1]).hide();
                    return;
                }

                $(el).click(function(params) {
                    // console.log(222345, params, $(this).attr('data-type'));
                    let p_type = $(this).attr('data-type');
                    switch (p_type) {
                        case 'p-html':
                            $($(this).children()[0]).hide();
                            $($($(this).children()[1]).children()[0]).show();
                            encodeURI('\uD800\uDFFF');
                            tinymce.init({
                                convert_urls: false,
                                selector: "textarea#conten",
                                plugins: ["image", "table", "code", "codesample",
                                    "showcomments", "media"
                                ],
                                toolbar1: 'undo redo | fontfamily fontsize styles bold italic underline | alignleft aligncenter alignright alignjustify alignnone | indent outdent | wordcount | lineheight help image media',
                                toolbar2: 'anchor | blockquote | backcolor forecolor | copy | cut | paste pastetext | hr | language | newdocument | print | remove removeformat | selectall | strikethrough | subscript superscript | visualaid | a11ycheck typopgraphy anchor restoredraft casechange charmap checklist ltr rtl editimage fliph flipv imageoptions rotateleft rotateright emoticons export footnotes footnotesupdate formatpainter fullscreen insertdatetime link openlink unlink bullist numlist mergetags mergetags_list nonbreaking pagebreak pageembed permanentpen preview quickimage quicklink cancel save searchreplace spellcheckdialog spellchecker | template typography | insertfile | visualblocks visualchars',
                                file_picker_callback: function(callback, value, meta) {
                                    let x = window.innerWidth || document.documentElement
                                        .clientWidth || document
                                        .getElementsByTagName('body')[0].clientWidth;
                                    let y = window.innerHeight || document.documentElement
                                        .clientHeight || document
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
                            })
                            break;
                        case 'p-price':
                        case 'p-timeline':
                        case 'p-picture':
                            $($(this).children()[0]).hide();
                            $($(this).children()[1]).show();
                            break;
                        default:
                            break;
                    }
                });
            }).on('remove', function(el) {
                console.log('remove');

            }).on('drop', function(el) {
                console.log('drop');

            }).on('cancel', function(el) {
                console.log('cancel');

            });
        </script>
    </div>
@endsection

@section('before_bottom_js')
    <script type="text/javascript">
        var url_base = '{!! $filemanager_url_base !!}';
        $('#lfm').filemanager('file', {
            prefix: url_base
        });

        $('#lfm2').filemanager('file', {
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

        // for conten of paper process
        $(document).ready(function() {
            // $("#right-defaults").children().click(function (params) {
            //     console.log(123123, params);
            // })
        });
    </script>
@endsection

@section('after_js')
    {{-- <script type="text/javascript">
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
    </script> --}}
@endsection
