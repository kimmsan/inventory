@extends('pdf')

@section('content-area')
    <h3>@lang('Asset type list')</h3>
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
                @foreach ($assetTypes as $key => $assetType)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>{{ $assetType['name'] }}</td>
                        <td>{{ $assetType['note'] }}</td>
                        <td>
                            @if ($assetType['status'])
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
