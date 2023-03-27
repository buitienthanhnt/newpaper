@section('body_top_tab')
    <div class="d-sm-flex align-items-center justify-content-between border-bottom">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab"
                    href={{ asset('assets/adminhtml/#overview') }} role="tab" aria-controls="overview"
                    aria-selected="true">Overview</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href={{ asset('assets/adminhtml/#audiences') }}
                    role="tab" aria-selected="false">Audiences</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href={{ asset('assets/adminhtml/#demographics') }}
                    role="tab" aria-selected="false">Demographics</a>
            </li>
            <li class="nav-item">
                <a class="nav-link border-0" id="more-tab" data-bs-toggle="tab" href={{ asset('assets/adminhtml/#more') }}
                    role="tab" aria-selected="false">More</a>
            </li>
        </ul>
        <div>
            <div class="btn-wrapper">
                <a href={{ asset('assets/adminhtml/#') }} class="btn btn-otline-dark align-items-center"><i
                        class="icon-share"></i>
                    Share</a>
                <a href={{ asset('assets/adminhtml/#') }} class="btn btn-otline-dark"><i class="icon-printer"></i> Print</a>
                <a href={{ asset('assets/adminhtml/#') }} class="btn btn-primary text-white me-0"><i
                        class="icon-download"></i>
                    Export</a>
            </div>
        </div>
    </div>
@endsection
