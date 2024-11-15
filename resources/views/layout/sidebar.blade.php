<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="{{ route('dashboard') }}" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary">COSMETICS</h3>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="{{ asset('img/user.jpg') }}" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0">{{ strtoupper(Auth::user()->name ?? null) }}</h6>
                <span>{{ ucfirst(Auth::user()->role ?? null) }}</span>
            </div>
        </div>
        <div class="navbar-nav w-100">
            <a href="{{ route('dashboard') }}" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                <a href="{{ route('products.index') }}" class="nav-item nav-link">
                    <i class="fa fa-box"></i>
                    <span>Products</span>
                </a>
                <a class="nav-item nav-link" href="{{ route('sales.index') }}">
                    <i class="fa fa-shopping-cart"></i> Sales
                </a>            
                <a class="nav-link" href="{{ route('reports.index') }}">
                    <i class="fa fa-file-alt"></i> Reports
                </a>
         </div>
    </nav>
</div>
