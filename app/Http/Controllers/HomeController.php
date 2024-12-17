<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use Illuminate\Support\Str;
use App\Models\ProductColor;
use App\Models\Color;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Log;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\WebpEncoder;



class HomeController extends Controller
{


    //
    public function dashboard()
    {

        // Paginate categories with 10 items per page
        $categories = Category::all()->count();
        $products = Product::all()->count();
        // Pass paginated products and categories to the view
        return view('admin.product.dashboard', compact('categories', 'products'));
    }
    public function dashboard_2(Request $request)
    {
        // Get search term from the request (default to empty if not present)
        $search = $request->input('search', '');
        $colors = Color::all();

        // Paginate categories with 10 items per page
        $categories = Category::orderBy('name', 'asc')->get();

        // Load and paginate products with the required fields, applying search filter if present
        $products = Product::with([
            'category' => function ($query) {
                $query->select('id', 'name'); // Only load 'id' and 'name' from categories
            }
        ])
            ->where(function ($query) use ($search) {
                // Apply search condition for product name and category name
                $query->where('p_name', 'like', "%$search")
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
                'status' => $product->status,
                'thumb' => $product->thumb,
                'stock_status' => $product->stock_status,
                'price' => $product->price,
                'qty' => $product->qty,
                'category_id' => $product->category_id,
                'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
            ];
        });
        $lastProduct = Product::latest('id')->first(); // Fetch the last product

        // Pass paginated products and categories to the view
        return view('admin.product.dashboard_2', compact('products', 'categories', 'colors', 'lastProduct'));
    }
    public function barcode(Request $request)
    {
        // Get search term from the request (default to empty if not present)
        $search = $request->input('search', '');
        $colors = Color::all();

        // Paginate categories with 10 items per page
        $categories = Category::orderBy('name', 'asc')->get();

        // Load and paginate products with the required fields, applying search filter if present
        $products = Product::with([
            'category' => function ($query) {
                $query->select('id', 'name'); // Only load 'id' and 'name' from categories
            }
        ])
            ->where(function ($query) use ($search) {
                // Apply search condition for product name and category name
                $query->where('p_name', 'like', "%$search")
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
                'status' => $product->status,
                'thumb' => $product->thumb,
                'stock_status' => $product->stock_status,
                'price' => $product->price,
                'qty' => $product->qty,
                'category_id' => $product->category_id,
                'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
            ];
        });
        $lastProduct = Product::latest('id')->first(); // Fetch the last product

        // Pass paginated products and categories to the view
        return view('admin.barcode.dashboard_2', compact('products', 'categories', 'colors', 'lastProduct'));
    }


    public function category(Request $request)
    {
        // Paginate categories with 10 items per page
        $search = $request->input('search', '');
        $categories = Category::where('name', 'LIKE', "{$search}%")->where('status', 'Active')->orderBy('name', 'asc')->get();

        // dd($categories);

        // Pass paginated products and categories to the view
        return view('admin.category.category', compact('categories'));
    }

    public function add_product(Request $request)
    {
        // dd($request->all());
        // Validate the incoming request data
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'p_name' => 'required|string|max:255',
            'stock_status' => 'required|string|max:255',
            'image' => 'required|image',
            'price' => 'required|numeric|min:0',
            // 'qty' => 'required|integer|min:0',
        ]);

        // Find the category
        $category = Category::findOrFail($request->input('category_id'));
        $category_name = $category->name;
        $product_name = $request->input('p_name');
        $Alredy_product_name = Product::where('p_name', $request->input('p_name'))->where('category_name', $category_name)->first();
        if ($Alredy_product_name) {
            // dd($Alredy_product_name);
            return redirect()->back()->with('error', 'Product name already exists for the selected category.');
        }

        // Handle the image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $originalName = pathinfo($request->p_name, PATHINFO_FILENAME); // Get the base name without extension
            $extension = $image->getClientOriginalExtension(); // Get the file extension

            // Define paths for main image and thumbnail
            $destinationPath = public_path('storage/images/' . $category_name);
            $thumbdestinationPath = public_path('storage/thumbnail/' . $category_name);

            // Ensure directories exist
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            if (!file_exists($thumbdestinationPath)) {
                mkdir($thumbdestinationPath, 0777, true);
            }

            // Generate unique names for the files
            $imageName = $category_name . '_' . $originalName . '.webp';
            $thumbImageName = $category_name . '_' . $originalName . '_thumbnail.webp';

            $counter = 1;

            // Ensure unique name for the main image
            while (file_exists($destinationPath . '/' . $imageName)) {
                $imageName = $category_name . '_' . $originalName . '_' . $counter . '.webp';
                $counter++;
            }

            $thumbCounter = 1;

            // Ensure unique name for the thumbnail image
            while (file_exists($thumbdestinationPath . '/' . $thumbImageName)) {
                $thumbimageName = $category_name . '_' . $originalName . '_' . $thumbCounter . '_thumbnail' . '.webp';
                $thumbCounter++;
            }

            // Move the main image to the destination path
            $image->move($destinationPath, $imageName);

            // Create a thumbnail (you may replace this with an actual image resizing logic if needed)
            $thumbnailFilePath = $thumbdestinationPath . '/' . $thumbImageName;
            copy($destinationPath . '/' . $imageName, $thumbnailFilePath);
        } else {
            return redirect()->back()->with('error', 'Product image is required.');
        }

        // Create and save the product
        $product = new Product();
        $product->p_name = $request->input('p_name');
        $product->category_id = $request->input('category_id');
        $product->image = $imageName;
        $product->thumb = $thumbImageName;
        $product->sync = 0;
        $product->qty = 0;
        $product->stock_status = $request->input('stock_status');
        $product->price = $request->input('price');
        $product->category_name = $category_name;
        $product->save();

        if ($request->has('colors')) {
            // Handle Product Colors
            $colors = $request->input('colors', []); // Default to empty array if no colors are provided
            $processedColors = []; // To track processed color names for duplicate checking within the input array

            foreach ($colors as $colorData) {
                $colorName = $colorData['color_name'] ?? $colorData['new_color'] ?? null;

                if (!$colorName) {
                    return redirect()->back()->with('error', 'Color name is required.');
                }

                // Check for duplicate within the input array
                if (in_array($colorName, $processedColors)) {
                    return redirect()->back()->with('error', 'Duplicate color name found in the input.' . $colorName);
                }

                $processedColors[] = $colorName; // Add to the list of processed colors

                // Check if the color already exists in the Color table
                $existingColor = Color::where('color_name', $colorName)->first();

                if (!$existingColor) {
                    // Only create a new entry if the color doesn't already exist
                    $color = new Color();
                    $color->color_name = $colorName;
                    $color->save();
                }

                // Add color to ProductColor
                $productColor = ProductColor::where('product_id', $product->id)
                    ->where('color_name', $colorName)
                    ->first();

                if ($productColor) {
                    // Update existing ProductColor
                    $productColor->quantity = $colorData['quantity'];
                    $productColor->save();
                } else {
                    // Create new ProductColor if it doesn't exist
                    $newProductColor = new ProductColor();
                    $newProductColor->product_id = $product->id;
                    $newProductColor->color_name = $colorName;
                    $newProductColor->quantity = $colorData['quantity'];
                    $newProductColor->save();
                }
            }
        }



        return redirect()->back()->with('success', 'Product added successfully.');
    }

    public function add_category_post(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'price' => 'required|int|min:0'
        ]);


        $category = Category::where('name', $request->name)->first();

        if ($category) {
            return redirect()->back()->with('error', 'Category already exists.');
        }



        $category = new Category();
        $category->name = $request->name;
        $category->status = 'Active';
        $category->price = $request->price;
        $category->save();
        return redirect()->back()->with('success', 'Category added successfully');
    }


    public function delete($id)
    {
        // Find the product record
        $product = Product::find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // // Paths for the images
        // $imagePath = public_path('storage/images/' . $product->category_name . '/' . $product->image); // Adjust if your path is different
        // $thumbnailPath = public_path('storage/thumbnail/' . $product->category_name . '/' . $product->thumb); // Adjust if your path is different
        // // dd($imagePath);
        // // Delete the images if they exist
        // if (file_exists($imagePath)) {
        //     unlink($imagePath);
        // }
        // if (file_exists($thumbnailPath)) {
        //     unlink($thumbnailPath);
        // }

        // Delete the product record
        $product->status = 'Inactive';
        $product->save();

        return redirect()->back()->with('success', 'Product deleted successfully.');
    }


    public function delete_category($id)
    {
        $data = Category::find($id);
        $data->status = 'Inactive';
        $data->save();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }

    public function edit(Request $request)
    {
        $productId = $request->input('product_id');
        $product = Product::find($productId);
        $colors = Color::all();

        // Your code to handle the edit view, passing the product data to the view
        return view('admin.product.edit_product', compact('product', 'colors'));
    }

    public function edit_category(Request $request)
    {
        // dd($request->all());
        $categoryId = $request->input('category_id');
        $category = Category::find($categoryId);

        // Your code to handle the edit view, passing the product data to the view
        return view('admin.category.edit_category', compact('category'));
    }

    public function update_category(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
        ]);

        $category = Category::find($request->input('id'));
        $category->price = $request->input('price');
        $category->save();
        $product = Product::where('category_name', $category->name)->get();
        foreach ($product as $item) {
            $item->price = $request->input('price');
            $item->save();
        }
        return redirect()->route('category')->with('success', 'Category updated successfully.');
        // dd($product);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'p_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock_status' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'qty' => 'required|numeric|min:0',
        ]);

        $categories = Category::find($request->category_id);

        if (!$categories) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Category not found.',
                'data' => []
            ]);
        }

        $category_name = $categories->name;

        // Image handling (No changes, keeping it as is)
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $originalName = pathinfo($request->p_name, PATHINFO_FILENAME); // Get the base name without extension
            $destinationPath = public_path('storage/images/' . $category_name);
            $thumbdestinationPath = public_path('storage/thumbnail/' . $category_name);

            $imageName = $category_name . '_' . $originalName . '.webp'; // Default name
            $thumbimageName = $category_name . '_' . $originalName . '_thumbnail.webp'; // Default thumbnail name

            $counter = 1;

            // Ensure unique name for main image
            while (file_exists($destinationPath . '/' . $imageName)) {
                $imageName = $category_name . '_' . $originalName . '_' . $counter . '.webp';
                $counter++;
            }

            $thumbCounter = 1;

            // Ensure unique name for thumbnail image
            while (file_exists($thumbdestinationPath . '/' . $thumbimageName)) {
                $thumbimageName = $category_name . '_' . $originalName . '_' . $thumbCounter . '_thumbnail' . '.webp';
                $thumbCounter++;
            }

            // Move the uploaded file to the main destination
            $image->move($destinationPath, $imageName);

            // Create a copy for the thumbnail folder with the correct name
            $thumbnailFilePath = $thumbdestinationPath . '/' . $thumbimageName;
            if (!file_exists($thumbdestinationPath)) {
                mkdir($thumbdestinationPath, 0777, true); // Ensure the directory exists
            }
            copy($destinationPath . '/' . $imageName, $thumbnailFilePath);
        }

        $productId = $request->input('id');
        $product = Product::find($productId);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Update product fields
        $product->p_name = $request->input('p_name');
        $product->category_id = $request->input('category_id');
        $product->stock_status = $request->input('stock_status');
        $product->image = $imageName ?? $product->image;
        $product->thumb = $thumbimageName ?? $product->thumb;
        $product->status = $product->status;
        $product->sync = 0;
        $product->qty = $request->input('qty');
        $product->category_name = $category_name;
        $product->price = $request->input('price');
        $product->save();

        if ($request->colors) {
            // Handle colors
            $colors = $request->input('colors', []);
            $processedColorNames = []; // To track color names for duplicates in input
            $productId = $product->id; // Assume $product is already retrieved

            // Delete removed colors
            $existingColorIds = collect($colors)->pluck('id')->filter();
            ProductColor::where('product_id', $productId)
                ->whereNotIn('id', $existingColorIds)
                ->delete();

            foreach ($colors as $color) {
                $colorName = $color['color_name'] ?? null;
                $quantity = $color['quantity'] ?? 0;

                // Check if color_name is provided
                if (!$colorName) {
                    return redirect()->back()->with('error', 'Color name is required.');
                }

                // Check for duplicates in the request
                if (in_array($colorName, $processedColorNames)) {
                    return redirect()->back()->with('error', "Duplicate color name found in input: {$colorName}");
                }
                $processedColorNames[] = $colorName;

                // Check for duplicate in database
                $existingColorQuery = ProductColor::where('product_id', $productId)
                    ->where('color_name', $colorName);

                if (isset($color['id']) && $color['id']) {
                    // Exclude the current color record being updated from duplicate check
                    $existingColorQuery->where('id', '!=', $color['id']);
                }

                if ($existingColorQuery->exists()) {
                    return redirect()->back()->with('error', "Duplicate color name found in database: {$colorName}");
                }

                // Update or create colors
                if (isset($color['id']) && $color['id']) {
                    // If 'id' is present, update the existing record
                    ProductColor::where('id', $color['id'])
                        ->update([
                            'product_id' => $productId,
                            'color_name' => $colorName,
                            'quantity' => $quantity
                        ]);
                } else {
                    // If 'id' is not present, create a new record
                    ProductColor::create([
                        'product_id' => $productId,
                        'color_name' => $colorName,
                        'quantity' => $quantity
                    ]);
                }
            }
        }




        return redirect('product')->with('success', 'Product updated successfully.');
    }


    public function updateStockStatus(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $product->stock_status = $request->stock_status;
        $product->save();

        return redirect()->back()->with('success', 'Stock status updated successfully.');
    }

    public function updateStatus(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $product->status = $request->status;
        $product->save();

        return redirect()->back()->with('success', 'Product status updated successfully.');
    }

    public function category_update_status(Request $request)
    {
        $category = Category::findOrFail($request->category_id);
        $category->status = $request->status;
        $category->save();

        return redirect()->back()->with('success', 'Category updated successfully.');
    }

    public function addToCart(Request $request)
    {
        // Validate the request to ensure 'product_id' is provided
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Retrieve the product ID from the request
        $productId = $request->input('product_id');

        // Retrieve the product details from the database
        $product = Product::find($productId);

        // Check if the product exists
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Generate a unique cart ID and store it in the session if not already set
        if (!session()->has('cart_id')) {
            session(['cart_id' => Str::random(10)]);
        }
        $cartId = session('cart_id');

        // Create a new Cart entry
        $cart = new Cart();
        $cart->cart_id = $cartId; // Use the cart ID from the session
        $cart->image = $product->image; // Store the product image
        $cart->product_id = $product->id; // Store the product ID as a string
        $cart->p_name = $product->p_name; // Store the product name
        $cart->category_name = $product->category_name; // Store the category name
        $cart->save(); // Save the cart entry

        // Redirect to the cart page with success message
        return redirect()->route('product')->with('success', 'Product added to cart.');
    }


    public function cart()
    {
        // Fetch the last cart record to get the cart_id
        $latestCart = Cart::latest()->first();

        // If there is no cart record, return an error message
        if (!$latestCart) {
            return redirect()->route('product')->with('error', 'No cart found. Please add products to your cart.');
        }

        // Get the cart_id from the latest cart record
        $cartId = $latestCart->cart_id;

        // Get all products associated with the cart_id
        $products = Cart::where('cart_id', $cartId)
            ->orderBy('created_at', 'desc') // Order by creation date to show the most recent items
            ->get();

        // Check if there are any cart items
        if ($products->isEmpty()) {
            return response()->json([
                'status_code' => 200,
                'message' => 'Your cart is empty.',
                'data' => []
            ]);
        }


        return view('admin.product.cart_product', compact('products'));
    }

    public function clearCart(Request $request)
    {

        // Fetch the last cart record to get the cart_id
        $latestCart = Cart::latest()->first();

        // If there is no cart record, return an error message
        if (!$latestCart) {
            return response()->json([
                'status_code' => 400,
                'message' => 'No cart found. Please add products to your cart.'
            ]);
        }

        // Get the cart_id from the latest cart record
        $cartId = $latestCart->cart_id;
        // Check if cart_id exists in session
        if (!$cartId) {
            return redirect()->route('cart')->with('error', 'No cart found.');
        }

        // Retrieve the products using the cart_id
        $products = Cart::where('cart_id', $cartId)->get();

        foreach ($products as $product) {
            $product->delete();
        }

        return redirect()->route('cart')->with('success', 'Cart cleared successfully.');
    }

    public function cart_detail()
    {
        // Fetch the last cart record to get the cart_id
        $latestCart = Cart::latest()->first();

        // If there is no cart record, return an error message
        if (!$latestCart) {
            return redirect()->route('product')->with('error', 'No cart found. Please add products to your cart.');
        }

        // Get the cart_id from the latest cart record
        $cartId = $latestCart->cart_id;
        // Check if cart_id exists in session
        if (!$cartId) {
            return redirect()->route('cart')->with('error', 'No cart found.');
        }

        // Retrieve cart items based on the cart_id
        $products = Cart::where('cart_id', $cartId)->get();

        // Return the cart detail view with the cart items
        return view('admin.product.cart_product', compact('products'));
    }

    public function filter(Request $request)
    {
        $colors = Color::all();
        $categories = Category::orderBy('name', 'asc')->get();
        $filter = $request->input('filter');

        // Start building the query
        $query = Product::query();

        // Apply the filter based on the selected value
        if ($filter === 'p_new_to_old') {
            $query->orderBy('created_at', 'desc');
        } elseif ($filter === 'p_old_to_new') {
            $query->orderBy('created_at', 'asc');
        } elseif ($filter === 'price_low_to_high') {
            $query->orderBy('price', 'asc');
        } elseif ($filter === 'price_high_to_low') {
            $query->orderBy('price', 'desc');
        } elseif ($filter === 'c_new_to_old') {
            $query->orderBy('category_id', 'desc');
        } elseif ($filter === 'c_old_to_new') {
            $query->orderBy('category_id', 'asc');
        } elseif ($filter === 'c_a_to_z') {
            $query->orderBy('category_name', 'asc');
        } elseif ($filter === 'c_z_to_a') {
            $query->orderBy('category_name', 'desc');
        } elseif ($filter === 'latest_updated') {
            $query->orderBy('updated_at', 'desc');
        } elseif ($filter === 'inactive_product') {
            // For inactive products
            $query->where('status', 'inactive');
        } else {
            // Default condition: Only active products
            $query->where('status', 'Active');
        }

        // Execute the query and paginate results
        $products = $query->paginate(10);

        // Transform the products (optional)
        $products->transform(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->p_name,
                'category_name' => $product->category ? $product->category->name : null,
                'image' => $product->image,
                'status' => $product->status,
                'thumb' => $product->thumb,
                'stock_status' => $product->stock_status,
                'qty' => $product->qty,
                'price' => $product->price,
                'category_id' => $product->category_id,
                'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        return view('admin.product.dashboard_2', compact('products', 'categories', 'colors'));
    }


    public function save_cart(Request $request)
    {
        // dd($request->all());
        $productIds = $request->input('product_ids', []);

        if (empty($productIds)) {
            return response()->json([
                'status_code' => 400,
                'message' => 'No products to add to the cart.'
            ]);
        }
        $cart_id = rand(1000, 9999);

        foreach ($productIds as $productId) {
            // Retrieve the product details from the database   
            $product = Product::find($productId);
            $cart = new Cart();
            $cart->product_id = $productId;
            $cart->p_name = $product->p_name;
            $cart->image = $product->image;
            $cart->category_name = $product->category->name;
            $cart->cart_id = $cart_id;
            $cart->save();
        }


        return response()->json([
            'status_code' => 200,
            'message' => 'Products added to the cart successfully!'
        ]);
    }

    public function printProduct($id)
    {
        $product = Product::findOrFail($id);
        // dd($product);

        return view('admin.product.print', compact('product'));
    }

}
