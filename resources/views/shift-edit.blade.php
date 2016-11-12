@extends('layouts.app')

@section('content')
	<section class="shift-section-container">

		@if (session('status'))
			<div class="session-status">
				{{ session('status') }}
			</div>
		@endif

		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<header>
			<h1>Edit Shift</h1>
		</header>

		<div class="shift-form-container">

			<form class="shift-form" role="form" method="POST" action="{{ url('/', $shift['id']) }}">
				{{ csrf_field() }}

				<div class="shift-form__day-select">

					<h3>When did you work?</h3>

					<div class="button-group hidden">

						<label for="date-choose">
							<input id="date-choose" class="datepicker-toggle" type="radio" name="date" value="" checked>
							<span class="button-group-item">Select a date</span>
						</label>

					</div>

					<input type="text" class="no-hide" name="datepicker" id="datepicker" value="{{ $shift['date'] }}">

				</div>

				<ul class="shift-form__fields">
					<li>
						<label for="start-time">Start Time</label>
						<input class="timepicker" type="text" value="{{ $shift['startTime'] }}" id="start-time" name="start_time">
					</li>

					<li>
						<label for="end-time">End Time</label>
						<input class="timepicker" type="text" value="{{ $shift['endTime'] }}" id="end-time" name="end_time">
					</li>
				</ul>

				{{ method_field('PUT') }}

				<button type="submit">Submit</button>
			</form>

		</div>

	</section>
@endsection
