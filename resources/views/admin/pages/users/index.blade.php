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
<h4 class="m-2 mt-4">Users</h4>

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
                    title: '{{ session('error') }}',
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
            
            <a href="{{ route('users.create') }}" class="btn btn-success m-2">Add User</a>
          
            <table id="style-2" class="table style-2 dt-table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>User Email</th>
            <th>Address</th>
            <th>DOB</th>
      
            <th class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>
                <span>
                    @if($user->image)
                    <img src="{{ asset($user->image) }}" class="rounded-circle profile-img" alt="user Image" style="width: 50px; height: 50px; margin-right: 10px;">
                    @else
                    <img src="{{ asset('images/dummy.jpg') }}" class="rounded-circle profile-img" alt="user Image" style="width: 50px; height: 50px; margin-right: 10px;">
                    @endif
                </span>
                {{ $user->first_name }} {{ $user->last_name }}
            </td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->address }}</td>
            <td>{{ $user->dob }}</td>
           
            <td class="text-center">
 
    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">
        <i class="fas fa-edit"></i>
    </a>
    
<form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;" class="delete-form">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger btn-sm delete-btn">
        <i class="fas fa-trash-alt"></i>
    </button>
</form>
    <button type="button" class="btn btn-info btn-sm view-details-btn" data-toggle="modal" data-target="#viewDetailsModal" data-user="{{ json_encode($user) }}">
        <i class="fas fa-eye"></i>
    </button>

    
   

   
  


    

</td>

        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">No user records found.</td>
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