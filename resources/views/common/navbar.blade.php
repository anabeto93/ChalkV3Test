<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
{{--        @auth('admin')--}}
            <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('course.list') }}">
                            @lang('messages.title.courses')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.list') }}">
                            @lang('messages.title.users')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cohort.list') }}">
                            @lang('messages.title.cohorts')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('quiz.list') }}">
                            @lang('messages.title.quizzes')
                        </a>
                    </li>
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            Admin
{{--                            {{ Auth::admin()->name }} <span class="caret"></span>--}}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <i width="16" height="16" data-feather="log-out"></i>
                                @lang('actions.logout')
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            {{--@endauth--}}
        </div>
    </div>
</nav>