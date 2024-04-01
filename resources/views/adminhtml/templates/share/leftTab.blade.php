<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href={{ asset('assets/adminhtml/index.html') }}>
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        @if ($formatRoutes)
            @foreach ($formatRoutes as $item)
                {{-- <li class="nav-item nav-category">UI Elements</li> --}}
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse"
                        href={{ asset('#' . str_replace('/', '-', $item['Number'])) }} aria-expanded="false"
                        aria-controls="{{ str_replace('/', '-', $item['Number']) }}">
                        <i class="menu-icon mdi mdi-floor-plan"></i>
                        <span class="menu-title">{{ $item['Name'] }}</span>
                        <i class="menu-arrow"></i>
                    </a>
                    @if (count($item['Children']))
                        <div class="collapse" id="{{ str_replace('/', '-', $item['Number']) }}">
                            <ul class="nav flex-column sub-menu">
                                @foreach ($item['Children'] as $chil)
                                    <li class="nav-item"> 
                                        <a class="nav-link"
                                            href={{ route($chil['as']) }}>{{ $chil['Name'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </li>
            @endforeach
        @endif
    </ul>
</nav>
