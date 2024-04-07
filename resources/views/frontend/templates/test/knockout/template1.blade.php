@extends('frontend.templates.test.knockout.baseTemplate')


@section('content_page')
    <ul data-bind='template: { name: displayMode, foreach: employees }'> </ul>

	<script type="text/html" id="active">
		<h3 data-bind="text: name"></h3>
	</script>

    <script type="text/html" id="inactive">
		htnl name: <h5 data-bind="text: name"></h5>
	</script>

    <script type="text/javascript">
        // Lưu ý 5: Tự động chọn mẫu nào được sử dụng
        var viewModel = {
            employees: ko.observableArray([{
                    name: "Kari",
                    active: ko.observable(true)
                },
                {
                    name: "Brynn",
                    active: ko.observable(false)
                },
                {
                    name: "Nora",
                    active: ko.observable(false)
                }
            ]),
            displayMode: function(employee) {
                // Initially "Kari" uses the "active" template, while the others use "inactive"
                return employee.active() ? "active" : "inactive";
            }
        };

        // ... then later ...
        viewModel.employees()[1].active(true); // Now "Brynn" is also rendered using the "active" template.
		ko.applyBindings(viewModel);
		
		// Lưu ý 6: Sử dụng jQuery.tmpl, một công cụ mẫu dựa trên chuỗi bên ngoài
		// Lưu ý 7: Sử dụng công cụ mẫu Underscore.js
    </script>
@endsection

@section('content_page-ex3')
    <ul
        data-bind="template: { 
		name: 'seasonTemplate', 
		foreach: seasons, 
		as: 'season',
		afterRender: myPostProcessingLogic
	 }">
    </ul>

    <script type="text/html" id="seasonTemplate">
		<li>
			<strong data-bind="text: name"></strong>
			<ul data-bind="template: { name: 'monthTemplate', foreach: months, as: 'month' }"></ul>
		</li>
	</script>

    <script type="text/html" id="monthTemplate">
		<li>
			<span data-bind="text: month"></span>
			is in
			<span data-bind="text: season.name"></span>
		</li>
	</script>

    <script type="text/javascript">
        // Lưu ý 3: Sử dụng "as" để đặt bí danh cho các mục "foreach"
        var viewModel = {
            seasons: ko.observableArray([{
                    name: 'Spring',
                    months: ['March', 'April', 'May']
                },
                {
                    name: 'Summer',
                    months: ['June', 'July', 'August']
                },
                {
                    name: 'Autumn',
                    months: ['September', 'October', 'November']
                },
                {
                    name: 'Winter',
                    months: ['December', 'January', 'February']
                }
            ]),
            myPostProcessingLogic: function(val) {
                console.log('====================================');
                console.log(val);
            }
        };
        ko.applyBindings(viewModel);

        // Lưu ý 4: Sử dụng "afterRender", "afterAdd" và "beforeRemove"
    </script>
@endsection

@section('content_page-ex2')
    <h2>Participants</h2>
    Here are the participants:
    <div data-bind="template: { name: 'person-template', foreach: people }"></div>

    <script type="text/html" id="person-template">
		<h3 data-bind="text: name"></h3>
		<p>Credits: <span data-bind="text: credits"></span></p>
	</script>

    <script type="text/html" id="person-template2">
		htnl name: <h3 data-bind="text: name"></h3>
		<p>tha nan edit Credits: <span data-bind="text: credits"></span></p>
	</script>

    <script type="text/javascript">
        // Lưu ý 2: Sử dụng tùy chọn "foreach" với một mẫu được đặt tên
        function MyViewModel() {
            this.people = [{
                    name: 'Franklin',
                    credits: 250
                },
                {
                    name: 'Mario',
                    credits: 5800
                }
            ]
        }
        ko.applyBindings(new MyViewModel());
    </script>
@endsection

@section('content_page-ex1')
    <h2>Participants</h2>
    Here are the participants:
    <div data-bind="template: { name: 'person-template', data: buyer }"></div>

    <div data-bind="template: { name: 'person-template2', data: seller }"></div>

    <script type="text/html" id="person-template">
		<h3 data-bind="text: name"></h3>
		<p>Credits: <span data-bind="text: credits"></span></p>
	</script>

    <script type="text/html" id="person-template2">
		htnl name: <h3 data-bind="text: name"></h3>
		<p>tha nan edit Credits: <span data-bind="text: credits"></span></p>
	</script>

    <script type="text/javascript">
        function MyViewModel() {
            this.buyer = {
                name: 'Franklin',
                credits: 250
            };
            this.seller = {
                name: 'Mario',
                credits: 5800
            };
        }
        ko.applyBindings(new MyViewModel());

        // Lưu ý 1: Kết xuất mẫu đã đặt tên
        // Lưu ý 3: Sử dụng "as" để đặt bí danh cho các mục "foreach"
    </script>
@endsection
