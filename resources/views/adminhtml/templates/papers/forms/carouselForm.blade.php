<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Title:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="page_title" required
                    value="@isset($title){{ $title }}@endisset" required
                    placeholder="page title" />
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group row">
            <label for="active" class="col-sm-1">Active:</label>
            <div class="col-sm-1">
                <input id="active" class="form-check-input" type="checkbox" name="active"
                    value="@if (isset($active)) {{ $active ? 'checked' : '' }} @else checked @endif">
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
            <label for="url-alias" class="col-sm-2">url alias:</label>
            <div class="col-sm-8">
                <input id="url-alias" class="form-control" type="text" name="alias"
                    placeholder="use page title if this value is null"
                    value="@isset($url_alias){{ $url_alias }}@endisset">
            </div>
        </div>
    </div>

    <div class="col-md-6 row">
        <label for="show" class="col-sm-1">show:</label>
        <div class="col-sm-1">
            <input id="show" class="form-check-input" type="checkbox" name="show"
                value="@if (isset($show)) {{ $show ? 'checked' : '' }} @else checked @endif">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <label for="short_conten" class="col-sm-2">short conten:</label>
        <div class="col-sm-10">
            <textarea id="short_conten" name="short_conten" class="form-control" rows="4"
                style="padding: 10px; height: 100%;">
@isset($short_conten)
{{ $short_conten }}
@endisset
</textarea>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="auto_hide">auto hide: </label>
            <input id="auto_hide" class="form-check-input" type="checkbox" name="auto_hide"
                value=" @if (isset($show)) {{ $show ? 'checked' : '' }} @else checked @endif">
        </div>
    </div>
</div>

<div class="row">

    {{-- <div class="col-md-6">
        <label for="category" class="col-sm-2">category:</label>
        <div class="col-sm-10">
            <div class="form-group">
                <select id="category_option" class="form-control" name="category_option[]"
                    multiple="multiple">
                    {!! $category_option !!}
                </select>
            </div>
        </div>
    </div> --}}

    <div class="col-md-6">
        <div class="form-group">
            <label class="col-sm-6 col-form-label">slider image lists:</label>
            <div class="col-sm-9">
                <div class="form-group">
                    {{-- <span class="input-group-btn">
                        <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                            <i class="fa fa-picture-o"></i> Choose images
                        </a>
                    </span> --}}
                    <button type="button" class="btn form-control btn-primary" data-toggle="modal" id="addSlider">
                        add slider item
                    </button>

                    <textarea id="sliderDataConten" style="display: none" name="slider_data"></textarea>


                    <div class="modal fade" id="sliderModal" tabindex="-1" role="dialog" aria-labelledby="sliderModal"
                        aria-hidden="true">
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
                                        <input id="captions_label" class="form-control" type="text"
                                            name="captions_label">
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
                                                        <a id="lfm" data-input="thumbnail"
                                                            data-preview="holder" class="btn btn-primary">
                                                            <i class="fa fa-picture-o"></i> Choose
                                                        </a>
                                                    </span>
                                                    <input id="thumbnail" class="form-control" type="text"
                                                        name="image_path">
                                                </div>
                                                <img id="holder" style="margin-top:15px;max-height:100px;">
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                        id="closeSliderImages">Close</button>
                                    <button type="button" class="btn btn-primary" id="saveCarouiselItem">Save
                                        changes</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- <input id="thumbnail" class="form-control" type="text" name="image_paths"> --}}
                </div>
                <img id="holder" style="margin-top:15px;max-height:100px;">
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <label class="col-sm-6 col-form-label">paper carousel preview: </label>
        <div class="col-md-6">
            <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
            </div>
        </div>
    </div>
</div>

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
            let image_path = $("#thumbnail").val();
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
