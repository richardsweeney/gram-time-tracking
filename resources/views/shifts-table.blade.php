@extends('layouts.app')

@section('content')

	<section class="shifts-table">
		@if (session('status'))
			<div class="session-status">
				{{ session('status') }}
			</div>
		@endif

		<header>
			<h1>Shifts</h1>
		</header>

		@if( auth()->user()->hasRole('admin') )
			<div class="shift-table-filters-container">
				<form action="{{ url('/shift') }}" method="GET">
					<select name="user_id">
						<option value="">-- Show all users --</option>
						@foreach( $users as $user )
							<option value="{{ $user->id }}">{{ $user->name }}</option>
						@endforeach
					</select>
					<button type="submit">Go</button>
				</form>
			</div>
		@endif

		<div class="shifts-table-container">

			<table class="tables">
				<thead>
				<tr>
					<th>Employee</th>
					<th>Start</th>
					<th>End</th>
					<th>Actions</th>
				</tr>
				</thead>
				<tfoot>
				<tr>
					<td>Employee</td>
					<td>Start</td>
					<td>End</td>
					<td>Actions</td>
				</tr>
				</tfoot>
				<tbody>
				@foreach( $shifts as $shift )
					<tr>
						<td>{{ $shift->user->name }}</td>
						<td>{{ $shift->start->format('Y-m-d H:i') }}</td>
						<td>{{ $shift->end->format('Y-m-d H:i') }}</td>
						<td>
							<form action="{{ url('/', $shift) }}" method="POST">
								{{ csrf_field() }}
								{{ method_field('DELETE') }}
								<button class="delete" type="submit">Delete</button>
							</form>
							<a class="button" href="{{ url('/', $shift) }}">Edit</a>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>

			{{ $shifts->links() }}

		</div>

	</section>

@endsection
