@extends('layouts.app')

@section('content')

	<section class="shifts-report">

		<header>
			<h1>Shift report</h1>
		</header>

		<h3>Period: {{ $startDate }} - {{ $endDate }}</h3>

		<div class="shifts-report-container">

			<table class="tables">
				<thead>
				<tr>
					<th>Employee</th>
					<th>Regular weekday hours</th>
                    <th>OB weekday hours</th>
                    <th>OB weekend hours</th>
				</tr>
				</thead>
				<tbody>
				@foreach($reports as $report)
					<tr>
						<td>{{ $report['user']->name }}</td>
						<td><strong>{{ $report['time']['weekday'] }}h</strong></td>
						<td><strong>{{ $report['time']['weekday_extra'] }}h</strong></td>
						<td><strong>{{ $report['time']['weekend'] }}h</strong></td>
					</tr>
				@endforeach
				</tbody>
			</table>

		</div>

	</section>

@endsection
