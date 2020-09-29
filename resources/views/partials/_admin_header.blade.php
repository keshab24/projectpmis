<header id="pro-admin-header">
    <?php
    if (session()->get('pro_fiscal_year') != null)
        $fiscalyear = \PMIS\Fiscalyear::whereId(session()->get('pro_fiscal_year'))->first()->fy;
    else
        $fiscalyear = "";
    ?>
    <a id="cd-logo" href="{!! route('home') !!}">Project Management / {{ Auth::user()->implementingOffice->name }}
        - {{ $fiscalyear }}</a>
    <nav id="cd-top-nav">
        <ul>
            <li>
                <ul class="proHeaderMenuAdmin">
                    <li class="dropDownParent">
                        <a href="#"><i class="fa fa-user"></i> {!! $user_info->name !!} <span class="caret"></span></a>
                        <ul style="width: 150px;">
                            <li>
                                <a href="@if(Auth::user()->access == 'Root Level' || Auth::user()->access == 'Top Level')
                                {{route('user.index')}}
                                @else
                                {{route('user.edit',Auth::user()->slug)}}
                                @endif "><span class="fa fa-certificate"></span>&nbsp; Profile</a>
                            </li>
                            <li>
                                <a href="{{route('user.password.change', $user_info->slug)}}"><span
                                            class="fa fa-lock"></span> Change Password</a>
                            </li>
                            <li>
                                <a href="{{route('logout')}}"><span class="fa fa-sign-out"></span> Log Out</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <a id="cd-menu-trigger" href="#0"><span class="cd-menu-text">Menu</span><span class="cd-menu-icon"></span></a>
</header>
<nav id="cd-lateral-nav">
    @if(Auth::user()->type_flag === 5) {{--engineers--}}
        <ul class="cd-navigation">
            <li class="item-has-children">
                <a href="#0"><i class="fa fa-file-text-o"></i> Manage </a>
                <ul class="sub-menu">
                    <li><a href="{{route('project.index')}}"><i class="fa fa-building-o"></i> Main Projects</a></li>
                </ul>
            </li> <!-- item-has-children -->
        </ul>
    @else
        <ul class="cd-navigation">
            <li class="item-has-children">
                <a href="#0"><i class="fa fa-file-text-o"></i> Manage </a>
                <ul class="sub-menu">
                    <li><a href="{{route('project.index')}}"><i class="fa fa-building-o"></i> Main Projects</a></li>
                    <li><a href="{{route('user.index')}}"><i class="fa fa-building-o"></i> Users</a></li>
                </ul>
            </li>
        </ul>

        <!-- only for sbcpco-->
        @if(Auth::User()->implementing_office_id==410)

            <li class="item-has-children">
                <a href="#0"><i class="fa fa-file-text-o"></i> Daily Activity Masters </a>
                <ul class="sub-menu">
                    <li><a href="{{route('manpower.index')}}"><i class="fa fa-user"></i> Manpower </a></li>
                    <li><a href="{{route('engineers.index')}}"><i class="fa fa-list-alt"></i> Human Resource </a></li>
                    <li><a href="{{route('equipment.index')}}"><i class="fa fa-wrench"></i> Equipment </a></li>
                    <li><a href="{{route('material.index')}}"><i class="fa fa-gavel"></i> Materials </a></li>
                    <li><a href="{{route('work-activity.index')}}"><i class="fa fa-th"></i> Work Activties </a></li>
                </ul>
            </li>
        @endif
    @endif

    
</nav>
