@extends('frontend.templates.test.knockout.baseTemplate')


@section('content_page')
    <p>Send me spam: <input type="checkbox" data-bind="checked: wantsSpam" /></p>

	{{-- dung: wantsSpam() cho cac thuoc tinh chi doc nhu text, visible; con vua doc vua ghi nhu: checked thi khong dung: ()   --}}
	{{-- dung phu dinh voi cac gia tri chi doc thi can dung: () vi du:  visible: !wantsSpam() --}}
    <div data-bind="visible: wantsSpam()"> 
        Preferred flavors of spam:
        <div><input type="checkbox" value="cherry" data-bind="checked: spamFlavors" /> Cherry</div>
        <div><input type="checkbox" value="almond" data-bind="checked: spamFlavors" /> Almond</div>
        <div><input type="checkbox" value="msg" data-bind="checked: spamFlavors" /> Monosodium Glutamate</div>
    </div>

    <script type="text/javascript">
        var viewModel = {
            wantsSpam: ko.observable(false),
            spamFlavors: ko.observableArray(["cherry", "almond"]) // Initially checks the Cherry and Almond checkboxes
        };

        // ... then later ...
        viewModel.spamFlavors.push("msg"); // Now additionally checks the Monosodium Glutamate checkbox
        ko.applyBindings(viewModel);

        // viewModel.wantsSpam(false); // The checkbox becomes unchecked
    </script>
@endsection



@section('content_page-ex1')
    <p>Send me spam: <input type="checkbox" data-bind="checked: wantsSpam" /></p>


    <script type="text/javascript">
        var viewModel = {
            wantsSpam: ko.observable(true) // Initially checked
        };

        // ... then later ...
        // viewModel.wantsSpam(false); // The checkbox becomes unchecked
        ko.applyBindings(viewModel);

        // viewModel.wantsSpam(false); // The checkbox becomes unchecked
    </script>
@endsection
