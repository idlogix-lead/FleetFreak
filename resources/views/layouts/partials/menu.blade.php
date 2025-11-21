@foreach ($menu as $row)
    @if ($row['type'] == 'link')
        {{-- @if($row['permission'])
            <li class="nav-item {{ active_class($row['active_link']) }}" position='{{ $row['link_position'] }}'>
                <a href="{{ $row['route'] }}" class="nav-link loader {{ active_class($row['active_link']) }}">
                    {!! $row['icon'] !!}
                    <span class="link-title menu-title">{{ $row['name'] }}</span>
                </a>
            </li>
        @endif --}}

         @if($row['permission'])
            <li class="nav-item" position='{{ $row['link_position']??0 }}'>
                <a href="{{ $row['route'] }}" class="nav-link loader ">
                    {!! $row['icon'] !!}
                    <span class="link-title menu-title">{{ $row['name'] }}</span>
                </a>
            </li>
        @endif
    @elseif($row['type'] == 'dropdown')
        {{-- <li class="nav-item {{ active_class($row['active_link']) }}">
            <a class="nav-link" data-toggle="collapse" href="#{{$row['id']}}" role="button"
                aria-expanded="{{ is_active_route($row['active_link']) }}" aria-controls="{{$row['id']}}">
                {!! $row['icon'] !!}
                <span class="link-title">{{$row['name']}}</span>
                <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse {{ show_class($row['active_link']) }}" id="{{$row['id']}}">
                <ul class="nav sub-menu">
                    @foreach ($row['list'] as $child_row)
                        @if($child_row['permission'])
                            <li class="nav-item">
                                <a href="{{ $child_row['route'] }}"
                                    class="nav-link loader {{ active_class($child_row['active_link']) }}">{{$child_row['name']}}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </li> --}}

        <li class="nav-item" position='{{ $row['link_position'] ?? 0 }}'>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    {!! $row['icon'] !!}
                </div>
                <div class="menu-title">{{$row['name']}} </div>
            </a>
            <ul class='active'>
                @foreach ($row['list'] as $child_row)
                    @if($child_row['permission'])
                        <li >
                            <a href="{{ $child_row['route'] }}" >
                                {{-- {!! $child_row['icon'] !!} --}}
                                <i class="bx bx-right-arrow-alt"></i>
                                {{$child_row['name']}}
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </li>
    @endif
@endforeach
