<aside class="sidebar sidebar-left">
    <div class="sidebar-content">
        <div class="aside-toolbar">
            <ul class="site-logo">
                <li>
                <!-- START LOGO -->
                    <a href="{{ url('/')}}">
                        <span class="brand-text" style="font-family:courier;">Pelindung Hewan</span>
                    </a>
                <!-- END LOGO -->
                </li>
            </ul>
        </div>
        <nav class="main-menu">
            <ul class="nav metismenu">
                <li class="sidebar-header"><span>NAVIGATION</span></li>
                <li class="nav-dropdown active">
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="icon dripicons-meter"></i><span>Dashboard</span></a>
                    <ul class="collapse in nav-sub" aria-expanded="true">
                    <li><a href="{{ route('penban.index')}}"><span>Penerima Bantuan</span></a></li>
                    <li><a href="{{ route('konmah.index')}}"><span>Kondisi Rumah</span></a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>