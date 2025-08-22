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
    <h4 class="m-2 mt-4">Created Cards</h4>

    <div class="statbox widget box box-shadow">
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            position: 'bottom-end',
                            icon: 'success',
                            title: '{{ session('success') }}',
                            showConfirmButton: false,
                            timer: 3000,
                            toast: true,
                            background: '#28a745',
                            customClass: { popup: 'small-swal-popup' }
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
                            title: '{{ session('error') }}',
                            showConfirmButton: false,
                            timer: 3000,
                            toast: true,
                            background: '#dc3545',
                            customClass: { popup: 'small-swal-popup' }
                        });
                    });
                </script>
            @endif
            <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">

        
           <table id="style-2" class="table style-2 dt-table-hover">
   
                 <thead>
                    <tr>
                        <th>ID</th>
                        <th>Card Holder</th>
                        <th>Card Number</th>
                        <th>Expiry Date</th>
                        <th>CCV</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Province</th>
                        <th>Postal Code</th>
                        <th>City</th>
                        <th>Country</th>
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                      </thead>
           
                <tbody>
                    @forelse($createds as $card)
                        <tr>
                            <td>{{ $card->id }}</td>
                            <td>{{ $card->card_holder_name }}</td>
                            <td>{{ $card->card_number }}</td>
                            <td>{{ $card->expiry_date }}</td>
                            <td>{{ $card->ccv_code }}</td>
                            <td>{{ $card->first_name }}</td>
                            <td>{{ $card->last_name }}</td>
                            <td>{{ $card->email }}</td>
                            <td>{{ $card->province }}</td>
                            <td>{{ $card->postal_code }}</td>
                            <td>{{ $card->city }}</td>
                            <td>{{ $card->country }}</td>
                            <td>{{ $card->created_at->format('Y-m-d H:i') }}</td>

                            <td class="text-center">
                                <a href="{{ route('cards.edit', $card->id) }}" class="btn btn-primary btn-sm btn-circle">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('cards.destroy', $card->id) }}" method="POST" style="display:inline;" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-circle">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="text-center">No card records found.</td>
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
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Are you sure?',
                text: "This card will be deleted permanently!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
