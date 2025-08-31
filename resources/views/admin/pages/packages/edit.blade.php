@extends('admin.master.main')

@section('content')
<div class="row">
    <div class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="widget-header">
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center mb-2">
                    <h3>Edit Bonus</h3>
                    <a href="{{ route('bonus.index') }}" class="btn btn-success">Back</a>
                </div>
            </div>
        </div>

        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <form action="{{ route('packages.update', $package->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <label for="name">Package Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name', $package->name) }}" required>
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="price">Price ($)</label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control"
                                value="{{ old('price', $package->price) }}" required>
                            @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <label for="icon">Icon (Image)</label>
                            <input type="file" name="icon" id="icon" class="form-control">
                            @if($package->icon)
                            <p class="mt-2">
                                <img src="{{ asset($package->icon) }}" alt="icon" width="50">
                            </p>
                            @endif
                            @error('icon') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="credit">Credit</label>
                            <input type="number" name="credit" id="credit" class="form-control"
                                value="{{ old('credit', $package->credit) }}" required>
                            @error('credit') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Package</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection