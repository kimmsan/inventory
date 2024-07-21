@extends('pdf')

@section('content-area')
    <h3>@lang('All product categories list')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('#')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Code')</th>
                    <th>@lang('Note')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productCategories as $key => $category)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>{{ $category['name'] }}</td>
                        <td>{{ config('config.proCatPrefix') . '-' . $category['code'] }} </td>
                        <td>{{ $category['note'] }} </td>
                        <td>
                            @if ($category['status'])
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
