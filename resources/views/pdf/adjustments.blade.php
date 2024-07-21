@extends('pdf')

@section('content-area')
    <h3>@lang('All inventory adjustments')</h3>
    <div class="table-responsive">
        <table class="table-listing table table-bordered table-striped table-sm">
            <thead class="thead-light">
                <tr>
                    <th>@lang('S.No.')</th>
                    <th>@lang('Reason')</th>
                    <th>@lang('Date')</th>
                    <th>@lang('Status')</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($adjustments as $key => $adjustment)
                    <tr>
                        <td> {{ ++$key }} </td>
                        <td>
                            {{ $adjustment->reason }}
                        </td>
                        <td>{{ $adjustment->date }}</td>
                        <td>
                            @if ($adjustment->status)
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
