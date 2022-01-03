<?php

namespace App\Http\Controllers;

use App\Models\customerStatus;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class CustomerStatusController extends Controller {

    protected $customerStatus;

    public function __construct(Request $request) {
        try {
            if ($request->bearerToken() == null) {
                return response()->json(['token_not_found'], Response::HTTP_NOT_FOUND);
            } else {
                if (!$this->customerStatus = JWTAuth::parseToken()->authenticate()) {

                    return response()->json(['user_found'], Response::HTTP_NOT_FOUND);
                } else {
                    return response()->json(['user_not_found'], Response::HTTP_NOT_FOUND);
                }
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['user_not_found'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        if (!empty($request->header('platform')) && !empty($request->header('source'))) {
            return response()->json([
                        'success' => true,
                        'message' => 'Customer get all successfully',
                        'datas' => array(
                            'details' => customerStatus::get(),
                            'data' => $this->customerStatus
                        )
                            ], Response::HTTP_OK);
        } else {
            return response()->json([
                        'success' => false,
                        'message' => 'Sorry, source or platform is empty'
                            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        if (!empty($request->header('platform')) && !empty($request->header('source'))) {
            $data = $request->only('status', 'position');
            $validator = Validator::make($data, [
                        'status' => 'required|string|in:active,inactive',
                        'position' => 'required|string',
            ]);

            //Send failed response if request is not valid
            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], 200);
            }

            //Request is valid, create new customer status
            $check_data = customerStatus::where('status', $request->status)
                    ->where('position', $request->position)
                    ->where('user_id', $this->customerStatus->id)
                    ->first();
            if (!$check_data) {
                $dataResults = new customerStatus([
                    'user_id' => $this->customerStatus->id,
                    'status' => $request->status,
                    'position' => $request->position,
                ]);

                $dataResults->save();

                //Customer Status created, return success response
                return response()->json([
                            'success' => true,
                            'message' => 'Customer Status created successfully',
                            'datas' => array(
                                'details' => $dataResults,
                                'data' => $this->customerStatus
                            )
                                ], Response::HTTP_OK);
            } else {
                return response()->json([
                            'success' => true,
                            'message' => 'Customer Status already record',
                            'data' => null
                                ], Response::HTTP_OK);
            }
        } else {
            return response()->json([
                        'success' => false,
                        'message' => 'Sorry, source or platform is empty'
                            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\customerStatus  $customerStatus
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        if (!empty($request->header('platform')) && !empty($request->header('source'))) {
            $customerStatus = customerStatus::find($id);

            if (!$customerStatus) {
                return response()->json([
                            'success' => false,
                            'message' => 'Sorry, customer status not found.',
                            'data' => null
                                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                        'success' => true,
                        'message' => 'Success get detail customer status.',
                        'data' => array(
                            'details' => $customerStatus,
                            'data' => $this->customerStatus
                        )
                            ], Response::HTTP_OK);
        } else {
            return response()->json([
                        'success' => false,
                        'message' => 'Sorry, source or platform is empty'
                            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\customerStatus  $customerStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        if (!empty($request->header('platform')) && !empty($request->header('source'))) {
            $data = $request->only('status', 'position');
            $validator = Validator::make($data, [
                        'status' => 'required|string|in:active,inactive',
                        'position' => 'required|string',
            ]);

            //Send failed response if request is not valid
            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], 200);
            }

            //Request is valid, update customer status
            $customerStatusUpdate = customerStatus::where('id', $id)
                    ->update([
                'user_id' => $this->customerStatus->id,
                'status' => $request->status,
                'position' => $request->position,
            ]);

            if ($customerStatusUpdate) {
                return response()->json([
                            'success' => true,
                            'message' => 'Customer Status updated successfully',
                            'data' => null,
                                ], Response::HTTP_OK);
            } else {
                return response()->json([
                            'success' => false,
                            'message' => 'Customer Status updated failed',
                            'data' => null
                                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            return response()->json([
                        'success' => false,
                        'message' => 'Sorry, source or platform is empty'
                            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\customerStatus  $customerStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        if (!empty($request->header('platform')) && !empty($request->header('source'))) {
            $data = customerStatus::destroy($id);

            if ($data) {
                return response()->json([
                            'success' => true,
                            'message' => 'Customer Status deleted successfully',
                            'data' => null
                                ], Response::HTTP_OK);
            } else {
                return response()->json([
                            'success' => false,
                            'message' => 'Customer Status deleted failed',
                            'data' => null
                                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            return response()->json([
                        'success' => false,
                        'message' => 'Sorry, source or platform is empty'
                            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
