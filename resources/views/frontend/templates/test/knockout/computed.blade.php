@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <p>
        The name is <span data-bind="text: fullName"></span>
    </p>
    <script type="text/javascript">
        // quan sat 1 mang // https://knockoutjs.com/documentation/computed-reference.html
        function AppViewModel() {
            var self = this;
            self.firstName = ko.observable('Bob');   // gán thuộc tính
            self.lastName = ko.observable('Smith');  // gán thuộc tính
            self.fullName = ko.computed(function() { // gán thuộc tính phụ thuộc
                return self.firstName() + " " + self.lastName();
            });
        }
        // console.log(AppViewModel);

        // hàm applyBindings là để đăng ký quan sát cho model(bắt buộc), nhất định phải có. 
        ko.applyBindings(AppViewModel);
    </script>
@endsection
