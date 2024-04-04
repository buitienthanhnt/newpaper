@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page-ex1')
    <div>
        <div>First name: <span data-bind="text: firstName"></span></div>
        <div>Last name: <span data-bind="text: lastName"></span></div>
        <div class="heading">Hello, <input data-bind="textInput: fullName" /></div>
    </div>

    <script type="text/javascript">
        // https://knockoutjs.com/documentation/computed-reference.html
        // hàm applyBindings là để đăng ký quan sát cho model(bắt buộc), nhất định phải có. 
        function MyViewModel() {
            this.firstName = ko.observable('Planet');
            this.lastName = ko.observable('Earth');

            // pureComputed có thêm 2 tính năng: read và write
            this.fullName = ko.pureComputed({
                read: function() { // trả về giá trị cho ô input
                    return this.firstName() + " " + this.lastName();
                },
                write: function(value) {
                    console.log('----', value);
                    var lastSpacePos = value.lastIndexOf(" ");
                    if (lastSpacePos > 0) { // Ignore values with no space character
                        this.firstName(value.substring(0, lastSpacePos)); // Update "firstName"
                        this.lastName(value.substring(lastSpacePos + 1)); // Update "lastName"
                    }
                },
                owner: this
            });
        }

        ko.applyBindings(new MyViewModel());
    </script>
@endsection

@section('content_page-ex2')
    <div>
        <div class="heading">
            <input type="checkbox" data-bind="checked: selectedAllProduce" title="Select all/none" /> Produce
        </div>
        <div data-bind="foreach: produce">
            <label>
                <input type="checkbox" data-bind="checkedValue: $data, checked: $parent.selectedProduce" />
                <span data-bind="text: $data"></span>
            </label>
        </div>
    </div>

    <script type="text/javascript">
        function MyViewModel() {
            this.produce = ['Apple', 'Banana', 'Celery', 'Corn', 'Orange', 'Spinach']; // giá trị mảng ban đầu
            this.selectedProduce = ko.observableArray(['Corn', 'Orange']); // mảng giá trị được chọn
            this.selectedAllProduce = ko.pureComputed({
                read: function() {
                    // Comparing length is quick and is accurate if only items from the
                    // main array are added to the selected array.
                    return this.selectedProduce().length === this.produce.length; // nếu bằng nhau trả về true
                },
                write: function(value) {
                    this.selectedProduce(value ? this.produce.slice(0) : []); // gán giá trị toàn bộ hoặc không
                },
                owner: this
            });
        }
        ko.applyBindings(new MyViewModel());
    </script>
@endsection

@section('content_page')
    <div>
        <div class="log" data-bind="text: computedLog"></div>
        <!--ko if: step() == 0-->
        <p>First name: <input data-bind="textInput: firstName" /></p>
        <!--/ko-->
        <!--ko if: step() == 1-->
        <p>Last name: <input data-bind="textInput: lastName" /></p>
        <!--/ko-->
        <!--ko if: step() == 2-->
        <div>Prefix: 
            <select 
                data-bind="
                    value: prefix, 
                    options: ['Mr.', 'Ms.','Mrs.','Dr.']
                ">
            </select>
        </div>
        <h2>Hello, <span data-bind="text: fullName"> </span>!</h2>
        <!--/ko-->

        <p><button type="button" data-bind="click: next">Next</button></p>
    </div>

    <script type="text/javascript">
        function AppData() {
            this.firstName = ko.observable('John');
            this.lastName = ko.observable('Burns');
            this.prefix = ko.observable('Dr.');
            this.computedLog = ko.observable('Log: ');
            this.fullName = ko.pureComputed(function() {
                var value = this.prefix() + " " + this.firstName() + " " + this.lastName();
                // Normally, you should avoid writing to observables within a pure computed 
                // observable (avoiding side effects). But this example is meant to demonstrate 
                // its internal workings, and writing a log is a good way to do so.
                this.computedLog(this.computedLog.peek() + value + '; ');
                return value;
            }, this);

            this.step = ko.observable(0);
            this.next = function() {
                this.step(this.step() === 2 ? 0 : this.step() + 1);
            };
        };
        ko.applyBindings(new AppData());
    </script>
@endsection
