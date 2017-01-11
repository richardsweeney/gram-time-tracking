<?php

namespace App\Http\Controllers;

use App\Role;
use App\Shift;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}


	/**
	 * @param Request $request
	 *
	 * @return string
	 */
	public function index(Request $request)
	{
		$startDate = $request->start_date;
		$endDate = $request->end_date;

		if ($request->show_report) {
			return view('shifts-report', [
				'reports' => $this->calculateReports($request),
				'startDate' => ! empty( $startDate ) ? date('D, jS M Y', strtotime( $startDate ) ) : 'All',
				'endDate' => ! empty( $endDate ) ? date('D, jS M Y', strtotime( $endDate ) ) : date('D, jS M Y'),
			]);
		}

		$user = auth()->user();
		if ($user->hasRole('admin')) {
			$users = User::all();
			if ($request->has('user_id')) {
				$userId = (int) $request->user_id;
			}
			else {
				$userId = null;
			}
		}
		else {
			$users = new Collection([$user]);
			$userId = $user->id;
		}

		return view('shifts-table', [
			'users' => $users,
			'shifts' => $this->getPaginatedShifts($request),
			'startDate' => $startDate,
			'endDate' => $endDate,
			'userId' => $userId,
		]);
	}


	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create(Request $request)
	{
		return view('shift');
	}


	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(Request $request)
	{
		$this->validate($request, $this->getValidationParams());

		$start = new Carbon($request->start);
		$end = new Carbon($request->end);
		$date = new Carbon($request->date);

		$user = auth()->user();

		Shift::create([
			'start' => $start->toTimeString(),
			'end' => $end->toTimeString(),
			'date' => $date,
			'user_id' => $user->id,
		]);

		return redirect('/shifts/create')->with('status', sprintf(
			'Shift Added. %sView all shifts%s',
			'<a href="' . url( '/shifts' ) . '">',
			'</a>'
		));
	}


	/**
	 * @param $id
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function show($id, Request $request)
	{
		return Shift::findOrFail($id);
	}


	/**
	 * Update a shift.
	 *
	 * @param $id
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id, Request $request)
	{
		$shift = Shift::findOrFail($id);

		$this->validate($request, $this->getValidationParams());

		$start = new Carbon($request->start);
		$end = new Carbon($request->end);
		$date = new Carbon($request->date);

		$shift->start = $start->toTimeString();
		$shift->end = $end->toTimeString();
		$shift->date = $date;

		$shift->save();

		return redirect('/shifts')->with('status', 'Shift Updated');
	}


	/**
	 * Edit a shift.
	 *
	 * @param int $id
	 * @param Request $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit($id, Request $request)
	{
		return view('shift-edit', [
			'shift' => Shift::findOrFail($id),
		]);
	}


	/**
	 * Delete a shift.
	 *
	 * @param $id
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy($id, Request $request)
	{
		Shift::destroy($id);

		return redirect('/shifts')->with('status', 'Shift Deleted');
	}


	/**
	 * @return array
	 */
	protected function getValidationParams()
	{
		return [
			'date' => 'required|date_format:Y-m-d',
			'start' => 'required|date_format:H:i',
			'end' => 'required|date_format:H:i',
		];
	}


	/**
	 * Get paginated results within certain paramaters.
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function getPaginatedShifts(Request $request)
	{
		return Shift::where(function($query) use ($request) {
			if ($request->has('start_date') && ! empty($request->start_date)) {

				$start_date = new Carbon($request->start_date);
				$query->where('date', '>=', $start_date->toDateTimeString());
			}
			if ($request->has('end_date') && ! empty($request->end_date)) {

				$end_date = new Carbon($request->end_date);
				$query->where('date', '<=', $end_date->toDateTimeString());
			}

			$user = auth()->user();
			$user_id = null;

			if ($user->hasRole('admin')) {
				if ($request->has('user_id')) {
					$user_id = (int) $request->user_id;
				}
			}
			else {
				$user_id = $user->id;
			}

			if (null !== $user_id) {
				$query->where('user_id', $user_id);
			}
		})->orderBy('date', 'DESC')->paginate(20);
	}


	/**
	 * Get all shifts with certain query paramaters.
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function getAllShifts(Request $request)
	{
		return Shift::where(function($query) use ($request) {
			if ($request->has('start_date') && ! empty($request->start_date)) {

				$start_date = new Carbon($request->start_date);
				$query->where('date', '>=', $start_date->toDateTimeString());
			}

			if ($request->has('end_date') && ! empty($request->end_date)) {

				$end_date = new Carbon($request->end_date);
				$query->where('date', '<=', $end_date->toDateTimeString());
			}

			if ($request->has('user_id') && ! empty($request->user_id)) {
				$query->where('user_id', $request->user_id);
			}

		})->orderBy('date', 'DESC')->get();
	}


	/**
	 * Calculate the reports for a user or users.
	 *
	 * @param Request $request
	 *
	 * @return array
	 */
	public function calculateReports(Request $request) {
		$shifts = $this->getAllShifts($request);

		$allShifts = [];
		foreach ($shifts as $shift) {
			if (!array_key_exists($shift->user_id, $allShifts)) {
				$allShifts[ $shift->user_id ] = [
					'user' => User::findOrFail($shift->user_id),
					'time' => [
						'weekday'       => 0,
						'weekday_extra' => 0,
						'weekend'       => 0,
					]
				];
			}

			$allShifts[$shift->user_id]['time'] = $this->calculateShiftHours($shift, $allShifts[$shift->user_id]['time']);

		}

		return $allShifts;
	}


	/**
	 * @param Shift $shift
	 * @param array $totalShifTimes
	 *
	 * @return array
	 */
	protected function calculateShiftHours(Shift $shift, array $totalShifTimes) {
		if ($shift->date->isWeekend()) {
			$totalShifTimes['weekend'] += $shift->start->diffInHours($shift->end);

		} else {
			$weekdayRegular = new Carbon();
			$weekdayRegular->setTime(18, 00);

			$totalShifTimes['weekday'] += $shift->start->diffInHours($weekdayRegular);
			$totalShifTimes['weekday_extra'] += $shift->end->diffInHours($weekdayRegular);
		}

		return $totalShifTimes;
	}

}
