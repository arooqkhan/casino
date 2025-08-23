@extends('admin.master.main')
@section('content')

<style>
    .small-swal-popup {
        width: 250px !important;
        padding: 10px !important;
    }

    .btn-circle {
        width: 36px;
        height: 36px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 5px;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<div class="col-lg-12">
    <h4 class="m-2 mt-4">TransactionHistory</h4>

    <div class="statbox widget box box-shadow">
        @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'success',
                    title: '{{ session('
                    success ') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    background: '#28a745',
                    customClass: {
                        popup: 'small-swal-popup'
                    }
                });
            });
        </script>
        @endif
        @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'error',
                    title: '{{ session('
                    error ') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    background: '#dc3545', // Error background color
                    customClass: {
                        popup: 'small-swal-popup'
                    }
                });
            });
        </script>
        @endif
        <div class="widget-content widget-content-area">



            <table id="style-2" class="table style-2 dt-table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Sent</th>
                        <th>Payment Status</th>
                        <th>Transaction Type</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaction_histories as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->user->first_name ?? 'N/A' }} {{ $transaction->user->last_name ?? '' }}</td>
                        <td>{{ $transaction->user->email ?? 'N/A' }}</td>
                        <td>{{ ucfirst($transaction->type) }}</td>
                        <td>${{ number_format($transaction->amount, 2) }}</td>
                        <td>{{ $transaction->status == 1 ? 'Completed' : 'Pending' }}</td>
                        <td>{{ $transaction->is_sent ? 'Yes' : 'No' }}</td>
                        <td>{{ ucfirst($transaction->payment_status) }}</td>
                        <td>{{ $transaction->trans_type ?? '-' }}</td>
                        <td>{{ $transaction->created_at->format('d-m-Y') }}</td>
                        <td>
                            @if($transaction->type === 'withdraw' && $transaction->status == 0)
                            <form action="{{ route('admin.transactions.approve', $transaction->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">Approve</button>
                            </form>
                            <form action="{{ route('admin.transactions.reject', $transaction->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm btn-circle" title="Reject">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                            @endif


                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">No transaction records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        $('.delete-form').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submit

            const form = this; // Reference to the form

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit the form if confirmed
                }
            });
        });
    });
</script>



@endsection