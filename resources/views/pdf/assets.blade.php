@extends('pdf')

@section('content-area')
    <h3>@lang('All assets list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Asset Name')</th>
                    <th>@lang('Asset Type')</th>
                    <th>@lang('Asset Cost')</th>
                    <th>@lang('Current Value')</th>
                    <th>@lang('Created At')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($assets as $key => $asset)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>{{ $asset['name'] }}</td>
                        <td>{{ $asset['asset_type']['name'] }}</td>
                        <td>
                            @currency($asset['asset_cost'])
                        </td>
                        <td>
                            @currency($asset['calculated_value'])
                        </td>
                        <td>{{ \Carbon\Carbon::parse($asset['date'])->format('d-M-Y') }}</td>
                        <td>
                            @if ($asset['status'])
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
