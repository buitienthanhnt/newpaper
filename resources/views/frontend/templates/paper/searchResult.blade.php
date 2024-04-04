@extends('frontend.layouts.pagestruct')

@section('page_top_head')
    @include('frontend.templates.page_top_head')
    <script src="{{ asset('assets/frontend/js/commentReply.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/commentHistory.js') }}"></script>
@endsection

@section('page_header')
    <!-- Preloader Start -->
    @include('frontend.templates.page_header')
@endsection

@section('page_footer')
    @include('frontend.templates.page_footer')
@endsection


@section('title')
    detail page
@endsection

@section('css_after')
    <link rel="stylesheet" href={{ asset('assets/frontend/css/paper/detail.css') }}>
@endsection

@section('main_conten')
    <div class="about-area2 gray-bg pt-60 pb-60">
        <div class="container">
            <div class="row">
                @if (count($papers))
                    @foreach ($papers as $item)
                        <div class="card" style="">
                            <div class="card-body">
                                <a class="card-title"
                                    href="{{ route('front_page_detail', ['alias' => $item->url_alias, 'page' => $item->id]) }}">{{ $item->title }}</a>
                                <p class="card-text">{{ $item->short_conten }}</p>
                                {{-- <a href="#" class="btn btn-primary">Go somewhere</a> --}}
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="card">
                        <p>
                            The name is <span data-bind="text: personName, click: changeName"></span>
                        <p style="color: blue" data-bind="text:personAge"></p>
                        </p>
                        {{-- <p >legnth of obserable <span data-bind="text: anotherObservableArray.length"></span> </p> --}}

                        <div class="card-header">
                            Not result
                        </div>
                        <div class="card-body">
                            <blockquote class="blockquote mb-0">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                                <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source
                                        Title</cite></footer>
                            </blockquote>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var myViewModel = {
            personName: ko.observable('Bob 234d'),
            personAge: ko.observable(123),
            changeName: function() {
                // var previousCount = this.numberOfClicks();
                this.personName(this.personName() + ' change to new val');
            }
        };

        // lắng nghe sau khi thay đổi
        myViewModel.personName.subscribe(function(newValue) {
            alert("The person's new name is " + newValue);
        });

        // lắng nghe trước khi thay đổi dùng: beforeChange
        myViewModel.personName.subscribe(function(oldValue) {
            alert("The person's previous name is " + oldValue);
        }, null, "beforeChange");

        // observable 1 object
        ko.applyBindings(myViewModel);

        // observable 1 Array
        // var anotherObservableArray = ko.observableArray(
        //     [
        //         {
        //             name: "Bungle",
        //             type: "Bear"
        //         },
        //         {
        //             name: "George",
        //             type: "Hippo"
        //         },
        //         {
        //             name: "Zippy",
        //             type: "Unknown"
        //         }
        //     ]
        // );
    </script>
@endsection
