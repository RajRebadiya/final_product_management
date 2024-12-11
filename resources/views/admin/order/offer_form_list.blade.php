@extends('admin.layout.template')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <style>
        .search-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .table-container {
            overflow-x: auto;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: #ffffff;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .table th {
            background-color: #343a40;
            color: #fff;
        }

        .btn-info {
            background-color: #17a2b8;
            border: none;
        }

        .btn-info:hover {
            background-color: #138496;
        }

        .reset-btn {
            background-color: #6c757d;
        }

        .reset-btn:hover {
            background-color: #5a6268;
        }
    </style>

    <div class="container mt-4">
        <h2 class="text-center mb-4">📋 Offer Form List</h2>

        <!-- Search Form -->
        <div class="search-card mb-4">
            <form method="GET" action="{{ route('offer_form_list') }}">
                <div class="row g-3">
                    <!-- Order Number Filter -->
                    <div class="col-md-4">
                        <input type="text" name="order_number" class="form-control" placeholder="Search by Order Number"
                            value="{{ request()->order_number }}">
                    </div>

                    <!-- Order Date Filter -->
                    <div class="col-md-4">
                        <input type="date" name="order_date" class="form-control" value="{{ request()->order_date }}">
                    </div>

                    <!-- Buttons -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">🔍 Search</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('offer_form_list') }}" class="btn btn-secondary reset-btn w-100">🔄 Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table to list orders -->
        <div class="table-container">
            <table class="table table-striped table-hover">
                <thead class="text-center">
                    <tr>
                        <th>#</th>
                        <th>Order Number</th>
                        <th>Customer Name</th>
                        <th>Customer Email</th>
                        <th>Customer Phone</th>
                        <th>Order Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $order->order_number }}</td>
                            <td>{{ $order->party_name }}</td>
                            <td>{{ $order->party_email }}</td>
                            <td>{{ $order->party_mobile_no }}</td>
                            <td class="text-center">{{ $order->order_date }}</td>
                            <td class="text-center">
                                <a href="{{ route('offer_form_detail', $order->order_number) }}"
                                    class="btn btn-info btn-sm">🔍 View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $orders->appends(['order_number' => request()->order_number, 'order_date' => request()->order_date])->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
