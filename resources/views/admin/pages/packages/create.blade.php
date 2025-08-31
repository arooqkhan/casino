@extends('admin.master.main')

@section('content')
<div class="row">
    <div class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="widget-header">
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center mb-2">
                    <h3>Create Packages</h3>
                    <a href="{{ route('packages.index') }}" class="btn btn-success">Back</a>
                </div>
            </div>
        </div>

        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <form action="{{ route('packages.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <label for="name">Package Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name') }}" required>
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="price">Price</label>
                            <input type="number" name="price" id="price" class="form-control"
                                value="{{ old('price') }}" step="0.01" required>
                            @error('price')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <label for="icon">Icon (Image)</label>
                            <input type="file" name="icon" id="icon" class="form-control">
                            @error('icon')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="credit">Credits</label>
                            <input type="number" name="credit" id="credit" class="form-control"
                                value="{{ old('credit') }}" required>
                            @error('credit')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Package</button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection