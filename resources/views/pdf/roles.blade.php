@extends('pdf')

@section('content-area')
    <h3>@lang('All roles list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Role Name')</th>
                    <th>@lang('Created At')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $key => $role)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $role['name'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($role['created_at'])->format('d-M-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
