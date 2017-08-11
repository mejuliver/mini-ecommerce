<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
<div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav">
        <li @if($current_page==='Home') class="active" @endif >
            <a href="{{ url('/app/system/user/dashboard') }}"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
        </li>
        <li @if($current_page==='Orders') class="active" @endif >
            <a href="{{ url('/app/system/orders') }}"><i class="fa fa-shopping-bag" aria-hidden="true"></i> Orders</a>
        </li>
        <li @if($current_page==='Wishlists') class="active" @endif >
            <a href="{{ url('/app/system/wishlists') }}"><i class="fa fa-star" aria-hidden="true"></i> Wishtlists</a>
        </li>
    </ul>
</div>
