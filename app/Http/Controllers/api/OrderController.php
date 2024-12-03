<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Party;
use App\Models\TempOrder;
use App\Models\TempOrderDetail;

class OrderController extends Controller
{
    //
    public function search_party(Request $request)
    {


        $party = Party::where('mobile_no', $request->mobile_no)
            ->orWhere('name', $request->name)
            ->orWhere('gst_no', $request->gst_no)
            ->orWhere('city', $request->city)->first();

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
        ];

        // Validate the incoming request
        $validator = Validator::make($request->all(), $rules);

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
        if ($request->p_id) {
            $party = Party::find($request->p_id);
            if ($party) {
                $tempOrder = new TempOrder();
                $tempOrder->party_id = $party->id;
                $tempOrder->order_date = $request->order_date;
                $tempOrder->save();
            } else {
            }
            $order_number = $tempOrder->order_number;
            return response()->json([
                'status_code' => 200,
                'message' => 'Offer form created successfully.',
                'order_number' => $order_number
            ]);
        } else {
            $party = new Party();
            $party->name = $request->p_name;
            $party->mobile_no = $request->p_mobile_no;
            $party->address = $request->p_address;
            $party->city = $request->p_city;
            $party->email = $request->p_email;
            $party->gst_no = $request->p_gst_no;
            $party->save();
            $tempOrder = new TempOrder();
            $tempOrder->party_id = $party->id;
            $tempOrder->order_date = $request->order_date;
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
        $rules = [
            'order_number' => 'required|string',
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
                ],
                'order_id' => $order->order_id, // Correctly map the alias 'order_id' for temp_orders.id
                'order_number' => $order->order_number,
                'order_date' => $order->order_date,
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












}
