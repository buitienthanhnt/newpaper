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
                padding: 6px !important;
                font-size: larger !important;
                font-family: inherit;
                line-height: 1;
            }

            .sliderImages {
                max-height: 480px !important;
            }
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
        <h4 class="card-title">Design new source</h4>
        <form class="form-sample" method="POST" enctype="multipart/form-data" action={{ route('admin_paper_save') }}>
            @csrf
            {!! view('elements.message.index')->render() !!}

            <div class="row">
                <div class="col-md-6">
                    {!! view('elements.formFields.textField', [
                        'label' => 'Title',
                        'name' => 'page_title',
                        'require' => true,
                        'value' => $value ?? null,
                    ])->render() !!}
                </div>

                <div class="col-md-6">
                    {!! view('elements.formFields.checkBox', ['label' => 'Active', 'name' => 'active', 'checked' => true])->render() !!}
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    {!! view('elements.formFields.textField', ['label' => 'Url alias', 'name' => 'alias'])->render() !!}
                </div>

                <div class="col-md-6">
                    {!! view('elements.formFields.checkBox', ['label' => 'Show', 'name' => 'show', 'checked' => true])->render() !!}
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    {!! view('elements.formFields.textArea', ['label' => 'Short conten', 'name' => 'short_conten'])->render() !!}
                </div>

                <div class="col-md-6">
                    {!! view('elements.formFields.checkBox', [
                        'label' => 'Auto hide',
                        'name' => 'auto_hide',
                        'checked' => true,
                    ])->render() !!}
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="col-sm-10">
                        {!! view('elements.formFields.select2Fields', [
                            'label' => 'Select category',
                            'id' => 'category_option',
                            'name' => 'category_option',
                            'options' => $category_option,
                            'place_holder' => 'Select an option',
                            'token_separators' => [',', ' '],
                            'multiple' => true
                        ])->render() !!}
                    </div>
                </div>

                <div class="col-md-6">
                    {!! view('elements.formFields.chooseFile', [
                        'label' => 'Thumbnail image',
                        'id' => 'lfm_1',
                        'input_id' => 'input_id_1',
                        'name' => 'image_path',
                        'preview_id' => 'preview_id_1',
                    ])->render() !!}
                </div>
            </div>

            <div class='wrapper col-md-12'>
                <div id='left-defaults' class='container' style="width: 30%">
                    <div data-type="p-carousel" ondragover="">
                        <p>paper carousel</p>
                        <div class="data-content" style="display: none">
                            <button type="button" class="btn form-control btn-primary" data-toggle="modal" id="addSlider">
                                Add slider item
                            </button>
                            <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                            </div>
                            <textarea id="sliderDataConten" style="display: none" name="slider_data"></textarea>
                            <div class="modal fade" id="sliderModal" tabindex="-1" role="dialog"
                                aria-labelledby="sliderModal" aria-hidden="true">
                                <div class="modal-dialog modal-lg" style="max-width: 800px" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Input item content</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="captions_label">captions label</label>
                                                <input id="captions_label" class="form-control" type="text">
                                            </div>

                                            <div class="form-group">
                                                <label for="captions_content">captions content</label>
                                                <textarea id="captions_content" class="form-control" style="height: auto" name="captions_content" rows="6"></textarea>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">image:</label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group">
                                                            <span class="input-group-btn">
                                                                <a id="slider_image" data-input="slider_images"
                                                                    data-preview="holder" class="btn btn-primary">
                                                                    <i class="fa fa-picture-o"></i> Choose
                                                                </a>
                                                            </span>
                                                            <input id="slider_images" class="form-control" type="text">
                                                        </div>
                                                        <img id="holder" style="margin-top:15px;max-height:100px;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                                id="closeSliderImages">Close
                                            </button>
                                            <button type="button" class="btn btn-primary" id="saveCarouiselItem">Save
                                                changes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="" data-type="p-html">
                        <div class="demo-content-source">
                            <p class="text-primary"
                                style="font-size: 16px; font-weight: 600; text-decoration: underline;">view demo
                                frontend(pull right to add content):</p>
                            @isset($conten)
                                <style>
                                    .demo-content-source img {
                                        max-width: 30% !important;
                                        width: auto !important;
                                        height: auto !important;
                                    }
                                </style>
                                {!! $conten !!}
                            @else
                                <p>paper html content</p>
                            @endisset
                        </div>
                        <div class="data-content">
                            <textarea id="conten" name="conten" class="form-control" style="height: 720px; display: none">
                                @isset($conten)
{{ $conten }}
@endisset
                            </textarea>
                        </div>
                    </div>

                    <div data-type="p-timeline">
                        <p>paper timeline</p>
                        <div class="data-content form-group" style="display: none">
                            <div class="col-md-12 r">
                                <label for="time_line_type" class="col-sm-2 col-form-label">TimeLine:</label>
                                <div class="col-sm-10">
                                    <div class="form-group">
                                        <select id="time_line_type" class="form-control" name="time_line_type"
                                            multiple="multiple">
                                            {!! $time_line_option !!}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" style="align-content: center">
                                <div class="form-group row" style="margin-bottom: 0px">
                                    <label for="url-alias" class="col-sm-2">Timeline:</label>
                                    <div class="cs-form col-sm-8">
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
                            </div>
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
                            <div>
                                <span class="input-group-btn">
                                    <a class="btn btn-primary">
                                        <i class="fa fa-picture-o"></i> Choose
                                    </a>
                                </span>
                            </div>
                            <input class="form-control" type="text" multiple>
                            <div class="input-group">
                                <span class="input-group-btn">description:&nbsp;</span>
                                <input class="form-control" type="text" name="">
                            </div>
                        </div>
                        <img id="holder" style="margin-top:15px;max-height:100px;">
                    </div>
                    <div style="height: 150px" data-type="p-video">
                        <p>paper video</p>
                    </div>
                </div>
                <div id='right-defaults' class='container col-md-7'>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    {!! view('elements.formFields.select2Fields', [
                        'id' => 'paper_tag',
                        'label' => 'Tag for links',
                        'name' => 'paper_tag',
                        'place_holder' => 'liên kết',
                        'token_separators' => [','],
                        'multiple' => true
                    ])->render(); !!}
                </div>
                <div class="col-md-6">
                    {!! view('elements.formFields.select2Fields', [
                        'label' => 'Writer',
                        'id' => 'paper_writer',
                        'name' => 'writer',
                        'options' => $writers,
                        'token_separators' => [''],
                        'multiple' => false
                    ])->render(); !!}
                </div>
            </div>

            <div class="row justify-content-center mt-2">
                <div class="col-md-6">
                    {!! view('elements.formFields.submitBtn')->render() !!}
                </div>
            </div>
        </form>

        <script>
            function imgOnclick(type = 'file', options = {
                prefix: filemanager_url_base,
            }) {
                var route_prefix = (options && options.prefix) ? options.prefix : '/filemanager';
                var target_input = $('#' + $(this).data('input'));
                var target_preview = $('#' + $(this).data('preview'));
                window.open(route_prefix + '?type=' + type, 'FileManager', 'width=900,height=600');
                window.SetUrl = function(items) {
                    var file_path = items.map(function(item) {
                        return item.url;
                    }).join(',');

                    // set the value of the desired input to image url
                    target_input.val('').val(file_path).trigger('change');

                    // clear previous preview
                    target_preview.html('');

                    // set or change the preview image src
                    items.forEach(function(item) {
                        target_preview.append(
                            $('<img>').css('height', '5rem').attr('src', item.thumb_url)
                        );
                    });

                    // trigger change event
                    target_preview.trigger('change');
                };
                return false;
            }

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
                    copy: function(el, source) {
                        return $(el).attr('data-type') === 'p-picture' && $(source).attr('id') === 'left-defaults';
                    }, // elements are moved by default, not copied
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
                if ($(el).attr('data-type') === 'p-picture' && $($(el).parent()[0]).attr('id') === 'left-defaults') {
                    if ($("div[data-type=p-picture]").length > 1) {
                        $(el).remove();
                    }
                    return;
                }

                if ($($(el).parent()[0]).attr('id') === 'left-defaults') {
                    $($(el).children()[0]).show();
                    $($(el).children()[1]).hide();
                    return;
                }

                $(el).click(function(params) {
                    // console.log(params, $(this).attr('data-type'));
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
                        case 'p-picture':
                            $($(this).children()[0]).hide();
                            $($(this).children()[1]).show();
                            const pictute_thum = 'imagex_' + $("#right-defaults > div[data-type=p-picture]")
                                .length;
                            const id_thum = 'lfm_' + pictute_thum;
                            $($($($(this).children()[1]).children()[0]).children()[0]).attr("id", id_thum).attr(
                                "data-input", pictute_thum);
                            $($($(this).children()[1]).children()[1]).attr("id", pictute_thum).attr("name",
                                "images_" + pictute_thum);
                            // set image desciption name:
                            const pictute_desc = 'imagex_desc_' + $(
                                    "#right-defaults > div[data-type=p-picture]")
                                .length;
                            $($($($(this).children()[1]).children()[2]).children()[1]).attr('name',
                                pictute_desc)
                            // gán sự kiện cho nút: 'choose image'.
                            $($($($(this).children()[1]).children()[0]).children()[0]).on('click', imgOnclick);
                            $(this).off('click');
                        case 'p-price':
                        case 'p-timeline':
                        case 'p-carousel':
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
        // slider_image
        $('#slider_image').filemanager('file', {
            prefix: filemanager_url_base
        });

        $("#time_line_type").select2({
            placeholder: 'Select an value',
            maximumSelectionLength: 1
        });
    </script>
@endsection

@section('after_js')
    <script>
        var slider = [];

        function renderCarousel(data) {
            let beginIndicator = '<ol class="carousel-indicators">';
            let content = ' <div class="carousel-inner">';
            let change =
                '<a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">' +
                '<span class="carousel-control-prev-icon" aria-hidden="true"></span>' +
                '<span class="sr-only">Previous</span>' +
                '</a>' +
                '<a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">' +
                '<span class="carousel-control-next-icon" aria-hidden="true"></span>' +
                '<span class="sr-only">Next</span>' +
                '</a>';
            for (let i = 0; i < slider.length; i++) {
                beginIndicator += '<li data-target="#carouselExampleCaptions" data-slide-to="' + i + '" class="' + (
                    i == 0 ? 'active' : ' ') + '"></li>';

                content += '<div class="carousel-item ' + (i == 0 ? 'active' : '') + '">' +
                    '<img class="d-block w-100 sliderImages" src="' + (slider[i].image_path) + '" >' +
                    '<div class="carousel-caption d-none d-md-block">' +
                    '<h5>' + (slider[i].title) + '</h5>' +
                    '<p>' + (slider[i].label) + '</p>' +
                    '</div>' +
                    '</div>';
            }
            beginIndicator += '</ol>';
            content += ' </div>';
            return beginIndicator + content + change;
        }

        $(document).ready(function() {
            $("#carouselExampleCaptions").html(renderCarousel(slider));

            $("#addSlider").click(function() {
                $("#sliderModal").modal('show');
            });
            $("#closeSliderImages").click(function() {
                $("#sliderModal").modal('hide');
            })

            $("#saveCarouiselItem").click(function() {
                let title = $("#captions_label").val();
                let content = $("#captions_content").val();
                let image_path = $("#slider_images").val();
                slider.push({
                    title: title,
                    label: content,
                    image_path: image_path
                });
                $("#carouselExampleCaptions").html(renderCarousel(slider));
                $("#sliderDataConten").val(JSON.stringify(slider));
                $("#sliderModal").modal('hide');
            });
        })
    </script>
@endsection
