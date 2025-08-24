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
<h4 class="m-2 mt-4">Campaign</h4>

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



    <div class="statbox widget box box-shadow">
        <div class="widget-content widget-content-area">

            <a href="{{ route('campaigns.create') }}" class="btn btn-success m-2">Add Campaign</a>

            <table id="style-2" class="table style-2 dt-table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Start At</th>
                        <th>End At</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                    <tr>
                        <td>{{ $campaign->id }}</td>
                        <td>{{ $campaign->name }}</td>
                        <td>{{ $campaign->start_at ? \Carbon\Carbon::parse($campaign->start_at)->format('Y-m-d h:i') : '-' }}</td>
                        <td>{{ $campaign->end_at ? \Carbon\Carbon::parse($campaign->end_at)->format('Y-m-d h:i') : '-' }}</td>
                        <td>
                            @if($campaign->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($campaign->status == 'upcoming')
                                <span class="badge bg-primary">Upcoming</span>
                            @elseif($campaign->status == 'expired')
                                <span class="badge bg-danger">Expired</span>
                            @endif
                        </td>
                       

                        <td class="text-center">
                            <a href="{{ route('campaigns.edit', $campaign->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('campaigns.destroy', $campaign->id) }}"
                                method="POST" style="display:inline;" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm delete-btn">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No campaign records found.</td>
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
                text: "This campaign will be deleted permanently!",
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