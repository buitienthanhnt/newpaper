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

@section('body_main_conten')
    <div class="col-12 grid-margin">
        <div class="">
            <div class="card-body">
                <h4 class="card-title">add new file to source</h4>
                <form class="form-sample" method="POST" enctype="multipart/form-data" action={{ route('admin_file_save') }}>
                    @csrf
                    @if (session('success'))
                        <div class="alert alert-success" id="save_image_success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label>Content</label>
                                <textarea id="my-editor" name="content" class="form-control">{!! old('content', 'test editor content') !!}</textarea>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">file name:</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="file_name" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
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
        save_image.onchange = evt => {
            const [file] = save_image.files
            if (file) {
                $(priview_image).show();
                priview_image.src = URL.createObjectURL(file)
            } else {
                $(priview_image).hide();
            }
        }
    </script>
@endsection

@section('after_js')
    <script>
        // CKEDITOR.replace('my-editor');
        // CKEDITOR.replace( 'editor1', {
        //     filebrowserBrowseUrl: '{{ asset('ckfinder/ckfinder.html') }}',
        //     filebrowserImageBrowseUrl: '{{ asset('ckfinder/ckfinder.html?type=Images') }}',
        //     filebrowserFlashBrowseUrl: '{{ asset('ckfinder/ckfinder.html?type=Flash') }}',
        //     filebrowserUploadUrl: '{{ asset('ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files') }}',
        //     filebrowserImageUploadUrl: '{{ asset('ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images') }}',
        //     filebrowserFlashUploadUrl: '{{ asset('ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash') }}'
        // } );

        tinymce.init({
            selector: "textarea#my-editor",
            plugins: [
                "image"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image ",
            file_picker_callback: function(callback, value, meta) {
                let x = window.innerWidth || document.documentElement.clientWidth || document
                    .getElementsByTagName('body')[0].clientWidth;
                let y = window.innerHeight || document.documentElement.clientHeight || document
                    .getElementsByTagName('body')[0].clientHeight;

                let type = 'image' === meta.filetype ? 'Images' : 'Files',
                    url = 'http://laravel1.com/adminhtml/laravel-filemanager?editor=tinymce5';

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
