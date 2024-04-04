@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <h4>People</h4>
    <ul data-bind="foreach: people">
        <li>
            Name at position <span data-bind="text: $index"> </span>:
            <span data-bind="text: name"> </span>
            <a href="#" data-bind="click: $parent.removePerson">Remove</a>
        </li>
    </ul>
    <button data-bind="click: addPerson">Add</button>

    <script type="text/javascript">
    // https://knockoutjs.com/documentation/foreach-binding.html
        function AppViewModel() {
            var self = this;

            self.people = ko.observableArray([{
                    name: 'Bert'
                },
                {
                    name: 'Charles'
                },
                {
                    name: 'Denise'
                }
            ]);

            self.addPerson = function() {
                self.people.push({
                    name: "New at " + new Date()
                });
            };

            self.removePerson = function() {
                self.people.remove(this);
            }
        }

        // Lưu ý 1: Tham chiếu đến từng mục mảng bằng $data
        // Lưu ý 2: Sử dụng $index, $parent và các thuộc tính ngữ cảnh khác
        // Lưu ý 3: Sử dụng “as” để đặt bí danh cho mục “foreach”
        // Lưu ý 4: Sử dụng foreach không có phần tử vùng chứa
        // Lưu ý 5: Cách phát hiện và xử lý các thay đổi mảng
        // Lưu ý 6: Ẩn các mục bị hủy
        // Lưu ý 7: Xử lý hậu kỳ hoặc tạo hoạt ảnh cho các phần tử DOM được tạo // hiệu ứng

        ko.applyBindings(new AppViewModel());
    </script>
@endsection

@section('content_page_ex1')
    <table>
        <thead>
            <tr>
                <th>First name</th>
                <th>Last name</th>
            </tr>
        </thead>
        <tbody data-bind="foreach: people">
            <tr>
                <td data-bind="text: firstName"></td>
                <td data-bind="text: lastName"></td>
            </tr>
        </tbody>
    </table>

    <script type="text/javascript">
        let viewModel = {
            people: [{
                    firstName: 'Bert',
                    lastName: 'Bertington'
                },
                {
                    firstName: 'Charles',
                    lastName: 'Charlesforth'
                },
                {
                    firstName: 'Denise',
                    lastName: 'Dentiste'
                }
            ]
        };
        // Causes the "profitPositive" class to be removed and "profitWarning" class to be added
        // viewModel.currentProfit(-50);
        ko.applyBindings(viewModel);
    </script>
@endsection
