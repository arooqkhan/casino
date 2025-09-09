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
                            <input type="text" placeholder="Package Name" name="name" id="name" class="form-control"
                                value="{{ old('name') }}" required>
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="price">Price</label>
                            <input type="number" placeholder="Price" name="price" id="price" class="form-control"
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
                            <input type="number" placeholder="Credits" name="credit" id="credit" class="form-control"
                                value="{{ old('credit') }}" required>
                            @error('credit')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                     {{-- ✅ New Fields --}}
    <div class="row mb-4">
        <div class="col-sm-6">
            <label for="color">Bonus Color</label>
            <div class="d-flex align-items-center">
                <input type="color" name="color" id="color" class="form-control form-control-color"
                       value="{{ old('color', '#ff0000') }}"
                       style="width: 60px; height: 40px; padding: 2px;">
                <span id="color-preview" class="ms-3 px-3 py-2 rounded"
                      style="border:1px solid #ccc; background: {{ old('color', '#ff0000') }};">
                    {{ old('color', '#ff0000') }}
                </span>
            </div>
            @error('color')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-sm-6">
            <label for="shadow">Shadow</label>
            <input type="text" name="shadow" id="shadow" class="form-control"
                   value="{{ old('shadow', '0px 0px 10px 0px') }}">
            @error('shadow')
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

<script>
    document.getElementById('color').addEventListener('input', function() {
        let color = this.value;
        let preview = document.getElementById('color-preview');
        preview.style.backgroundColor = color;
        preview.textContent = color;
    });
</script>


@endsection