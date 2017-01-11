@extends('layouts.app')

@section('content')
	<section class="shift-section-container">

		@if (session('status'))
			<div class="session-status">
				{!! session('status') !!}
			</div>
		@endif

		@if (count($errors) > 0)
			<div class="form-errors">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<header>
			<h1>Register Shift</h1>
		</header>

		<div class="shift-form-container">

			<form class="shift-form" role="form" method="POST" action="{{ url('/shifts') }}">
				{{ csrf_field() }}

				<div class="shift-form__day-select">

					<h3>When did you work?</h3>

					<div class="button-group">

						<label for="date-today">
							<input id="date-today" type="radio" name="date" value="{{ date('Y-m-d' ) }}" checked>
							<span class="button-group-item">Today</span>
						</label>

						<label for="date-yesterday">
							<input id="date-yesterday" type="radio" name="date" value="{{ date('Y-m-d', strtotime( '-1 day' ) ) }}">
							<span class="button-group-item">Yesterday</span>
						</label>

						<label for="date-choose">
							<input id="date-choose" class="datepicker-toggle" type="radio" name="date" value="">
							<span class="button-group-item">Select a date</span>
						</label>

					</div>

					<div class="datepicker-hidden" id="datepicker-hidden">
						<input type="text" name="datepicker" id="datepicker" value="" placeholder="{{ date('Y-m-d') }}">
					</div>

				</div>

				<ul class="shift-form__fields">
					<li>
						<label for="start">Start</label>
						<input class="timepicker" type="text" value="{{ old('start') }}" id="start" name="start">
					</li>

					<li>
						<label for="end">End</label>
						<input class="timepicker" type="text" value="{{ old('end') }}" id="end" name="end">
					</li>
				</ul>

				<button type="submit">Submit</button>
			</form>

		</div>


	</section>
@endsection
