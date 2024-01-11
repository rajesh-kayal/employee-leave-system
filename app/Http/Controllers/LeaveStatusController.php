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
    public function Leaves_alldata1(Request $request)
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

        $query = ApplyLeave::select(
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
                            //->where('apply_leave.status', 'approve') 
                            ->get();
            //FETCH THOSE ID WHCH STATUS IS APROVE 
        if ($query->isEmpty()) {
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

            if ($empId === '1230') {
            $employeeData1 = ApplyLeave::select(
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

                'pagination' => $paginationUserData,
            ]);

        }


        else {
                $isValidEmployeeId =  Employee::where('employeeid', $empId)->first();

                if (!$isValidEmployeeId) {
                    return response()->json([
                        'id' => 'Invalid Employee ID',
                        'message' => 'The provided employee ID is not valid.',
                        'status' => 'unsuccess',
                        'response' => [],
                    ]);
                }

                $employeeData = Employee::where('employeeid', $empId)->first();
                if ($employeeData) {
                    return response()->json([
                        'id' => $empId,
                        'message' => 'Successfully Found.',
                        'status' => 'success',
                        'response' => $employeeData,
                    ]);
                } else {
                    return response()->json([
                        'id' => $empId,
                        'message' => 'Unsuccessfully Found.',
                        'status' => 'unsuccess',
                        'response' => [],
                    ]);
                }
            }
        }


        // $paginationUserData = [
        //     'totalCount' => $query->total(),
        //     'totalPage' => $query->lastPage(),
        // ];

        $formattedData = $query->map(function ($leave) {
            return [
                'id' => $leave->id,
                'employeeid' => $leave->employeeid,
                'assign_id' => $leave->assign_id,
                'count' => $leave->count,
                'status' => $leave->status,
                'start' => $leave->start,
                'half_start' => $leave->half_start,
                'half_end' => $leave->half_end,
                'end' => $leave->end,
                'approver' => $leave->approver,
                'created_at' => $leave->created_at,
                'updated_at' => $leave->updated_at,
                'reason' => $leave->reason,
                'reject_reason' => $leave->reject_reason,
                'rejoin' => $leave->rejoin,
                'first_name' => $leave->first_name,
                'last_name' => $leave->last_name,
                'email' => $leave->email,
                'location' => $leave->location,
                'department' => $leave->department,
                'type' => $leave->type,
                'is_active' => $leave->is_active,
                'is_deleted' => $leave->is_deleted,
                'color' => $leave->color,
                'isdeleted' => $leave->isdeleted,
                'appid' => $leave->id,
                'locationname' => $leave->locationName,
                'leave_count' => $leave->leave_count,
                'Leavestart' => date('m-d-Y', strtotime($leave->start)),
                'Leaveend' => date('m-d-Y', strtotime($leave->end)),
            ];
        });

        return response()->json([
            'id' => $empId,
            'message' => 'Successfully Found.',
            'status' => 'success',
            'response' => $formattedData->toArray(),
            // 'pagination' => $paginationUserData,
        ]);
    }




    public function Leaves_alldata(Request $request)
{
    // ... (existing code)
    $employee_id = $request->employee_id;
        $empId = $request->empid;
        $com = $request->com;
        $type = $request->type;
        $perpage = $request->perpage ; 
        $page = $request->page ;
        $search = $request->search;
        $firstName = $request->first_name;
        $pageOffset = ($perpage * $page) - $perpage;

    $query = ApplyLeave::select(
            'apply_leave.*',
            'employee.*',
            'leave_type.*',
            'leave_assign.leave_count',
            'location.locationName'
        )
        ->join('employee', 'apply_leave.employeeid', '=', 'employee.employeeid')
        ->join('leave_assign', 'apply_leave.assign_id', '=', 'leave_assign.id')
        ->join('leave_type', 'leave_assign.leave_type', '=', 'leave_type.id')
        ->join('location', 'employee.location', '=', 'location.location_id');

    // Add your where clauses here if needed
    // $query->where('apply_leave.status', 'approve');

    // Paginate the results
    $perPage = $request->perpage ?? 10; // Set your default per page value
    $results = $query->paginate($perPage);

    // Format the results as needed
    $formattedData = $results->map(function ($leave) {
         return [
                'id' => $leave->id,
                'employeeid' => $leave->employeeid,
                'assign_id' => $leave->assign_id,
                'count' => $leave->count,
                'status' => $leave->status,
                'start' => $leave->start,
                'half_start' => $leave->half_start,
                'half_end' => $leave->half_end,
                'end' => $leave->end,
                'approver' => $leave->approver,
                'created_at' => $leave->created_at,
                'updated_at' => $leave->updated_at,
                'reason' => $leave->reason,
                'reject_reason' => $leave->reject_reason,
                'rejoin' => $leave->rejoin,
                'first_name' => $leave->first_name,
                'last_name' => $leave->last_name,
                'email' => $leave->email,
                'location' => $leave->location,
                'department' => $leave->department,
                'type' => $leave->type,
                'is_active' => $leave->is_active,
                'is_deleted' => $leave->is_deleted,
                'color' => $leave->color,
                'isdeleted' => $leave->isdeleted,
                'appid' => $leave->id,
                'locationname' => $leave->locationName,
                'leave_count' => $leave->leave_count,
                'Leavestart' => date('m-d-Y', strtotime($leave->start)),
                'Leaveend' => date('m-d-Y', strtotime($leave->end)),
            ];
    });

    return response()->json([
        'id' => $empId,
        'message' => 'Successfully Found.',
        'status' => 'success',
        'response' => $formattedData->toArray(),
        'pagination' => [
            'total' => $results->total(),
            'per_page' => $results->perPage(),
            'current_page' => $results->currentPage(),
            'last_page' => $results->lastPage(),
            'from' => $results->firstItem(),
            'to' => $results->lastItem(),
        ],
    ]);
}

}

?>

