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

			<form class="shift-form" role="form" method="POST" action="{{ action('ShiftController@update', $shift) }}">
				{{ csrf_field() }}
                {{ method_field('PUT') }}

				<div class="shift-form__day-select">

					<div class="button-group hidden">

						<label for="date-choose">
							<input id="date-choose" class="datepicker-toggle" type="radio" name="date" value="{{ $shift->date->format('Y-m-d') }}" checked>
							<span class="button-group-item">Select a date</span>
						</label>

					</div>

					<input type="text" class="no-hide" name="datepicker" id="datepicker" value="{{ $shift->date->format('Y-m-d') }}">

				</div>

				<ul class="shift-form__fields">
					<li>
						<label for="start">Start</label>
						<input class="timepicker" type="text" value="{{ $shift->start->format('H:i') }}" id="start" name="start">
					</li>

					<li>
						<label for="end">End</label>
						<input class="timepicker" type="text" value="{{ $shift->end->format('H:i') }}" id="end" name="end">
					</li>
				</ul>

				<button type="submit">Submit</button>
			</form>

		</div>

	</section>
@endsection
