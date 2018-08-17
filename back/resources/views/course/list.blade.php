@extends('layouts.base')

@section('title')
    @lang('messages.list.courses')
@endsection

@section('content')
    <div>
        <span class="float-left">
            <h1>@lang('messages.list.courses')</h1>
        </span>

        <span class="float-right">
            <a href="#" class="btn btn-success" role="button">
                @lang('actions.create.course')
            </a>
        </span>
    </div>

    <table class="table table-striped">
        <thead>
        <tr class="row">
            <th class="col-3">@lang('fields.name')</th>
            <th class="col-3">@lang('fields.description')</th>
            <th class="col-2">@lang('fields.teacher')</th>
            <th class="col-1 text-center">@lang('fields.enabled')</th>
            <th class="col-3 text-right"><span class="text-center">@lang('fields.actions')</span></th>
        </tr>
        </thead>

        <tbody>
             @foreach($courses as $course)
                <tr class="row">
                    <td class="col-3">{{ $course->name }}</td>
                    <td class="col-3">{{ $course->description }}</td>
                    <td class="col-2">{{ $course->teacher }}</td>
                    <td class="col-1 text-center">
                        @if($course->enabled)
                            <i data-feather="check-square"></i>
                        @else
                            <i/>
                        @endif
                    </td>
                    <td class="col-3 text-right">
                        <a class="btn btn-primary btn-sm" href="#" role="button">
                            @lang('actions.edit.title')
                        </a>
                        <a class="btn btn-danger btn-sm" href="#" role="button">
                            @lang('actions.delete.title')
                        </a>
                        <div class="btn-group" role="group">
                            <a class="btn btn-info btn-sm" href="#" role="button">
                                @lang('actions.view.title')
                            </a>

                            <button type="button" class="btn btn-info btn-sm active dropdown-toggle"
                                    data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">

                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">
                                    @lang('messages.title.folders')
                                </a>
                                <a class="dropdown-item" href="#">
                                    @lang('messages.title.sessions')
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
             @endforeach
        </tbody>
    </table>

    @empty($courses)
        @lang('messages.none.courses')
    @endempty
@endsection