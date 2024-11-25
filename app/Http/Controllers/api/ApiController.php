<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    //
    public function category_data()
    {


        $search = request()->search;


        $categories = Category::where('name', 'LIKE', "{$search}%")->where('status' , 'Active')->orderBy('name', 'asc')->get();
        // dd($categories);

        // dd($categories);


        // Add category name directly to each product
        $categories = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'price' => $category->price
            ];
        });

        return response()->json([
            'status_code' => 200,
            'message' => 'Category successfully loaded',
            'data' => $categories
        ]);
    }

    public function product_data()
    {
        // Load products with only the required fields from category
        $products = Product::with(['category' => function ($query) {
            $query->select('id', 'name'); // Only load 'id' and 'name' from categories
        }])->get();

        // Add category name directly to each product
        $products = $products->map(function ($product) {
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

        return response()->json([
            'status_code' => 200,
            'message' => 'Product successfully loaded',
            'data' => $products
        ]);
    }

    // public function search_products(Request $request)
    // {
    //     $rules = [
    //         'input' => 'required',
    //     ];

    //     // Validate the incoming request
    //     $validator = Validator::make($request->all(), $rules);

    //     // Check if validation fails
    //     if ($validator->fails()) {
    //         $errors = $validator->errors()->all();
    //         $errorMessage = implode(' ', $errors);
    //         return response()->json([
    //             'status_code' => 400,
    //             'message' => $errorMessage,
    //             'data' => []
    //         ]);
    //     }

    //     $input = $request->input;
    //     $products = Product::where('p_name', 'LIKE', "%{$input}%")->get();

    //     // Add category name directly to each product
    //     $products = $products->map(function ($product) {
    //         return [
    //             'id' => $product->id,
    //             'name' => $product->p_name,
    //             'category_name' => $product->category ? $product->category->name : null,
    //             'image' => $product->image,
    //             // 'description' => $product->description,
    //             // 'price' => $product->price,
    //             'category_id' => $product->category_id,
    //             'created_at' => $product->created_at->format('Y-m-d H:i:s'),
    //             'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
    //         ];
    //     });

    //     return response()->json([
    //         'status_code' => 200,
    //         'message' => 'Product successfully loaded',
    //         'data' => $products
    //     ]);
    // }

    public function product_add(Request $request)
    {
        // Define validation rules (remove unique rule from p_name)
        $rules = [
            'p_name' => 'required|string|max:255',
            'stock_status' => 'required',
            'image' => 'required',
            'category_id' => 'required',
            'category_name' => 'required',
            'price'=>'required',
            'thumb'=>'required'
        ];

        $messages = [
            'p_name.required' => 'Product name is required.',
            'p_name.string' => 'Product name must be a string.',
            'p_name.max' => 'Product name should not exceed 255 characters.',
        ];

        $categories = Category::find($request->category_id);

        if (!$categories) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Category not found.',
                'data' => []
            ]);
        }

        $category_name = $categories->name;
        $prefixedProductName = $request->p_name;

        // Custom validation to check if prefixed product name already exists
        if (Product::where('p_name', $prefixedProductName)->where('category_id', $request->category_id)->exists()) {
            return response()->json([
                'status_code' => 400,
                'message' => 'This Product Name already exists.',
                'data' => []
            ]);
        }

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

        // Handle the image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
             $imageExtansion = $image->getClientOriginalExtension();
           $destinationPath = public_path('storage/images/' . $category_name);
            $image->move($destinationPath, $imageName);
        } else {
            return redirect()->back()->with('error', 'Product image is required.');
        }
        
        
          if ($request->hasFile('thumb')) {
            $thumbimage = $request->file('thumb');
            $thumbimageName = $thumbimage->getClientOriginalName();
             $thumbimageExtansion = $thumbimage->getClientOriginalExtension();
           $destinationPath = public_path('storage/thumbnail/' . $category_name);
            $thumbimage->move($destinationPath, $thumbimageName);
        } else {
            return redirect()->back()->with('error', 'Product thumb image is required.');
        }

        // Save the product
        $product = new Product();
        $product->p_name = $request->p_name;
        $product->stock_status = $request->stock_status;
        $product->image = $imageName;
        $product->thumb = $thumbimageName;
        $product->category_id = $request->category_id;
        $product->category_name = $request->category_name;
        $product->status = 'Active';
        $product->sync =1;
        $product->price = $request->price;
        $product->save();

        return response()->json([
            'status_code' => 200,
            'message' => 'Product added successfully',
            'data' => [$product]
        ]);
    }


    public function category_add(Request $request)
    {
        // dd($request->all());

        $rules = [
            'name' => 'required|string|max:255|unique:categories,name',
            'price' => 'required|int|min:0'
        ];

        $message = [
            'name.required' => 'Category is alredy exists.',
            'name.unique' => 'This Category is already exists.',

        ];


        // Validate the incoming request
        $validator = Validator::make($request->all(), $rules, $message);

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

        $category = new Category();
        $category->name = $request->name;
        $category->status = 'Active';
        $category->price = $request->price;
        $category->save();
        // dd('fine');

        return response()->json([
            'status_code' => 200,
            'message' => 'Category add succesfully',
            'data' => [$category]
        ]);
    }

    public function all_products_with_pagination(Request $request)
    {
        // dd($request->all());
        $rules = [
            'limit' => 'required|integer',
            'page' => 'required|integer',
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

        $search = $request->search;
        $limit = $request->limit;
        $page = $request->page;

        $productsQuery = Product::where(function ($query) use ($search) {
            $query->where('p_name', 'LIKE', "{$search}%")
                ->orWhere('category_name', 'LIKE', "{$search}%");
        })->where('status', 'Active')
            ->orderBy('category_name', 'asc');



        // Paginate the query
        $products = $productsQuery->paginate($limit, ['*'], 'page', $page);






        // Add category name directly to each product
        $products->getCollection()->transform(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->p_name,
                'category_name' => $product->category ? $product->category->name : null,
                'image' => $product->image,
                'thumb'=>$product->thumb,
                'price'=>$product->price,
                'stock_status' => $product->stock_status,
                'category_id' => $product->category_id,
                'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        // Return a simplified response with only the data and essential pagination details
        return response()->json([
            'status_code' => 200,
            'message' => 'Products successfully loaded',
            'data' => $products->items(),
            

        ]);
    }

    public function all_category_with_pagination(Request $request)
    {
        // dd($request->all());
        $rules = [
            'limit' => 'required|integer',
            'page' => 'required|integer',
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

        $search = $request->search;
        $limit = $request->limit;
        $page = $request->page;


        // Use page and limit for pagination
        $categoryQuery = Category::where('name', 'LIKE', "{$search}%")->where('status', 'Active')->orderBy('name', 'asc');
        
        
     // Count total matching categories
    $totalCount = $categoryQuery->count();



        // Paginate the query
        $categorys = $categoryQuery->paginate($limit, ['*'], 'page', $page);



        // Add category name directly to each category
        $categorys->getCollection()->transform(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'status' => $category->status,
                'price'=>$category->price,
                'count' => $category->products()->where('status', 'Active')->count(),

                // 'created_at' => $category->created_at->format('Y-m-d H:i:s'),
                // 'updated_at' => $category->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        // Return a simplified response with only the data and essential pagination details
        return response()->json([
            'status_code' => 200,
            'message' => 'categories successfully loaded',
             'total_count' => $totalCount, // Total count of matching categories
            'data' => $categorys->items(),
            // 'count' => $categorys->products()->count(),

        ]);
    }

    public function delete_product(Request $request)
    {
        // dd($request->all());
        $rules = [
            'id' => 'required',
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
        $product = Product::find($request->id);
        if (!$product) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Product not found.',
                'data' => []
            ]);
        }
        $product->status = 'Inactive';
        $product->save();
        return response()->json([
            'status_code' => 200,
            'message' => 'Product deleted successfully',
            'data' => [$product]
        ]);
    }

    public function delete_category(Request $request)
    {
        // dd($request->all());
        $rules = [
            'id' => 'required',
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
        $category = Category::find($request->id);
        if (!$category) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Category not found.',
                'data' => []
            ]);
        }
        $category->status = 'Inactive';
        $category->save();
        return response()->json([
            'status_code' => 200,
            'message' => 'Category deleted successfully',
            'data' => [$category]
        ]);
    }

    public function edit_category(Request $request)
    {
        // dd($request->all());
        $rules = [
            'id' => 'required',
            'name' => 'required|string|max:255',
            'price' => 'required|int|min:0'
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
        $category = Category::find($request->id);
        if (!$category) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Category not found.',
                'data' => []
            ]);
        }
        $category->name = $request->name;
        $category->price = $request->price;
        $category->save();
        return response()->json([
            'status_code' => 200,
            'message' => 'Category updated successfully',
            'data' => [$category]
        ]);
    }

    public function edit_product(Request $request)
    {
        // dd($request->all());
        $rules = [
            'id' => 'required',
            'p_name' => 'required',
            'category_id' => 'required',
            
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

        $categories = Category::findOrFail($request->category_id);

        if (!$categories) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Category not found.',
                'data' => []
            ]);
        }
        $category_name = $categories->name;

        $product = Product::find($request->id);
        if (!$product) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Product not found.',
                'data' => []
            ]);
        }

          $imageName = $product->image; // Default to the current image name

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME); // Get the base name without extension
            $extension = $image->getClientOriginalExtension(); // Get the extension
            $destinationPath = public_path('storage/images/' . $category_name);
            $imageName = $originalName . '.' . $extension; // Default name
            $counter = 1;

            // Check if a file with the same name exists, and append a counter if it does
            while (file_exists($destinationPath . '/' . $imageName)) {
                $imageName = $originalName . '_' . $counter . '.' . $extension;
                $counter++;
            }

            // Move the uploaded file to the destination
            $image->move($destinationPath, $imageName);
            // $product->image = $imageName;
        }
        
          if ($request->hasFile('thumb')) {
            $thumbimage = $request->file('thumb');
            // dd()
            $thumboriginalName = pathinfo($thumbimage->getClientOriginalName(), PATHINFO_FILENAME); // Get the base name without extension
            $thumbextension = $thumbimage->getClientOriginalExtension(); // Get the extension
            $thumbdestinationPath = public_path('storage/thumbnail/' . $category_name);
            $thumbimageName = $thumboriginalName . '.' . $extension; // Default name
            $counter = 1;

            // Check if a file with the same name exists, and append a counter if it does
            while (file_exists($thumbdestinationPath . '/' . $thumbimageName)) {
                $thumbimageName = $thumboriginalName . '_' . $counter . '.' . $extension;
                $counter++;
            }

            // Move the uploaded file to the destination
            $thumbimage->move($thumbdestinationPath, $thumbimageName);
            // $product->image = $imageName;
        }


        if($request->p_name != $product->p_name){
            
            if (Product::where('p_name', $request->p_name)->exists()) {
                return response()->json([
                    'status_code' => 400,
                    'message' => 'This Product Name already exists.',
                    'data' => []
                ]);
            }
        }





        $product->p_name =  $request->p_name;
        $product->image = $imageName ?? $product->image;
        $product->thumb = $thumbimageName ?? $product->thumb;
        $product->price = $request->price;
        $product->sync = 1;
        // $product->category_id = $request->category_id;
        $product->save();

        return response()->json([
            'status_code' => 200,
            'message' => 'Product updated successfully',
            'data' => [$product]
        ]);
    }

    public function product_stock_update(Request $request)
    {
        // dd($request->all());
        $rules = [
            'id' => 'required',
            'stock_status' => 'required',
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

        $product = Product::find($request->id);
        if (!$product) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Product not found.',
                'data' => []
            ]);
        }
        $product->stock_status = $request->stock_status;
        $product->save();
        return response()->json([
            'status_code' => 200,
            'message' => 'Product stock updated successfully',
            'data' => [$product]
        ]);
    }
    
  public function new_arrival_product(Request $request)
    {
        // Validation rules
        $rules = [
            'limit' => 'required|integer',
            'page' => 'required|integer',
            'cat_date' => 'required',
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

        // Extract variables from the request
        $search = $request->search;
        $limit = $request->limit;
        $page = $request->page;
        $category_date = $request->cat_date;

        // Build the query for products
        $productsQuery = Product::where(function ($query) use ($search) {
            $query->where('p_name', 'LIKE', "{$search}%")
                ->orWhere('category_name', 'LIKE', "{$search}%");
        })
            ->where('status', 'Active')
            ->when($category_date, function ($query) use ($category_date) {
                $query->whereDate('created_at', $category_date);
                // Apply category filter if category_id is provided
                // $query->where('category_id', $category_id);
            })
            ->orderBy('id', 'desc');
            
             if(!$productsQuery->exists()){
                return response()->json([
                    'status_code' => 400,
                    'message' => 'No products found',
                    'data' => []
                ]);
            }

        // Paginate the query
        $products = $productsQuery->paginate($limit, ['*'], 'page', $page);

        // Add category name directly to each product
        $products->getCollection()->transform(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->p_name,
                'category_name' => $product->category ? $product->category->name : null,
                'image' => $product->image,
                'thumb'=>$product->thumb,
                'stock_status' => $product->stock_status,
                'price' => $product->price,
                'category_id' => $product->category_id,
                'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        // Return a simplified response with only the data and essential pagination details
        return response()->json([
            'status_code' => 200,
            'message' => 'New Arrival Products successfully loaded',
            'data' => $products->items(),
        ]);
    }
    
     public function get_product_with_cat(Request $request)
    {
        // Validation rules
        $rules = [
            'limit' => 'required|integer',
            'page' => 'required|integer',
            'cat_id' => 'required|integer',
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

        // Extract variables from the request
        $search = $request->search;
        $limit = $request->limit;
        $page = $request->page;
        $category_id = $request->cat_id;

        // Build the query for products
        $productsQuery = Product::where(function ($query) use ($search) {
            $query->where('p_name', 'LIKE', "{$search}%")
                ->orWhere('category_name', 'LIKE', "{$search}%");
        })
            ->where('status', 'Active')
            ->when($category_id, function ($query) use ($category_id) {
                // Apply category filter if category_id is provided
                $query->where('category_id', $category_id);
            })
            ->orderBy('id', 'desc');

        // Paginate the query
        $products = $productsQuery->paginate($limit, ['*'], 'page', $page);

        // Add category name directly to each product
        $products->getCollection()->transform(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->p_name,
                'category_name' => $product->category ? $product->category->name : null,
                'image' => $product->image,
                 'price' => $product->price,
                 'thumb'=>$product->thumb,
                'stock_status' => $product->stock_status,
                'category_id' => $product->category_id,
                'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        // Return a simplified response with only the data and essential pagination details
        return response()->json([
            'status_code' => 200,
            'message' => 'Products successfully loaded',
            'data' => $products->items(),
        ]);
    }
    
      public function update_product_price(Request $request)
    {   
        $rules = [
            'id' => 'required|integer',
            'price' => 'required|numeric',
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

        $product = Product::find($request->id);

        if (!$product) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Product not found.',
                'data' => []
            ]);
        }

        $product->price = $request->price;
        $product->save();
        return response()->json([
            'status_code' => 200,
            'message' => 'Product price updated successfully',
            
        ]);
    }
    
   public function product_filter(Request $request)
 {
    //   dd($request->all());
     $rules = [
         'limit' => 'numeric|min:1|max:100',
         'page' => 'numeric|min:1',
         'search' => 'nullable|string|max:255',
         'filter' => 'nullable|string'
     ];
 
     // Validate the incoming request
     $validator = Validator::make($request->all(), $rules);
 
     if ($validator->fails()) {
         $errors = $validator->errors()->all();
         $errorMessage = implode(' ', $errors);
         return response()->json([
             'status_code' => 400,
             'message' => $errorMessage,
         ]);
     }
 
     // Retrieve filter, search, limit, and page parameters
     $limit = $request->input('limit'); // Default to 10 items per page
     $page = $request->input('page'); // Default to the first page
     $search = $request->input('search'); // Search keyword
     $filter = $request->input('filter'); // Sorting/filtering option
 
       // Start building the product!uery
       $productQuery = Product::where('status', 'Active');

       // Apply search functionality
       if ($search) {
           $productsQuery->where(function ($query) use ($search) {
               $query->where('p_name', 'LIKE', "{$search}%")
                     ->orWhere('category_name', 'LIKE', "{$search}%");
           });
       }
       
 
     // Apply sorting based on the filter value
     if ($filter) {
         switch ($filter) {
             // Product filters
             case 'p_new_to_old':
                 $productQuery->orderBy('created_at', 'desc');
                 break;
             case 'p_old_to_new':
                 $productQuery->orderBy('created_at', 'asc');
                 break;
 
             // Price filters
             case 'price_low_to_high':
                 $productQuery->orderBy('price', 'asc');
                 break;
             case 'price_high_to_low':
                 $productQuery->orderBy('price', 'desc');
                 break;
 
             // Category filters
             case 'c_new_to_old':
                 $productQuery->orderBy('category_id', 'desc');
                 break;
             case 'c_old_to_new':
                 $productQuery->orderBy('category_id', 'asc');
                 break;
             case 'c_a_to_z':
                 $productQuery->orderBy('category_name', 'asc');
                 break;
             case 'c_z_to_a':
                 $productQuery->orderBy('category_name', 'desc');
                 break;
            // Latest updated product
            case 'latest_updated':
                $productQuery->orderBy('updated_at', 'desc');
                break;
     
             default:
                 return response()->json([
                     'status_code' => 400,
                     'message' => 'Invalid filter parameter.',
                 ]);
         }
     } else {
         // Default sorting if no filter is provided
         $productQuery->orderBy('category_name', 'asc');
     }
 
     // Paginate the results
     $products = $productQuery->paginate($limit, ['*'], 'page', $page);
 
     // Transform the products data
     $products->getCollection()->transform(function ($product) {
         return [
             'id' => $product->id,
             'name' => $product->p_name,
             'category_name' => $product->category ? $product->category->name : null,
             'image' => $product->image,
             'thumb' => $product->thumb,
             'price' => $product->price,
             'stock_status' => $product->stock_status,
             'category_id' => $product->category_id,
             'created_at' => $product->created_at->format('Y-m-d H:i:s'),
             'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
         ];
     });
 
     // Return the response with products and pagination details
     return response()->json([
         'status_code' => 200,
         'message' => 'Products successfully loaded',
         'data' => $products->items(), // Paginated data
     ]);
 }
    
  /* public function product_filter(Request $request)
    {
        // Retrieve the filter parameter
        $filter = $request->input('filter'); // Single key for all filters
    
        // Start building the query
        $query = Product::query();
    
        // Apply sorting based on the filter value
        if ($filter) {
            switch ($filter) {
                // Product filters
                case 'p_new_to_old':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'p_old_to_new':
                    $query->orderBy('created_at', 'asc');
                    break;
    
                // Price filters
                case 'price_low_to_high':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high_to_low':
                    $query->orderBy('price', 'desc');
                    break;
    
                // Category filters
                case 'c_new_to_old':
                    $query->orderBy('category_id', 'desc');
                    break;
                case 'c_old_to_new':
                    $query->orderBy('category_id', 'asc');
                    break;
                case 'c_a_to_z':
                    $query->orderBy('category_name', 'asc'); // Assuming `category_name` is a column or relation
                    break;
                case 'c_z_to_a':
                    $query->orderBy('category_name', 'desc');
                    break;
    
                // Default case if filter value is unrecognized
                default:
                    return response()->json([
                        'status_code' => 400,
                        'message' => 'Invalid filter parameter.',
                        'data' => []
                    ]);
            }
        } else {
            // Default sorting if no filter is provided
            $query->orderBy('category_name', 'asc');
        }
    
        // Execute the query and fetch products
        $products = $query->get();
          // Add category name and other fields directly to each product
            $products->transform(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->p_name,
                    'category_name' => $product->category ? $product->category->name : null,
                    'image' => $product->image,
                    'thumb' => $product->thumb,
                    'price' => $product->price,
                    'stock_status' => $product->stock_status,
                    'category_id' => $product->category_id,
                    'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
                ];
            });
    
        return response()->json([
            'status_code' => 200,
            'message' => 'Products successfully loaded',
            'data' => $products
        ]);
    }*/
    
        public function productSync(Request $request)
        {
            // Validation rules for 'limit' and 'page'
            $rules = [
                'limit' => 'required|integer',
                'page' => 'required|integer',
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
        
            // Get limit and page from request
            $limit = $request->limit;
            $page = $request->page;
        
            // Retrieve products where product_sync is 0
            $productsQuery = Product::where('sync', 0); // Order by created_at for consistency
        
            // Paginate the query results based on the provided 'limit' and 'page'
            $products = $productsQuery->paginate($limit, ['*'], 'page', $page);
        
            // Add category name and other fields directly to each product
            $products->getCollection()->transform(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->p_name,
                    'category_name' => $product->category ? $product->category->name : null,
                    'image' => $product->image,
                    'thumb' => $product->thumb,
                    'price' => $product->price,
                    'stock_status' => $product->stock_status,
                    'category_id' => $product->category_id,
                    'created_at' => $product->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $product->updated_at->format('Y-m-d H:i:s'),
                ];
            });
        
            // Return a response with paginated data and pagination details
            return response()->json([
                'status_code' => 200,
                'message' => 'Products successfully fetched',
                'product_count' => $products->total(),  // Total number of products
                // 'current_page' => $products->currentPage(),  // Current page number
                // 'total_pages' => $products->lastPage(),  // Total number of pages
                'data' => $products->items()  // Return the transformed collection
            ]);
        }

        
     public function updatedProductSync(Request $request)
    {
        // Validation rules
        $rules = [
            'product_id' => 'required|exists:products,id',
            'image' => 'required|image',
            'thumb' => 'required|image',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 400,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 400);
        }
    
        // Retrieve the product
        $product = Product::find($request->product_id);
    
        if (!$product) {
            return response()->json([
                'status_code' => 404,
                'message' => 'Product not found',
            ], 404);
        }
    
        // Get category name
        $category_name = $product->category_name;

    
       // Handle the image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
             $imageExtansion = $image->getClientOriginalExtension();
           $destinationPath = public_path('storage/images/' . $category_name);
            $image->move($destinationPath, $imageName);
        } else {
            return redirect()->back()->with('error', 'Product image is required.');
        }
        
        
          if ($request->hasFile('thumb')) {
            $thumbimage = $request->file('thumb');
            $thumbimageName = $thumbimage->getClientOriginalName();
             $thumbimageExtansion = $thumbimage->getClientOriginalExtension();
           $destinationPath = public_path('storage/thumbnail/' . $category_name);
            $thumbimage->move($destinationPath, $thumbimageName);
        } else {
            return redirect()->back()->with('error', 'Product thumb image is required.');
        }
        // Mark product as synced
        $product->sync = 1;
        $product->save();
        
            // Prepare success message
            $message = 'Product updated successfully.';
        
            // // Add information about deleted files if applicable
            // if ($imageDeleted) {
            //     $message .= ' Old image was deleted successfully.';
            // } else if ($request->hasFile('image')) {
            //     $message .= ' No old image to delete, new image uploaded.';
            // }
        
            // if ($thumbDeleted) {
            //     $message .= ' Old thumbnail was deleted successfully.';
            // } else if ($request->hasFile('thumb')) {
            //     $message .= ' No old thumbnail to delete, new thumbnail uploaded.';
            // }
        
            // Return success response with detailed message
            return response()->json([
                'status_code' => 200,
                'message' => $message,
                
            ]);
        }







      
}
