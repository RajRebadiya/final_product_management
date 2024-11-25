<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\WebpEncoder;



class HomeController extends Controller
{
    
    
    //
    public function dashboard()
    {
        // Paginate categories with 10 items per page
        $categories = Category::paginate(8);

        // Load and paginate products with the required fields
        $products = Product::with(['category' => function ($query) {
            $query->select('id', 'name'); // Only load 'id' and 'name' from categories
        }])->paginate(8); // Paginate products with 10 items per page

        // Transform the products to include the category name directly
        $products->getCollection()->transform(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->p_name,
                'category_name' => $product->category ? $product->category->name : null,
                'image' => $product->image,
                // 'description' => $product->description,
                // 'price' => $product->price,
                'category_id' => $product->category_id,
                'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        // Pass paginated products and categories to the view
        return view('admin.product.dashboard', compact('categories', 'products'));
    }
  public function dashboard_2(Request $request)
        {
            // Get search term from the request (default to empty if not present)
            $search = $request->input('search', '');
        
            // Paginate categories with 10 items per page
            $categories = Category::orderBy('name', 'asc')->get();
        
            // Load and paginate products with the required fields, applying search filter if present
            $products = Product::with(['category' => function ($query) {
                $query->select('id', 'name'); // Only load 'id' and 'name' from categories
            }])
            ->where(function ($query) use ($search) {
                // Apply search condition for product name and category name
                $query->where('p_name', 'like', "%$search%")
                      ->orWhereHas('category', function ($query) use ($search) {
                          $query->where('name', 'like', "%$search%");
                      });
            })
            ->paginate(10); // Paginate products with 10 items per page
        
            // Transform the products to include the category name directly
            $products->transform(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->p_name,
                    'category_name' => $product->category ? $product->category->name : null,
                    'image' => $product->image,
                    'status' => $product->status,
                    'thumb'=> $product->thumb,
                    'stock_status' => $product->stock_status,
                    'price' => $product->price,
                    'category_id' => $product->category_id,
                    'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
                ];
            });
        
            // Pass paginated products and categories to the view
            return view('admin.product.dashboard_2', compact('products', 'categories'));
        }


    public function category()
    {
        // Paginate categories with 10 items per page
        $categories = Category::all();
        // dd($categories);

        // Pass paginated products and categories to the view
        return view('admin.category.category', compact('categories'));
    }

    public function add_product(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'p_name' => 'required|string|max:255|unique:products,p_name',
        'stock_status' => 'required|string|max:255',
        'image' => 'required|image',
        'price' => 'required|numeric|min:0',
    ]);

    // Find the category
    $category = Category::findOrFail($request->input('category_id'));
    $category_name = $category->name;

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
             $thumbimageName = $category_name . '_' . $originalName . '_' . $thumbCounter . '_thumbnail' .  '.webp';
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
    $product->stock_status = $request->input('stock_status');
    $product->price = $request->input('price');
    $product->category_name = $category_name;
    $product->save();

    return redirect()->back()->with('success', 'Product added successfully.');
}


    public function add_category(Request $request)
    {

        $category = Category::all();
        return view('admin.product.add_category', compact('category'));
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

    public function search_products(Request $request)
    {
        $query = $request->input('query');

        // Retrieve products matching the query, along with the related category
        $products = Product::with('category') // Eager load the category relationship
            ->where('p_name', 'LIKE', "%{$query}%")
            ->get();

        // Map the products to include category name
        $products = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'category_id' => $product->category_id,
                'p_name' => $product->p_name,
                'image' => $product->image,
                'category_name' => $product->category ? $product->category->name : null, // Accessing category name
                'stock_status' => $product->stock_status,
                'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        // Return the products as JSON
        return response()->json($products);
    }

    public function delete($id)
    {
        $data = Product::find($id);
        $data->status = 'Inactive';
        $data->save();
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

        // Your code to handle the edit view, passing the product data to the view
        return view('admin.product.edit_product', compact('product'));
    }

    // public function edit_category(Request $request){
    //     $categoryId = $request->input('category_id');
    //     $category = Category::find($categoryId);

    //     // Your code to handle the edit view, passing the product data to the view
    //     return view('admin.category.edit_category', compact('category'));
    // }

    public function update(Request $request)
    {

        // dd($request->all());

        $request->validate([
            'id' => 'required|exists:products,id',
            'p_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock_status' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
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
        //  $imageIntervention = \Intervention\Image\Facades\Image::make($image->getRealPath());

     if ($request->hasFile('image')) {
    $image = $request->file('image');
    $originalName = pathinfo($request->p_name, PATHINFO_FILENAME); // Get the base name without extension
    $extension = $image->getClientOriginalExtension(); // Get the extension
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
        $thumbimageName = $category_name . '_' . $originalName . '_' . $thumbCounter . '_thumbnail' .  '.webp';
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


        $product->p_name = $request->input('p_name');
        $product->category_id = $request->input('category_id');
        $product->stock_status = $request->input('stock_status');
        $product->image = $imageName ?? $product->image;
        $product->thumb = $thumbimageName ?? $product->image;
        $product->status = $product->status;
        $product->sync = 0;
        $product->category_name = $category_name;
        $product->price = $request->input('price');
        $product->save();
        // dd('fine');
        return redirect('dashboard_2')->with('success', 'Product updated successfully.');
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
        $cart->product_id =  $product->id; // Store the product ID as a string
        $cart->p_name = $product->p_name; // Store the product name
        $cart->category_name = $product->category_name; // Store the category name
        $cart->save(); // Save the cart entry
    
        // Redirect to the cart page with success message
        return redirect()->route('dashboard_2')->with('success', 'Product added to cart.');
    }


    public function cart()
    {
        // Get the cart_id from the session
        $cartId = session('cart_id');

        // Check if cart_id exists in session
        if (!$cartId) {
            return redirect()->route('cart')->with('error', 'No cart found.');
        }

        // Retrieve the products using the cart_id
        $products = Cart::where('cart_id', $cartId)->get();
        // dd($products);

        return view('admin.product.cart_product', compact('products'));
    }

    public function clearCart(Request $request)
    {

        // Get the cart_id from the session
        $cartId = session('cart_id');

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
        // Retrieve the cart_id from the session
        $cartId = session('cart_id');

        // If there is no cart_id in the session, return an error message
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
                 // Paginate categories with 10 items per page
            $categories = Category::orderBy('name', 'asc')->get();
                // Retrieve the filter parameter from the query string
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
                }
                elseif ($filter === 'latest_updated') {
                    $query->orderBy('updated_at', 'desc');
                }
            
                // Execute the query and fetch filtered products
                $products = $query->paginate(10); // You can change the pagination value here
                
                 $products->transform(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->p_name,
                    'category_name' => $product->category ? $product->category->name : null,
                    'image' => $product->image,
                    'status' => $product->status,
                    'thumb'=> $product->thumb,
                    'stock_status' => $product->stock_status,
                    'price' => $product->price,
                    'category_id' => $product->category_id,
                    'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
                ];
            });
                // dd($products);
            
                return view('admin.product.dashboard_2', compact('products' , 'categories'));
            }

}
