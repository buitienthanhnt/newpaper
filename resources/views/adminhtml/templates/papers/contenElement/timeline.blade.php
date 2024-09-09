<div data-type="p-timeline">
    <p style="display: none">paper timeline</p>
    <div class="data-content form-group">
        <div class="col-md-12 r">
            <label for="time_line_type" class="col-sm-2 col-form-label">TimeLine:</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <select id="time_line_type" class="form-control" name="time_line_type" multiple="multiple">
                        {!! $time_line_option !!}
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-12" style="align-content: center">
            <div class="form-group row" style="margin-bottom: 0px">
                <label for="url-alias" class="col-sm-2">Timeline:</label>
                <div class="cs-form col-sm-8">
                    <input name="time_line_value" id="timelineInput" autocomplete="false"/>
                </div>
            </div>
        </div>
    </div>
</div>
