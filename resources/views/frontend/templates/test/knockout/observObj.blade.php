@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <p>
        The name is <span data-bind="text: personName, click: changeName"></span>
    <p style="color: blue" data-bind="text:personAge"></p>
    </p>

    <script type="text/javascript">
        // khai bao 1 object data
        var myViewModel = {
            personName: ko.observable('Bob 234d'),
            personAge: ko.observable(123),
            changeName: function() {
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

        // observable 1 object: (gán lắng nghe cho objectData)
        ko.applyBindings(myViewModel);
    </script>
@endsection
