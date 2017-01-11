@extends('layouts.app')

@section('content')

	<section class="shifts-table">
		@if (session('status'))
			<div class="session-status">
				{{ session('status') }}
			</div>
		@endif

		<header>
			<h1>All Shifts</h1>
		</header>

		<div class="shift-table-filters-container">

			<h3>Filters</h3>

			<form action="{{ url('/shifts') }}" method="GET" class="shift-table-filters">

                <div class="shift-table-filters__element user-select">
                    <label for="user-id">Select a user</label>
                    <select name="user_id" id="user-id" >
                        @if( auth()->user()->hasRole('admin') )
                            <option value="">-- Show all users --</option>
                        @endif
                        @foreach( $users as $user )
                            <option value="{{ $user->id }}"
                                @if ($userId == $user->id) selected="selected" @endif
                            >{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

				<div class="shift-table-filters__element date-picker-element">
					<label for="start-date">Date from</label>
						<input type="text" id="start-date" name="start_date" class="datepicker" placeholder="All shifts" value="{{ $startDate or '' }}">
				</div>

				<div class="shift-table-filters__element date-picker-element">
					<label for="end=date">Date to</label>
						<input type="text" id="end=date" name="end_date" class="datepicker" placeholder="All shifts" value="{{ $endDate or '' }}">
				</div>

				<div class="shift-table-filters__element button-element">
					<label for="submit">&nbsp;</label>
					<button id="submit" type="submit">Filter</button>
				</div>

				<div class="shift-table-filters__element button-element">
					<label for="submit">&nbsp;</label>
					<button id="report" name="show_report" value="1" class="alt" type="submit">Create Report</button>
				</div>
			</form>
		</div>

		<div class="shifts-table-container">

			<table class="tables">
				<thead>
				<tr>
					<th>Employee</th>
					<th>Date</th>
					<th>Start</th>
					<th>End</th>
					<th>Actions</th>
				</tr>
				</thead>
				<tbody>
				@forelse( $shifts as $shift )
					<tr>
						<td>{{ $shift->user->name }}</td>
						<td>{{ $shift->date->format('D, jS M') }}</td>
						<td>{{ $shift->start->format('H:i') }}</td>
						<td>{{ $shift->end->format('H:i') }}</td>
						<td class="actions">
							<form class="delete-shift-form" action="{{ action('ShiftController@destroy', $shift) }}" method="POST">
								{{ csrf_field() }}
								{{ method_field('DELETE') }}
								<button class="alt" type="submit">Delete</button>
							</form>
							<a class="button" href="{{ action('ShiftController@edit', $shift) }}">Edit</a>
						</td>
					</tr>
				@empty
					<tr>
						<td>No shifts found</td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				@endforelse
				</tbody>
			</table>

			<div class="pagination-container">
				{{ $shifts->appends(['user_id' => $userId, 'start_date' => $startDate, 'end_date' => $endDate])->links() }}
			</div>

		</div>

	</section>

@endsection
