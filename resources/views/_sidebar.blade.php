<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
<div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav">
        <li @if($current_page==='Home') class="active" @endif >
            <a href="{{ url('/app/system/dashboard') }}"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
        </li>
        @if($user_info->status!=='pending')
        @if(count(array_intersect($perms,['can_sell']))>0)
        <li @if($current_page==='Sale Items') class="active" @endif >
             <a href="javascript:;" data-toggle="collapse" data-target="#sale-menu"><i class="fa fa-shopping-basket" aria-hidden="true"></i> Items <i class="fa fa-fw fa-caret-down"></i></a>
             <ul id="sale-menu" class="collapse">
                <li>
                    <a href="{{ url('/app/system/sale-items') }}">Sale Items</a>
                </li>
                <li>
                    <a href="{{ url('/app/system/sale-items/item/create') }}">Create Item</a>
                </li>
                <li>
                    <a href="{{ url('/app/system/sale-items/tags') }}">Tags</a>
                </li>
            </ul>
        </li>
        @endif
        @endif
        @if(count(array_intersect($perms,['can_buy']))>0)
        <li @if($current_page==='Orders') class="active" @endif >
            <a href="{{ url('/app/system/orders') }}"><i class="fa fa-shopping-bag" aria-hidden="true"></i> Orders</a>
        </li>
        <li @if($current_page==='Wishlists') class="active" @endif >
            <a href="{{ url('/app/system/wishlists') }}"><i class="fa fa-star" aria-hidden="true"></i> Wishtlists</a>
        </li>
        @endif
        @if(count(array_intersect($roles,['admin']))>0)
        <li @if($current_page==='Admin') class="active" @endif >
             <a href="javascript:;" data-toggle="collapse" data-target="#admin-menu"><i class="fa fa-lock" aria-hidden="true"></i> Admin <i class="fa fa-fw fa-caret-down"></i></a>
             <ul id="admin-menu" class="collapse">
                <li >
                    <a href="{{ url('/app/system/admin/users') }}">Users</a>
                </li>
                <li >
                    <a href="{{ url('/app/system/admin/sale-items') }}">Sale Items</a>
                </li>
                {{--<li >
                    <a href="{{ url('/app/system/admin/menu') }}">Menu</a>
                </li>--}}
                <li >
                    <a href="{{ url('/app/system/admin/categories') }}">Categories</a>
                </li>
            </ul>
        </li>
        @endif
    </ul>
</div>
