<!-- resources/views/orders/list.blade.php -->
@extends('admin.layout.template')

@section('content')
    <div class="container mt-4">
        <h2 class="text-center mb-4">Offer Form List</h2>

        <!-- Table to list orders -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order Number</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Customer Phone</th>
                    <th>Order Date</th>
                    {{-- <th>Status</th> --}}
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- @dd($orders); --}}
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->party_name }}</td>
                        <td>{{ $order->party_email }}</td>
                        <td>{{ $order->party_mobile_no }}</td>
                        <td>{{ $order->order_date }}</td>
                        {{-- <td>
                            <span class="badge badge-{{ $order->status == 'completed' ? 'success' : 'warning' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td> --}}
                        <td>

                            <a href="{{ route('offer_form_detail', $order->order_number) }}" class="btn btn-info btn-sm">View
                                Details</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
