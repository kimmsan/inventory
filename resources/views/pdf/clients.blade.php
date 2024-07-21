@extends('pdf')

@section('content-area')
    <h3>@lang('All client list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Client ID')</th>
                    <th>@lang('Phone')</th>
                    <th>@lang('Email')</th>
                    <th>@lang('Company Name')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $key => $client)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>{{ $client['name'] }}</td>
                        <td>{{ config('config.clientPrefix') . '-' . $client['client_id'] }}</td>
                        <td>{{ $client['phone'] }}</td>
                        <td>{{ $client['email'] }}</td>
                        <td>{{ $client['company_name'] }}</td>
                        <td>
                            @if ($client['status'])
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
