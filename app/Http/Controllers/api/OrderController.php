<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Auth;
use Hamcrest\NullDescription;
use Illuminate\Http\Request;
use Illuminate\Queue\Connectors\NullConnector;
use Illuminate\Support\Facades\Validator;
use App\Models\Party;
use App\Models\TempOrder;
use App\Models\TempOrderDetail;

class OrderController extends Controller
{
    //
    public function search_party(Request $request)
    {
        $search = $request->get("search");

        // Check if the search input is a valid GST number
        $isGstValid = preg_match("/^([0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}[Z]{1}[0-9A-Z]{1})$/", $search);

        if ($isGstValid) {
            // If it's a valid GST number, search only by gst_no
            $party = Party::where('gst_no', $search)->first();
        } else {
            // If not a valid GST, perform general search
            $party = Party::where('mobile_no', 'LIKE', "$search%")
                ->orWhere('name', 'LIKE', "$search%")
                ->orWhere('city', 'LIKE', "$search%")
                ->first();
        }



        if (!$party) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Party not found.',
                'data' => []
            ]);
        }

        return response()->json([
            'status_code' => 200,
            'message' => 'Party found.',
            'data' => [$party]
        ]);
    }

    public function create_offer_form(Request $request)
    {
        $rules = [
            'order_date' => 'required|date',
            'p_gst_no' => [
                'nullable',
                'regex:/^([0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}[Z]{1}[0-9A-Z]{1})$/'
            ],
        ];


        $messages = [
            'p_gst_no.regex' => 'The GST number format is invalid. Please enter a valid GST number.',
        ];




        // Validate the incoming request
        $validator = Validator::make($request->all(), $rules, $messages);

        // Check if validation fails
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(' ', $errors);
            return response()->json([
                'status_code' => 400,
                'message' => $errorMessage,
                'data' => []
            ]);
        }

        // // Retrieve authenticated user ID
        // $userId = Auth::id();

        // if (!$userId) {
        //     return response()->json([
        //         'status_code' => 401,
        //         'message' => 'Unauthorized. Please log in.',
        //         'data' => []
        //     ]);
        // }

        if ($request->p_id) {
            // If the party exists, fetch and update
            $party = Party::find($request->p_id);

            if ($party) {
                // Update the Party details with new values if they are provided
                $party->haste = $request->p_haste ?? $party->haste; // If haste is provided, update it
                $party->booking = $request->p_booking ?? $party->booking; // If booking is provided, update it
                $party->export = $request->p_export ?? $party->export; // If export is provided, update it
                $party->name = $request->p_name ?? $party->name;
                $party->email = $request->p_email ?? $party->email;
                $party->mobile_no = $request->p_mobile_no ?? $party->mobile_no;
                $party->address = $request->p_address ?? $party->address;
                $party->city = $request->p_city ?? $party->city;
                $party->gst_no = $request->p_gst_no ?? $party->gst_no;
                $party->agent = $request->p_agent ?? $party->agent;
                $party->transport = $request->p_transport ?? $party->transport;
                $party->pin_code = $request->p_pin_code ?? $party->pin_code;
                $party->save(); // Save the updated party record

                // Now create the TempOrder
                $tempOrder = new TempOrder();
                $tempOrder->party_id = $party->id;
                $tempOrder->order_date = $request->order_date;
                $tempOrder->packing_bag = $request->packing_bag;
                $tempOrder->party_data = $party;// add by ROHIT
                $tempOrder->created_by = $request->created_by ?? ' ';
                $tempOrder->save();

                $order_number = $tempOrder->order_number;
                return response()->json([
                    'status_code' => 200,
                    'message' => 'Offer form created successfully.',
                    'order_number' => $order_number
                ]);
            } else {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Party not found.',
                    'data' => []
                ]);
            }
        } else {
            // If the party does not exist, create a new one
            $party = new Party();
            $party->haste = $request->p_haste ?? ' ';
            $party->booking = $request->p_booking ?? ' ';
            $party->export = $request->p_export ?? ' ';
            $party->name = $request->p_name;
            $party->email = $request->p_email ?? ' ';
            $party->mobile_no = $request->p_mobile_no;
            $party->address = $request->p_address ?? ' ';
            $party->city = $request->p_city;
            $party->gst_no = $request->p_gst_no;
            $party->agent = $request->p_agent ?? ' ';
            $party->transport = $request->p_transport ?? ' ';
            $party->pin_code = $request->p_pin_code;
            $party->save();

            // Now create the TempOrder
            $tempOrder = new TempOrder();
            $tempOrder->party_id = $party->id;
            $tempOrder->order_date = $request->order_date;
            $tempOrder->packing_bag = $request->packing_bag;
            $tempOrder->party_data = $party;// add by ROHIT
            $tempOrder->created_by = $request->created_by;
            $tempOrder->save();

            $order_number = $tempOrder->order_number;
            return response()->json([
                'status_code' => 200,
                'message' => 'Offer form created successfully.',
                'order_number' => $order_number
            ]);
        }
    }


    public function create_offer_order(Request $request)
    {
        // dd($request->all());
        $rules = [
            'order_number' => 'required|string|unique:temp_order_details,temp_order_number',
            'product_data' => 'required|json',
        ];

        // Validate the incoming request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(' ', $errors);
            return response()->json([
                'status_code' => 400,
                'message' => $errorMessage,
                'data' => []
            ]);
        }

        // Fetch the TempOrder using the order number
        $tempOrder = TempOrder::where('order_number', $request->order_number)->first();
        // dd($tempOrder);

        if (!$tempOrder) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Order not found.',
                'data' => []
            ]);
        }

        // Decode the JSON product_data
        $productData = json_decode($request->product_data, true);

        if (empty($productData) || !is_array($productData)) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Invalid product data.',
                'data' => []
            ]);
        }

        $storedProducts = [];

        // Loop through each product in the product_data array
        foreach ($productData as $product) {
            $tempOrderDetail = new TempOrderDetail();
            $tempOrderDetail->temp_order_id = $tempOrder->id;
            $tempOrderDetail->temp_order_number = $tempOrder->order_number;
            $tempOrderDetail->product_id = $product['product_id'] ?? null;
            // $tempOrderDetail->party_id = $temp
            $tempOrderDetail->category_name = $product['category_name'] ?? null;
            $tempOrderDetail->p_name = $product['p_name'] ?? null;
            $tempOrderDetail->buyqty = $product['buyQty'] ?? 0;
            $tempOrderDetail->remark = $product['remark'] ?? null;

            // Store JSON of the single product
            $tempOrderDetail->product_data = json_encode($product);

            // Save the individual record
            $tempOrderDetail->save();

            // Add saved record to the response data
            $storedProducts[] = $tempOrderDetail;
        }

        return response()->json([
            'status_code' => 200,
            'message' => 'Order created successfully.',
            'data' => $storedProducts
        ]);
    }


    public function order_form_list(Request $request)
    {
        $rules = [
            'limit' => 'required|integer|min:1',
            'page' => 'required|integer|min:1',
            'search' => 'nullable|string', // Optional search field
        ];

        // Validate the incoming request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(' ', $errors);
            return response()->json([
                'status_code' => 400,
                'message' => $errorMessage,
                'data' => []
            ]);
        }

        $limit = $request->limit;
        $page = $request->page;
        $search = $request->search;

        // Build query with optional search functionality, joining temp_order and party
        $tempOrderQuery = TempOrder::query()
            ->leftJoin('party', 'temp_orders.party_id', '=', 'party.id') // Join Party table
            ->select(
                'temp_orders.id as order_id',
                'temp_orders.order_number',
                'temp_orders.order_date',
                'temp_orders.packing_bag',
                'temp_orders.created_by',
                'temp_orders.created_at',
                'party.name',
                'party.mobile_no',
                'party.*'
            ); // Alias temp_orders.id as order_id

        // Apply search filters for order_number, party_name, and mobile_no
        if ($search) {
            $tempOrderQuery->where(function ($query) use ($search) {
                $query->where('temp_orders.order_number', 'like', "%$search%")
                    ->orWhere('party.name', 'like', "%$search%")
                    ->orWhere('party.mobile_no', 'like', "%$search%");
            });
        }

        // Order by most recent order
        $tempOrderQuery->orderBy('temp_orders.id', 'desc');

        // Paginate the results
        $tempOrders = $tempOrderQuery->paginate($limit, ['*'], 'page', $page);

        // Format the data for response
        $formattedData = $tempOrders->map(function ($order) {
            // Fetch all the details from the temp_order_details table for the given temp_order_id
            $orderDetails = TempOrderDetail::where('temp_order_id', $order->order_id)->get();

            // Group order details by category_name
            $groupedOrderDetails = $orderDetails->groupBy('category_name')->map(function ($group, $categoryName) {
                return [
                    'category_name' => $categoryName,
                    'products' => $group->map(function ($orderDetail) {
                        return [
                            'order_detail_id' => $orderDetail->id, // Include temp_order_detail id
                            'product_id' => $orderDetail->product_id,
                            'p_name' => $orderDetail->p_name,
                            'buyqty' => $orderDetail->buyqty,
                            'product_data' => json_decode($orderDetail->product_data), // Decode JSON for product data
                        ];
                    })->values(), // Reset the index for the array
                ];
            })->values(); // Reset the index for the grouped data

            $user = Staff::where('id', $order->created_by)->first();

            return [
                'party_detail' => [
                    'party_id' => $order->id,
                    'party_name' => $order->name,
                    'mobile_no' => $order->mobile_no,
                    'city' => $order->city,

                    'address' => $order->address, // Include other party details if needed
                    'email' => $order->email,
                    'gst_no' => $order->gst_no,
                    'agent' => $order->agent,
                    'transport' => $order->transport,
                    'haste' => $order->haste,
                    'pin_code' => $order->pin_code,
                    'booking' => $order->booking,
                    'export' => $order->export,
                ],
                'order_id' => $order->order_id, // Correctly map the alias 'order_id' for temp_orders.id
                'order_number' => $order->order_number,
                'order_date' => $order->order_date,
                'packing_bag' => $order->packing_bag,
                'created_by' => $user->name,
                'order_details' => $groupedOrderDetails, // Include grouped order details by category
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
            ];
        });

        // Return paginated data with order details and party details
        return response()->json([
            'status_code' => 200,
            'message' => 'Order form list retrieved successfully.',
            'data' => $formattedData
        ]);
    }


    public function delete_temp_order(Request $request)
    {
        $rules = [
            'id' => 'required|integer',  // Ensure id is an integer
        ];

        // Validate the incoming request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(' ', $errors);
            return response()->json([
                'status_code' => 400,
                'message' => $errorMessage,
                'data' => []
            ]);
        }

        // Find the temp order by ID
        $tempOrder = TempOrder::find($request->id);
        if (!$tempOrder) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Order not found.',
                'data' => []
            ]);
        }

        // Retrieve the order_number from the temp_order
        $orderNumber = $tempOrder->order_number;

        // Check if any records exist in the temp_order_details table with the given order_number
        $orderDetailsExist = TempOrderDetail::where('temp_order_number', $orderNumber)->exists();

        if ($orderDetailsExist) {
            // If records exist in temp_order_details, do not delete the temp_order
            return response()->json([
                'status_code' => 400,
                'message' => 'Cannot delete this order as it has related order details.',
                'data' => []
            ]);
        }

        // If no records exist in temp_order_details, delete the temp_order record
        $tempOrder->delete();

        return response()->json([
            'status_code' => 200,
            'message' => 'Order deleted successfully.',
            'data' => []
        ]);
    }

    public function search_temp_order(Request $request)
    {
        $rules = [
            'order_number' => 'required|string', // Order number is required
        ];

        // Validate the incoming request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(' ', $errors);
            return response()->json([
                'status_code' => 400,
                'message' => $errorMessage,
                'data' => []
            ]);
        }

        $orderNumber = $request->order_number;

        // Fetch the TempOrder and related party details
        $tempOrder = TempOrder::leftJoin('party', 'temp_orders.party_id', '=', 'party.id')
            ->select(
                'temp_orders.id as order_id',
                'temp_orders.order_number',
                'temp_orders.order_date',
                'temp_orders.created_at',
                'party.name',
                'party.mobile_no',
                'party.*'
            )
            ->where('temp_orders.order_number', $orderNumber)
            ->first();

        if (!$tempOrder) {
            return response()->json([
                'status_code' => 404,
                'message' => 'Order not found.',
                'data' => []
            ]);
        }

        // Fetch all the details from the temp_order_details table for the given temp_order_id
        $orderDetails = TempOrderDetail::where('temp_order_id', $tempOrder->order_id)->get();

        // Group order details by category_name
        $groupedOrderDetails = $orderDetails->groupBy('category_name')->map(function ($group, $categoryName) {
            return [
                'category_name' => $categoryName,
                'products' => $group->map(function ($orderDetail) {
                    return [
                        'order_detail_id' => $orderDetail->id, // Include temp_order_detail id
                        'product_id' => $orderDetail->product_id,
                        'p_name' => $orderDetail->p_name,
                        'buyqty' => $orderDetail->buyqty,
                        'product_data' => json_decode($orderDetail->product_data), // Decode JSON for product data
                    ];
                })->values(), // Reset the index for the array
            ];
        })->values(); // Reset the index for the grouped data

        // Format the response data
        $formattedData = [
            'party_detail' => [
                'party_id' => $tempOrder->id,
                'party_name' => $tempOrder->name,
                'mobile_no' => $tempOrder->mobile_no,
                'city' => $tempOrder->city,
                'address' => $tempOrder->address, // Include other party details if needed
                'email' => $tempOrder->email,
                'gst_no' => $tempOrder->gst_no,
                'agent' => $tempOrder->agent,
                'transport' => $tempOrder->transport,
                'haste' => $tempOrder->haste,
                'pin_code' => $tempOrder->pin_code,
                'booking' => $tempOrder->booking,
                'export' => $tempOrder->export,

            ],
            'order_id' => $tempOrder->order_id,
            'order_number' => $tempOrder->order_number,
            'order_date' => $tempOrder->order_date,
            'packing_bag' => $tempOrder->packing_bag,
            'created_by' => $tempOrder->created_by,
            'order_details' => $groupedOrderDetails,
            'created_at' => $tempOrder->created_at->format('Y-m-d H:i:s'),
        ];

        // Return the formatted response
        return response()->json([
            'status_code' => 200,
            'message' => 'Order details retrieved successfully.',
            'data' => [$formattedData]
        ]);
    }

    public function edit_temp_order_detail(Request $request)
    {
        // dd($request->all());
        $rules = [
            'order_number' => 'required|string|exists:temp_order_details,temp_order_number',
            'id' => 'required|exists:temp_order_details,id'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 400,
                'message' => $validator->errors()->first(),

            ]);
        }
        $tempOrderUpdate = TempOrderDetail::where('id', $request->id)->where('temp_order_number', $request->order_number)->first();
        // dd($tempOrderUpdate);
        if ($tempOrderUpdate) {
            $tempOrderUpdate->product_id = $request->product_id ?? $tempOrderUpdate->product_id;
            $tempOrderUpdate->p_name = $request->p_name ?? $tempOrderUpdate->p_name;
            $tempOrderUpdate->category_name = $request->category_name ?? $tempOrderUpdate->category_name;
            $tempOrderUpdate->buyqty = $request->buyqty ?? $tempOrderUpdate->buyqty;
            $tempOrderUpdate->remark = $request->remark ?? null;
            $tempOrderUpdate->product_data = $request->product_data ?? $tempOrderUpdate->product_data;
            $tempOrderUpdate->save();

            return response()->json([
                'status_code' => 200,
                'message' => 'Order updated successfully.',

            ]);
        } else {
            return response()->json([
                'status_code' => 404,
                'message' => 'Order not found.',

            ]);
        }

    }
    public function edit_party(Request $request)
    {
        $rules = [
            'id' => 'required|integer',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(' ', $errors);
            return response()->json([
                'status_code' => 400,
                'message' => $errorMessage,
                'data' => []
            ]);
        }
        $party = Party::find($request->id);
        if (!$party) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Party not found.',
                'data' => []
            ]);
        }
        $party->name = $request->name ?? $party->name;
        $party->email = $request->email ?? $party->email;
        $party->mobile_no = $request->mobile_no ?? $party->mobile_no;
        $party->address = $request->address ?? $party->address;
        $party->city = $request->city ?? $party->city;
        $party->gst_no = $request->gst_no ?? $party->gst_no;
        $party->agent = $request->agent ?? $party->agent;
        $party->transport = $request->transport ?? $party->transport;
        $party->haste = $request->haste ?? $party->haste;
        $party->pin_code = $request->pin_code ?? $party->pin_code;
        $party->booking = $request->booking ?? $party->booking;
        $party->export = $request->export ?? $party->export;
        $party->save();

        return response()->json([
            'status_code' => 200,
            'message' => 'Party updated successfully.',
            'data' => $party
        ]);
    }

    public function edit_temp_order(Request $request)
    {
        $rules = [
            'id' => 'required|integer'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(' ', $errors);
            return response()->json([
                'status_code' => 400,
                'message' => $errorMessage,
                'data' => []
            ]);
        }
        $tempOrder = TempOrder::find($request->id);
        if (!$tempOrder) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Temp order not found.',
                'data' => []
            ]);
        }
        $tempOrder->party_id = $request->party_id ?? $tempOrder->party_id;
        $tempOrder->order_date = $request->order_date ?? $tempOrder->order_date;
        $tempOrder->packing_bag = $request->packing_bag ?? $tempOrder->packing_bag;
        $tempOrder->party_data = $request->party_data ?? $tempOrder->party_data;
        $tempOrder->save();

        return response()->json([
            'status_code' => 200,
            'message' => 'Temp order updated successfully.',

        ]);
    }

    public function delete_temp_order_detail(Request $request)
    {
        $rules = [
            'id' => 'required|integer|exists:temp_order_details,id',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(' ', $errors);
            return response()->json([
                'status_code' => 400,
                'message' => $errorMessage,
                'data' => []
            ]);
        }
        $tempOrder = TempOrderDetail::find($request->id);
        $tempOrder->delete();
        return response()->json([
            'status_code' => 200,
            'message' => 'Temp order deleted successfully.',

        ]);
    }



}