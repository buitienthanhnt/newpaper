@extends('frontend.templates.test.knockout.baseTemplate')

@section('content_page')
    <form>
        <p>
            Name:
            <b data-bind="visible: !editing(), text: name, click: edit">&nbsp;</b>
            <input data-bind="visible: editing, value: name, hasFocus: editing" />
        </p>
        <p><em>Click the name to edit it; click elsewhere to apply changes.</em></p>


    </form>

    <script type="text/javascript">
        function PersonViewModel(name) {
            // Data
            this.name = ko.observable(name);
            this.editing = ko.observable(false);

            // Behaviors
            this.edit = function() {
                this.editing(true)
            }
        }

        ko.applyBindings(new PersonViewModel("Bert Bertington"));
    </script>
@endsection



@section('content_page-ex1')
    <form>
        <input data-bind="hasFocus: isSelected" />
        <button data-bind="click: setIsSelected">Focus programmatically</button>
        <span data-bind="visible: isSelected">The textbox has focus</span>

    </form>

    <script type="text/javascript">
        var viewModel = {
            isSelected: ko.observable(false),
            setIsSelected: function() {
                this.isSelected(true)
            }
        };
        ko.applyBindings(viewModel);
    </script>
@endsection
