@extends('pdf')

@section('content-area')
    <h3>@lang('All product sub categories list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Category')</th>
                    <th>@lang('Code')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Note')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productsubCategories as $key => $subCategory)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>
                            {{ $subCategory['category']['name'] }}<br />
                            [{{ config('config.proCatPrefix') . '-' . $subCategory['category']['code'] }}]
                        </td>
                        <td>{{ config('config.proSubCatPrefix') . '-' . $subCategory['code'] }}</td>
                        <td>{{ $subCategory['name'] }}</td>
                        <td>{{ $subCategory['note'] }}</td>
                        <td>
                            @if ($subCategory['status'])
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
