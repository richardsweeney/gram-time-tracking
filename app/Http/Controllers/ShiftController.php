<?php

namespace App\Http\Controllers;

use App\Role;
use App\Shift;
use App\User;
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
	 * Show the shifts dashboard.
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index(Request $request)
	{
		return view('shift');
	}


	/**
	 * Edit a shift.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function edit($id)
	{
		return view('shift-edit', [
			'shift' => $this->formatShiftForEdit($id),
		]);
	}


	/**
	 * Format the shift for editing.
	 *
	 * @param $id
	 *
	 * @return array|null
	 */
	protected function formatShiftForEdit($id)
	{
		$shift = Shift::find($id);
		if ( ! $shift ) {
			return null;
		}

		return [
			'id' => $id,
			'date' => $shift->start->toDateString(),
			'startTime' => $shift->start->format('H:i'),
			'endTime' => $shift->end->format('H:i'),
		];
	}


	/**
	 * Add a new shift.
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function add(Request $request)
	{
		$user = auth()->user();
		$shift = new Shift();

		$this->validate($request, [
			'date' => 'required|date_format:Y-m-d',
			'start_time' => 'required|date_format:H:i',
			'end_time' => 'required|date_format:H:i',
		]);

		$shift->start = $request->date . ' ' . $request->start_time . ':00';
		$shift->end = $request->date . ' ' . $request->end_time . ':00';
		$shift->user_id = $user->id;

		$shift->save();

		return redirect('/')->with('status', sprintf(
			'Shift Added. %sView all shifts%s',
			'<a href="' . url( '/shifts' ) . '">',
			'</a>'
		));
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
		$shift = Shift::find($id);

		$date = date('Y-m-d', strtotime($request->date));
		$shift->start = $date . ' ' . $request->start_time . ':00';
		$shift->end = $date . ' ' . $request->end_time . ':00';

		$shift->save();

		return redirect('/')->with('status', 'Shift Updated');
	}


	/**
	 * Delete a shift.
	 *
	 * @param $id
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id, Request $request)
	{
		Shift::destroy($id);

		return redirect('/shifts')->with('status', 'Shift Deleted');
	}


	public function getShifts(Request $request)
	{
		$user = auth()->user();
		$user_id = null;

		if ( $user->hasRole('admin') ) {
			if ( $request->has('user_id') ) {
				$user_id = (int) $request->user_id;
			}
		}
		else {
			$user_id = $user->id;
		}

		if ( null !== $user_id ) {
			return Shift::where('user_id', $user_id)->orderBy('start', 'DESC')->paginate(20);
		}
		else {
			return Shift::orderBy('start', 'DESC')->paginate(20);
		}
	}


	/**
	 * @return string
	 */
	public function shifts(Request $request)
	{
		return view('shifts-table', [
			'users'  => User::all(),
			'shifts' => $this->getShifts($request),
		]);
	}
}
