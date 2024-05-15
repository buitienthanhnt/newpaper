<div class="row">
    <div class="col-sm-12">
        <div class="statistics-details d-flex align-items-center justify-content-between">
            <div>
                <p class="statistics-title">Page Views</p>
                <h3 class="rate-percentage">{{ $list_data['paper_view_count'] }}</h3>
                <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>+0.1%</span></p>
            </div>
            <div>
                <p class="statistics-title">Focus paper</p>
                <h3 class="rate-percentage">{{ $list_data['page_count'] }}</h3>
                <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span></p>
            </div>
            <div class="d-none d-md-block">
                <p class="statistics-title">User Sessions</p>
                <h3 class="rate-percentage">{{ $list_data['user_count'] }}</h3>
                <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span></p>
            </div>
            <div>
                <p class="statistics-title">In firebase</p>
                <h3 class="rate-percentage">{{ $list_data['paper_in_firebase']%$list_data['page_count'] }}%</h3>
                <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>-0.5%</span></p>
            </div>
            <div class="d-none d-md-block">
                <p class="statistics-title">Avg. Time on Site</p>
                <h3 class="rate-percentage">2m:35s</h3>
                <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
            </div>
            {{-- <div class="d-none d-md-block">
                <p class="statistics-title">Avg. Time on Site</p>
                <h3 class="rate-percentage">2m:35s</h3>
                <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
            </div> --}}
        </div>
    </div>
</div>
