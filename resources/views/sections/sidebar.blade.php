<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark"> <!--begin::Sidebar Brand-->
    <div class="sidebar-brand"> <!--begin::Brand Link--> <a href="./index.html" class="brand-link">
            <!--begin::Brand Image--> <img src="../../dist/assets/img/AdminLTELogo.png" alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow"> <!--end::Brand Image--> <!--begin::Brand Text--> <span
                class="brand-text fw-light">AdminLTE </span> <!--end::Brand Text--> </a> <!--end::Brand Link--> </div>
    <!--end::Sidebar Brand--> <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2"> <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item menu-open"> <a href="{{ route('dashboard') }}" class="nav-link active">
                        <i class="nav-icon bi bi-speedometer"></i>

                        <p>
                            Dashboard
                        </p>

                    </a>
                </li>
                <li class="nav-item "> <a href="#" class="nav-link active"><i class="nav-icon bi bi-folder"></i>
                        <p>
                            Blog Management
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"> <a href="{{ route('category.index') }}" class="nav-link"> <i
                                    class="nav-icon bi bi-circle"></i>
                                <p>Blog Category</p>
                            </a> </li>

                        <li class="nav-item"> <a href="{{ route('blog.index') }}" class="nav-link"> <i
                                    class="nav-icon bi bi-circle"></i>
                                <p>Blog</p>
                            </a> </li>

                    </ul>
                </li>
                <li class="nav-item "> <a href="#" class="nav-link active"> <i class="nav-icon bi bi-person"></i>

                        <p>
                            User Management
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item"> <a href="{{ route('user.index') }}" class="nav-link"> <i
                                    class="nav-icon bi bi-circle"></i>
                                <p>User</p>
                            </a> </li>



                    </ul>
                </li>
                <li class="nav-item menu-open"> <a href="{{ route('Album.index') }}" class="nav-link active">
                        <i class="nav-icon bi bi-camera"></i>


                        <p>
                            Gallery
                        </p>

                    </a>
                </li>
                <li class="nav-item menu-open"> <a href="{{ route('cart.index') }}" class="nav-link active">
                        <i class="nav-icon bi bi-camera"></i>


                        <p>
                            cart
                        </p>

                    </a>
                </li>
                <li class="nav-item menu-open"> <a href="{{ route('cart.list') }}" class="nav-link active">
                        <i class="nav-icon bi bi-camera"></i>


                        <p>
                            list
                        </p>

                    </a>
                </li>
            </ul> <!--end::Sidebar Menu-->
        </nav>
    </div> <!--end::Sidebar Wrapper-->
</aside> <!--end::Sidebar-->
