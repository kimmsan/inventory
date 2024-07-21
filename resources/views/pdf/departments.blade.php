@extends('pdf')

@section('content-area')
    <h3>@lang('All departments list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Note')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($departments as $key => $department)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>{{ $department['name'] }}</td>
                        <td>{{ $department['note'] }}</td>
                        <td>
                            @if ($department['status'])
                                @lang('Active')
                            @else
                                @lang('Inactive')
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
