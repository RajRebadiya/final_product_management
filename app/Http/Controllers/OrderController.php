<?php

namespace App\Http\Controllers;

use App\Models\Party;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\TempOrder;
use App\Models\TempOrderDetail;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class OrderController extends Controller
{
    public function offer_form(Request $request)
    {

        $search = $request->input('search', '');
        // Load and paginate products with the required fields, applying search filter if present
        $products = Product::with([
            'category' => function ($query) {
                $query->select('id', 'name'); // Only load 'id' and 'name' from categories
            }
        ])->where(function ($query) use ($search) {
            // Apply search condition for product name and category name
            $query->where('p_name', 'like', "%$search%")
                ->orWhereHas('category', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                });
        })
            ->where('status', "Active")
            ->paginate(10); // Paginate products with 10 items per page

        // Transform the products to include the category name directly
        $products->transform(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->p_name,
                'category_name' => $product->category ? $product->category->name : null,
                'image' => $product->image,
                'thumb' => $product->thumb,
                'price' => $product->price,
                'qty' => $product->qty,
                'category_id' => $product->category_id,
            ];
        });

        $parties = Party::all();

        // dd($products);

        // Pass paginated products and categories to the view
        return view('admin.order.offer_form', compact('products', 'parties'));
    }

    public function offer_form_list(Request $request)
    {
        // Fetch search parameters from the request
        $orderNumber = $request->input('order_number');
        $orderDate = $request->input('order_date');

        // Use Query Builder to join TempOrder with the Party table and filter by temp_order_details
        $orders = DB::table('temp_orders')
            ->join('party', 'temp_orders.party_id', '=', 'party.id') // Join 'party' table
            ->join('temp_order_details', 'temp_orders.order_number', '=', 'temp_order_details.temp_order_number') // Join 'temp_order_details' table
            ->select(
                'temp_orders.*', // Select all columns from temp_orders table
                'party.name as party_name', // Select 'name' from party table and alias it as party_name
                'party.email as party_email', // Add party-related columns
                'party.mobile_no as party_mobile_no'
            )
            ->when($orderNumber, function ($query, $orderNumber) {
                return $query->where('temp_orders.order_number', 'like', "%{$orderNumber}%");
            }) // Apply filter for order_number if provided
            ->when($orderDate, function ($query, $orderDate) {
                return $query->whereDate('temp_orders.order_date', $orderDate);
            }) // Apply filter for order_date if provided
            ->distinct('temp_orders.order_number') // Ensure only distinct order_numbers
            ->paginate(5); // Paginate the results

        return view('admin.order.offer_form_list', compact('orders'));
    }




    public function offer_form_detail(Request $request, $orderNumber)
    {
        // Fetch all products belonging to the given order number
        $orderDetails = DB::table('temp_order_details')
            ->join('temp_orders', 'temp_order_details.temp_order_id', '=', 'temp_orders.id')
            ->join('party', 'temp_orders.party_id', '=', 'party.id')
            ->where('temp_orders.order_number', $orderNumber)
            ->select(
                'temp_order_details.product_data', // JSON product details
                'temp_orders.order_number', // Order number
                'temp_orders.order_date', // Order date
                'party.name as party_name', // Party name
                'party.email as party_email', // Party email
                'party.mobile_no as party_mobile_no', // Party mobile number
                'party.address as party_address', // Party address
                'party.city as party_city', // Party city
                'party.gst_no as party_gst_no' // Party GST number
            )
            ->get();

        // If no orders are found, return a 404 error
        if ($orderDetails->isEmpty()) {
            abort(404, 'No orders found for the given order number.');
        }

        // Extract product details and group them into an array
        $products = [];
        foreach ($orderDetails as $detail) {
            if (!empty($detail->product_data)) {
                $productData = json_decode($detail->product_data, true); // Decode JSON product_data into an array
                if (is_array($productData)) {
                    $products[] = $productData; // Add product details to the array
                }
            }
        }

        // Get order-level details (e.g., party details) from the first record
        $order = $orderDetails->first();
        $order->products = $products; // Add all products to the order object
        // dd($products);

        // Return view with the consolidated order data
        return view('admin.order.offer_form_detail', compact('order', 'products'));
    }





    public function save_temp_order(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'product_ids' => 'required|array|min:1',
            'name' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'pin_code' => 'required|string|max:255',
            'gst_no' => 'required|string|max:255',
            'order_date' => 'required|date',
            'buyqty' => 'required|array', // Validate buyqty array
            'remark' => 'nullable|array', // Validate remark array if it's provided
        ]);

        // Find or create the party record
        $party = Party::updateOrCreate(
            [
                'mobile_no' => $request->mobile_no,

            ],
            [
                'email' => $request->email,
                'name' => $request->name,
                'address' => $request->address,
                'city' => $request->city,
                'pin_code' => $request->pin_code,
                'gst_no' => $request->gst_no,
                'haste' => $request->haste,
                'booking' => $request->booking,
                'export' => $request->export,
            ]
        );

        // Create the temporary order and associate it with the party
        $tempOrder = new TempOrder();
        $tempOrder->party_id = $party->id; // Store the associated party ID
        $tempOrder->order_date = $request->order_date;
        $tempOrder->packing_bag = $request->packing_bag;
        $tempOrder->created_by = auth('staff')->user()->id;
        $tempOrder->party_data = $party;
        $tempOrder->save();

        $productDetails = [];

        // Save the products in the temp_order_details table
        foreach ($request->product_ids as $productId) {
            $product = Product::find($productId);

            if ($product) {
                $productData = [
                    'product_id' => (string) $product->id,
                    'p_name' => $product->p_name,
                    'image' => url('storage/images/' . $product->category_name . '/' . $product->image),
                    'category_id' => (string) $product->category->id,
                    'category_name' => $product->category->name,
                    'price' => (float) $product->price,
                    'thumb' => url('storage/thumbnail/' . $product->category_name . '/' . $product->thumb),
                    'stock' => (int) $product->stock,
                    'buyQty' => $request->buyqty[$productId] ?? 0,  // Get buyqty for each product
                    'remark' => $request->remark[$productId] ?? '', // Get remark for each product
                ];

                $tempOrderDetail = new TempOrderDetail();
                $tempOrderDetail->temp_order_id = $tempOrder->id;
                $tempOrderDetail->temp_order_number = $tempOrder->order_number;
                $tempOrderDetail->product_id = $productId;
                $tempOrderDetail->category_name = $product->category->name;
                $tempOrderDetail->p_name = $product->p_name;
                $tempOrderDetail->buyqty = $request->buyqty[$productId] ?? 0; // Save buyqty
                $tempOrderDetail->product_data = json_encode($productData); // Save product data as JSON
                $tempOrderDetail->remark = $request->remark[$productId] ?? ''; // Save remark

                $tempOrderDetail->save();

                // Add product details to the response array
                $productDetails[] = $productData;
            }
        }

        return response()->json([
            'message' => 'Temp order saved successfully.',
            'status_code' => 200,
            'data' => [
                'customer_details' => [
                    'name' => $party->name,
                    'email' => $party->email,
                    'mobile_no' => $party->mobile_no,
                    'city' => $party->city,
                    'gst_no' => $party->gst_no,
                    'address' => $party->address,
                    'order_date' => $tempOrder->order_date,
                ],
                'products' => $productDetails,
            ],
        ]);
    }




    public function new_offer_form(Request $request)
    {
        $search = $request->input('search', '');
        // Load and paginate products with the required fields, applying search filter if present
        $products = Product::with([
            'category' => function ($query) {
                $query->select('id', 'name'); // Only load 'id' and 'name' from categories
            }
        ])->where(function ($query) use ($search) {
            // Apply search condition for product name and category name
            $query->where('p_name', 'like', "%$search%")
                ->orWhereHas('category', function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%");
                });
        })
            ->where('status', "Active")
            ->paginate(10); // Paginate products with 10 items per page

        // Transform the products to include the category name directly
        $products->transform(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->p_name,
                'category_name' => $product->category ? $product->category->name : null,
                'image' => $product->image,
                'thumb' => $product->thumb,
                'price' => $product->price,
                'qty' => $product->qty,
                'category_id' => $product->category_id,
            ];
        });

        $parties = Party::all();

        // Pass paginated products and categories to the view
        return view('admin.order.new_offer_form', compact('products', 'parties'));
    }

    public function downloadSummaryPDF(Request $request, $orderNumber)
    {
        // Fetch all products belonging to the given order number
        $orderDetails = DB::table('temp_order_details')
            ->join('temp_orders', 'temp_order_details.temp_order_id', '=', 'temp_orders.id')
            ->join('party', 'temp_orders.party_id', '=', 'party.id')
            ->where('temp_orders.order_number', $orderNumber)
            ->select(
                'temp_order_details.product_data', // JSON product details
                'temp_orders.order_number', // Order number
                'temp_orders.packing_bag',

                'temp_orders.order_date', // Order date
                'party.*'
            )
            ->get();

        // If no orders are found, return a 404 error
        if ($orderDetails->isEmpty()) {
            abort(404, 'No orders found for the given order number.');
        }

        // Extract product details and group them into an array
        $products = [];
        foreach ($orderDetails as $detail) {
            if (!empty($detail->product_data)) {
                $productData = json_decode($detail->product_data, true); // Decode JSON product_data into an array
                if (is_array($productData)) {
                    $products[] = $productData; // Add product details to the array
                }
            }
        }

        // Get order-level details (e.g., party details) from the first record
        $order = $orderDetails->first();
        $order->products = $products; // Add all products to the order object
        // dd($products);


        $orderArray = json_decode(json_encode($order), true);

        // dd($orderArray);


        // Prepare data for the PDF (summary version without images)
        $pdfData = [
            'order' => $order,       // Order details
            'products' => $products  // Merged product data
        ];

        return view('admin.order.pdf_summary', compact('orderArray', 'products'));

    }
    public function downloadSummaryPDFIMages(Request $request, $orderNumber)
    {
        // Fetch all products belonging to the given order number
        $orderDetails = DB::table('temp_order_details')
            ->join('temp_orders', 'temp_order_details.temp_order_id', '=', 'temp_orders.id')
            ->join('party', 'temp_orders.party_id', '=', 'party.id')
            ->where('temp_orders.order_number', $orderNumber)
            ->select(
                'temp_order_details.product_data', // JSON product details
                'temp_orders.order_number', // Order number
                'temp_orders.packing_bag',

                'temp_orders.order_date', // Order date
                'party.*'
            )
            ->get();

        // If no orders are found, return a 404 error
        if ($orderDetails->isEmpty()) {
            abort(404, 'No orders found for the given order number.');
        }

        // Extract product details and group them into an array
        $products = [];
        foreach ($orderDetails as $detail) {
            if (!empty($detail->product_data)) {
                $productData = json_decode($detail->product_data, true); // Decode JSON product_data into an array
                if (is_array($productData)) {
                    $products[] = $productData; // Add product details to the array
                }
            }
        }

        // Get order-level details (e.g., party details) from the first record
        $order = $orderDetails->first();
        $order->products = $products; // Add all products to the order object
        // dd($products);


        $orderArray = json_decode(json_encode($order), true);

        // dd($orderArray);


        // Prepare data for the PDF (summary version without images)
        $pdfData = [
            'order' => $order,       // Order details
            'products' => $products  // Merged product data
        ];

        return view('admin.order.pdf_summary_image', compact('orderArray', 'products'));

    }

    public function downloadFullPDF(Request $request, $orderNumber)
    {
        // Fetch all products belonging to the given order number
        $orderDetails = DB::table('temp_order_details')
            ->join('temp_orders', 'temp_order_details.temp_order_id', '=', 'temp_orders.id')
            ->join('party', 'temp_orders.party_id', '=', 'party.id')
            ->where('temp_orders.order_number', $orderNumber)
            ->select(
                'temp_order_details.product_data', // JSON product details
                'temp_orders.order_number', // Order number
                'temp_orders.order_date', // Order date
                'party.name as party_name', // Party name
                'party.email as party_email', // Party email
                'party.mobile_no as party_mobile_no', // Party mobile number
                'party.address as party_address', // Party address
                'party.city as party_city', // Party city
                'party.gst_no as party_gst_no' // Party GST number
            )
            ->get();

        // If no orders are found, return a 404 error
        if ($orderDetails->isEmpty()) {
            abort(404, 'No orders found for the given order number.');
        }

        // Extract product details and group them into an array
        $products = [];
        foreach ($orderDetails as $detail) {
            if (!empty($detail->product_data)) {
                $productData = json_decode($detail->product_data, true); // Decode JSON product_data into an array
                if (is_array($productData)) {
                    $products[] = $productData; // Add product details to the array
                }
            }
        }

        // Get order-level details (e.g., party details) from the first record
        $order = $orderDetails->first();
        $order->products = $products; // Add all products to the order object

        // Prepare data for the Full PDF (with more details like images, etc.)
        $pdfData = [
            'order' => $order,        // Order details
            'products' => $products   // Merged product data
        ];

        // Load the view for the full PDF and generate
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.order.pdf_full', $pdfData);

        // Return the generated Full PDF as a download
        return $pdf->download('order_full_' . $order->order_number . '.pdf');
    }

    public function editProduct($orderNumber, $productId)
    {
        // dd($productId);
        // Retrieve the order with the specific product
        $order = TempOrderDetail::where('temp_order_number', $orderNumber)->where('product_id', $productId)->firstOrFail();
        // dd($order);

        // Retrieve the specific product
        $product = Product::find($productId);
        // dd($product);

        // Check if the product exists
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found in this order.');
        }

        // Pass both order and product to the view
        return view('admin.order.edit_product', compact('order', 'product'));
    }


    // Update the product details
    public function updateProduct(Request $request, $orderNumber, $productId)
    {
        // dd($productId);
        // Validate the input
        $request->validate([
            'quantity' => 'required|numeric|min:1',
            'remark' => 'nullable|string',
        ]);
        // convert qty into integer
        // dd($request->all());
        $qty = (int) $request->quantity;
        // dd($qty);


        // Find the TempOrderDetail by order number
        $tempOrder = TempOrderDetail::where('temp_order_number', $orderNumber)->where('product_id', $productId)->firstOrFail();

        // Decode the product_data JSON
        $productData = json_decode($tempOrder->product_data, true);
        // dd($productData);

        // Ensure that product_data is an array and check if it has the expected structure
        if (!is_array($productData)) {
            return redirect()->back()->with('error', 'Invalid product data format.');
        }
        // dd($productData);

        // // Check if the product_id exists in the product data
        // if ($productData['product_id'] != $productId) {
        //     return redirect()->back()->with('error', 'Product not found in this order.');
        // }
        // Update the necessary fields: buyqty, price, remark
        $productData['buyQty'] = $qty;
        $productData['remark'] = $request->input('remark');

        // dd($productData);

        // Update the TempOrderDetail's product_data with the modified product data
        $tempOrder->product_data = json_encode($productData);

        // Optionally update other fields like buyqty or remark in TempOrderDetail
        $tempOrder->buyqty = $request->input('quantity');
        $tempOrder->remark = $request->input('remark');

        // Save the changes
        $tempOrder->save();

        return redirect()->route('order.offer_form_detail', $orderNumber)
            ->with('success', 'Product updated successfully.');
    }

    public function deleteProduct($orderNumber, $productId)
    {
        // Find the TempOrderDetail by order number
        $tempOrder = TempOrderDetail::where('temp_order_number', $orderNumber)->where('product_id', $productId)->firstOrFail();

        // Decode the product_data JSON
        $productData = json_decode($tempOrder->product_data, true);

        // Ensure that product_data is an array
        if (!is_array($productData)) {
            return redirect()->back()->with('error', 'Invalid product data format.');
        }

        // Check if the product_id matches and remove the product
        if ($productData['product_id'] == $productId) {
            $tempOrder->delete(); // Optional: Adjust this logic to remove the product correctly
        } else {
            return redirect()->back()->with('error', 'Product not found in this order.');
        }

        // Redirect with success message
        return redirect()->route('order.offer_form_detail', $orderNumber)
            ->with('success', 'Product deleted successfully.');
    }




}
