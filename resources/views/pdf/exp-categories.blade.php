@extends('pdf')

@section('content-area')
    <h3>@lang('All expense categories')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Code')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Created Date')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $key => $category)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $category['name'] }}</td>
                        <td>{{ config('config.expCatPrefix') . '-' . $category['code'] }}</td>
                        <td>
                            @if ($category['status'])
                                @lang('Active')
                            @else
                                @lang('Inactive')
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($category['created_at'])->format('d-M-Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
