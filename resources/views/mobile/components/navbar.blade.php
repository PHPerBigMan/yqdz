<div class="m-navbar">
    @php
        $active = isset($active) ?  $active : 'home';
    @endphp

    <a href="/" class="nav-item @if($active == 'home') active @endif">
        <i class="p-icon icon-home"></i>
    </a>


    <a href="/square" class="nav-item @if($active == 'square') active @endif">
        <i class="p-icon icon-square"></i>
    </a>

    <a href="/enterprise" class="nav-item @if($active == 'enterprise') active @endif">
        <i class="p-icon icon-enterprise"></i>
    </a>

    <a href="/user" class="nav-item @if($active == 'user') active @endif">
        <i class="p-icon icon-user"></i>
    </a>
</div>