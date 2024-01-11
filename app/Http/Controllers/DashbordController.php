<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\ApplyLeave;
use App\Models\LeaveAssign;
use App\Models\LeaveType;
use App\Models\Employee;
use App\Models\Leavebank;

use Illuminate\Support\Facades\Auth;

class DashbordController extends Controller
{
    public function Dashbord_alldata(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'sometimes|required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        $userId = $request->userid;
            if ($userId) {

                    $LeveDataId = LeaveAssign::join('leave_type', 'leave_assign.leave_type', '=', 'leave_type.id')
                                ->leftJoin('apply_leave', 'leave_assign.id', '=', 'apply_leave.assign_id')
                                ->groupBy('leave_type.id', 'leave_type.type', 'leave_type.color')
                                ->where('leave_assign.employeeid', $userId)
                                ->select(
                                    'leave_assign.leave_count as count',
                                    'leave_type.color',
                                    'leave_assign.id as lid',
                                    'leave_type.id as value',
                                    'leave_type.id as btid',
                                    'leave_type.type AS label',
                                    DB::raw('SUM((apply_leave.status = "Approved") * apply_leave.count) as lcount')
                                )->get();

                                $result= Employee::select(
                                    'employee.id as emp_id',
                                    'employee.employeeid as emp_employeeid',
                                    'employee.first_name as emp_first_name',
                                    'employee.last_name as emp_last_name',
                                    'employee.email as emp_email',
                                    'employee.location as emp_location',
                                    'employee.department as emp_department',
                                    'employee.type as emp_type',
                                    'employee.is_active as emp_is_active',
                                    'employee.is_deleted as emp_is_deleted',
                                    'employee.created_at as emp_created_at',
                                    'approver.id as approver_id',
                                    'approver.employeeid as approver_employeeid',
                                    'approver.first_name as approver_first_name',
                                    'approver.last_name as approver_last_name',
                                    'approver.email as approver_email',
                                    'approver.location as approver_location',
                                    'approver.department as approver_department',
                                    'approver.type as approver_type',
                                    'approver.is_active as approver_is_active',
                                    'approver.is_deleted as approver_is_deleted',
                                    'approver.created_at as approver_created_at',
                                    'leave_bank_emp.leave_bank as total'
                                )
                                ->leftJoin('leave_bank AS leave_bank_emp', 'employee.employeeid', '=', 'leave_bank_emp.employeeid')
                                ->leftJoin('employee AS approver', 'leave_bank_emp.approverid', '=', 'approver.employeeid')
                                ->where('employee.employeeid', '=', $userId)
                                ->first();

                $responseDataId = [
                    'data' => $LeveDataId->map(function ($leaveID)  {
                        return [
                            'count' => $leaveID->count,
                            'color' => $leaveID->color,
                            'lid' => $leaveID->lid,
                            'lcount' =>(float) $leaveID->lcount,
                            'value' => $leaveID->value,
                            'btid' => $leaveID->btid,
                            'label' => $leaveID->label,
                        ];
                    }),
                    'chart' => $LeveDataId->map(function ($leaveID) {
                        return [
                            'value' => (float)$leaveID->lcount,
                            'count' => $leaveID->count,
                            'btid' => $leaveID->btid,
                            'lid' => $leaveID->lid,
                            'lcount' => (float)$leaveID->lcount,
                            'color' => $leaveID->color,
                            'title' => $leaveID->label,
                        ];
                    }),
                    'message' => 'Empty Form',
                    'emp' =>[
                        'total' => $result->total,
                    'emp' => [
                        'id' => $result->emp_id,
                        'employeeid' => $result->emp_employeeid,
                        'first_name' => $result->emp_first_name,
                        'last_name' => $result->emp_last_name,
                        'email' => $result->emp_email,
                        'location' => $result->emp_location,
                        'department' => $result->emp_department,
                        'type' => $result->emp_type,
                        'is_active' => $result->emp_is_active,
                        'is_deleted' => $result->emp_is_deleted,
                        'created_at' => $result->emp_created_at,
                    ],
                    'approver' => [
                        'id' => $result->approver_id,
                        'employeeid' => $result->approver_employeeid,
                        'first_name' => $result->approver_first_name,
                        'last_name' => $result->approver_last_name,
                        'email' => $result->approver_email,
                        'location' => $result->approver_location,
                        'department' => $result->approver_department,
                        'type' => $result->approver_type,
                        'is_active' => $result->approver_is_active,
                        'is_deleted' => $result->approver_is_deleted,
                        'created_at' => $result->approver_created_at,
                    ],
                    ],

                ];

                return response()->json($responseDataId);
            }


        $leaveData = LeaveType::join('leave_assign', 'leave_type.id', '=', 'leave_assign.leave_type')
            ->groupBy('leave_type.id', 'leave_type.type', 'leave_type.color')
            ->select(
                'leave_type.id as btid',
                'leave_type.type as label',
                'leave_type.color',
                DB::raw('SUM(leave_assign.leave_count) as count')
            )->get();

        $leaveCount = LeaveAssign::join('leave_type', 'leave_assign.leave_type', '=', 'leave_type.id')
            ->leftJoin('apply_leave', 'leave_assign.id', '=', 'apply_leave.assign_id')
            ->groupBy('leave_type.id', 'leave_type.type', 'leave_type.color')
            ->select(
                'leave_type.id',
                'leave_type.type',
                'leave_type.color',
                DB::raw('SUM((apply_leave.status = "Approved") * apply_leave.count) as lcount')
            )->get();

        $responseData = [
            'data' => $leaveData->map(function ($leave) use ($leaveCount) {
                $lcount = $leaveCount->where('id', $leave->btid)->value('lcount') ?? 0;

                return [
                    'count' => $leave->count,
                    'color' => $leave->color,
                    'btid' =>  $leave->btid,
                    'lcount' => (float) $lcount,
                    'value' => (float) $lcount,
                    'label' => $leave->label,
                ];
            }),
            'chart' => $leaveData->map(function ($leave) use ($leaveCount) {
                $lcount = $leaveCount->where('id', $leave->btid)->value('lcount') ?? 0;

                return [
                    'value' => (float) $lcount,
                    'count' => $leave->count,
                    'btid' =>  $leave->btid,
                    'lcount' => (float) $lcount,
                    'color' => $leave->color,
                    'title' => $leave->label,
                ];
            }),
            'message' => 'Empty Form',

            'emp' => [],
        ];

        return response()->json($responseData);
    }
}




            // $leaveData = LeaveAssign::join('leave_type', 'leave_assign.leave_type', '=', 'leave_type.id')
            //                     ->leftJoin('apply_leave', 'leave_assign.id', '=', 'apply_leave.assign_id')
            //                     ->groupBy('leave_type.id', 'leave_type.type', 'leave_type.color')
            //                     //->where('leave_assign.employeeid', $userId)
            //                     ->select(
            //                         'leave_type.id as btid',
            //                         'leave_type.type as label',
            //                         'leave_type.color',
            //                         DB::raw('SUM(leave_assign.leave_count) as count'),
            //                         DB::raw('SUM((apply_leave.status = "Approved") * apply_leave.count) as lcount')
            //                     )->get();
