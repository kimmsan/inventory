@extends('pdf')

@section('content-area')
    <h3>@lang('All brands list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Brand Name')</th>
                    <th>@lang('Short Code')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Created At')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($brands as $key => $brand)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $brand['name'] }}</td>
                        <td>{{ $brand['code'] }}</td>
                        <td>
                            @if ($brand['status'])
                                @lang('Active')
                            @else
                                @lang('Inactive')
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($brand['created_at'])->format('d-M-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
