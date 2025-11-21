<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $crumb)
            <li class="breadcrumb-item {{$crumb['active']?'breadcrumb-active':null}}"><a style="{{!$crumb['active'] ? 'color:#640D5F;' : ''  }}" href="{{$crumb['link']}}">{{$crumb['name']}}</a></li>
        @endforeach
    </ol>
</nav>
