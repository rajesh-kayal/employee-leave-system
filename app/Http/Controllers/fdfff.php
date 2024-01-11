<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use App\Models\ApplyLeave;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\Location;
use App\Models\LeaveAssign;

class LeaveStatusController extends Controller
{
    public function Leaves_alldata(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'empid' => 'required_without_all:employee_id',
            'employee_id' => 'required_without_all:empid',
            'com' => 'string',
            'type' => 'string',
            'perpage' => 'integer',
            'page' => 'integer',
            // 'search' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }
        $employee_id = $request->employee_id;
        $empId = $request->empid;
        $com = $request->com;
        $type = $request->type;
        $perpage = $request->perpage ; 
        $page = $request->page ;
        $search = $request->search;
        $firstName = $request->first_name;
        $pageOffset = ($perpage * $page) - $perpage;

       /* $query = ApplyLeave::select(
            'apply_leave.*',
            'employee.*',
            'leave_type.*',
            'leave_assign.leave_count',
            'location.locationName'
        )
            ->join('employee', 'apply_leave.employeeid', '=', 'employee.employeeid')
            ->Join('leave_assign', 'apply_leave.assign_id', '=', 'leave_assign.id')
            ->Join('leave_type', 'leave_assign.leave_type', '=', 'leave_type.id')
            ->Join('location', 'employee.location', '=', 'location.location_id')
            ->where('apply_leave.employeeid', $empId)
            ->where(function ($q) use ($search) {
                $q->where('employee.first_name', 'like', "%$search%")
                    ->orWhere('employee.last_name', 'like', "%$search%")
                    ->orWhere('leave_type.type', 'like', "%$search%")
                    ->orWhere('location.locationName', 'like', "%$search%")
                    ->orWhere('employee.employeeid', 'like', "%$search%");
            })
            ->orderBy('apply_leave.created_at', 'desc')
            ->paginate($perpage, ['*'], 'page', $page);*/
            //FETCH THOSE ID WHCH STATUS IS APROVE 
        // if ($query->isEmpty()) {
            if ($empId === '0000') {
            $employeeData = ApplyLeave::select(
                            'apply_leave.*',
                            'employee.*',
                            'leave_type.*',
                            'leave_assign.leave_count',
                            'location.locationName'
                        )
                            ->join('employee', 'apply_leave.employeeid', '=', 'employee.employeeid')
                            ->join('leave_assign', 'apply_leave.assign_id', '=', 'leave_assign.id')
                            ->join('leave_type', 'leave_assign.leave_type', '=', 'leave_type.id')
                            ->join('location', 'employee.location', '=', 'location.location_id')
                            ->get();

            $paginationUserData = [
                'totalCount' => $employeeData->count(),
                'totalPage' => 1, 
            ];
            return response()->json([
                'id' => $empId,
                'message'=> 'Successfull Found',
                'status' => 'success',
                'response' => [
                'apply_leave' => $employeeData->toArray(),
            ],

                'pagination' => $paginationUserData,
            ]);

        }
        if ($employee_id === '0000' && $firstName === 'admin') { 
            $adminData = Employee::where('employeeid', '0000')->first();

            if ($adminData) {
                return response()->json([
                    'id' => $employee_id,
                    'message' => 'Successfully Found',
                    'status' => 'success',
                    'response' => $adminData->toArray(),
                ]);
            } else {
                return response()->json([
                    'id' => $empId,
                    'message' => 'Admin data not found',
                    'status' => 'unsuccess',
                    'response' => [],
                ]);
            }
        }

            if ($empId === '2130') {
            $employeeData1 = ApplyLeave::select(
                                'apply_leave.*',
                                'employee.*',
                                'leave_type.*',
                                'leave_assign.leave_count',
                                'location.locationName'
                            )->where('apply_leave.status', 'approve')
                                ->join('employee', 'apply_leave.employeeid', '=', 'employee.employeeid')
                                ->join('leave_assign', 'apply_leave.assign_id', '=', 'leave_assign.id')
                                ->join('leave_type', 'leave_assign.leave_type', '=', 'leave_type.id')
                                ->join('location', 'employee.location', '=', 'location.location_id')
                                ->paginate($perpage);
                                $jsonData = $employeeData1->toJson();

                                    // Output the JSON response
                                    echo $jsonData;
                                 return response()->json([
                                    'employeeData' => $employeeData1,
                                ]);
                                die();
            $paginationUserData = [
                'totalCount' => $employeeData1->count(),
                'totalPage' => 1, 
            ];
            return response()->json([
                'id' => $empId,
                'message'=> 'Successfull Found',
                'status' => 'success',
                'response' => [
                'apply_leave' => $employeeData1->toArray(),
            ],

                'pagination' => $paginationUserData1,
            ]);

        }

        // else {
        //         $isValidEmployeeId =  Employee::where('employeeid', $empId)->first();

        //         if (!$isValidEmployeeId) {
        //             return response()->json([
        //                 'id' => 'Invalid Employee ID',
        //                 'message' => 'The provided employee ID is not valid.',
        //                 'status' => 'unsuccess',
        //                 'response' => [],
        //             ]);
        //         }

        //         $employeeData = Employee::where('employeeid', $empId)->first();
        //         if ($employeeData) {
        //             return response()->json([
        //                 'id' => $empId,
        //                 'message' => 'Successfully Found.',
        //                 'status' => 'success',
        //                 'response' => $employeeData,
        //             ]);
        //         } else {
        //             return response()->json([
        //                 'id' => $empId,
        //                 'message' => 'Unsuccessfully Found.',
        //                 'status' => 'unsuccess',
        //                 'response' => [],
        //             ]);
        //         }
        //     }
        //     die();

        // }

        // $paginationUserData = [
        //     'totalCount' => $query->total(),
        //     'totalPage' => $query->lastPage(),
        // ];

        // $formattedData = $query->map(function ($leave) {
        //     return [
        //         'id' => $leave->id,
        //         'employeeid' => $leave->employeeid,
        //         'assign_id' => $leave->assign_id,
        //         'count' => $leave->count,
        //         'status' => $leave->status,
        //         'start' => $leave->start,
        //         'half_start' => $leave->half_start,
        //         'half_end' => $leave->half_end,
        //         'end' => $leave->end,
        //         'approver' => $leave->approver,
        //         'created_at' => $leave->created_at,
        //         'updated_at' => $leave->updated_at,
        //         'reason' => $leave->reason,
        //         'reject_reason' => $leave->reject_reason,
        //         'rejoin' => $leave->rejoin,
        //         'first_name' => $leave->first_name,
        //         'last_name' => $leave->last_name,
        //         'email' => $leave->email,
        //         'location' => $leave->location,
        //         'department' => $leave->department,
        //         'type' => $leave->type,
        //         'is_active' => $leave->is_active,
        //         'is_deleted' => $leave->is_deleted,
        //         'color' => $leave->color,
        //         'isdeleted' => $leave->isdeleted,
        //         'appid' => $leave->id,
        //         'locationname' => $leave->locationName,
        //         'leave_count' => $leave->leave_count,
        //         'Leavestart' => date('m-d-Y', strtotime($leave->start)),
        //         'Leaveend' => date('m-d-Y', strtotime($leave->end)),
        //     ];
        // });

        // return response()->json([
        //     'id' => $empId,
        //     'message' => 'Successfully Found.',
        //     'status' => 'success',
        //     'response' => $formattedData->toArray(),
        //     'pagination' => $paginationUserData,
        // ]);
    }
}
