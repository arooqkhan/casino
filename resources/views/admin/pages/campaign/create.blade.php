@extends('admin.master.main')

@section('content')
<div class="row">
    <div class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="widget-header">
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center mb-2">
                    <h3>Create Campaign</h3>
                    <a href="{{ route('campaigns.index') }}" class="btn btn-success">Back</a>
                </div>
            </div>
        </div>

        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <form action="{{ route('campaigns.store') }}" method="POST">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <label for="name">Campaign Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name') }}" required>
                            @error('name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="upcoming" {{ old('status')=='upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="active" {{ old('status')=='active' ? 'selected' : '' }}>Active</option>
                                <option value="expired" {{ old('status')=='expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                            @error('status')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-4">
                            <label for="start_at">Start At</label>
                            <input type="datetime-local" name="start_at" id="start_at"
                                class="form-control" value="{{ old('start_at') }}">
                            @error('start_at')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-4">
                            <label for="end_at">End At</label>
                            <input type="datetime-local" name="end_at" id="end_at"
                                class="form-control" value="{{ old('end_at') }}">
                            @error('end_at')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-sm-4">
                            <label for="winner_price">Winner Price</label>
                            <input type="number" name="winner_price" id="winner_price"
                                class="form-control" value="{{ old('winner_price') }}">
                            @error('winner_price')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-4">
                            <label for="credit">Credit</label>
                            <input type="number" name="credit" id="credit"
                                class="form-control" value="{{ old('credit') }}">
                            @error('credit')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-12">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-12">
                            <label for="terms">Bonus Terms</label>
                            <textarea name="terms" id="terms" class="form-control" rows="3">{{ old('terms') }}</textarea>
                            @error('terms')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- ✅ New Fields --}}
                    <div class="row mb-4">
                        <div class="col-sm-4">
                            <label for="color">Campaign Color</label>
                            <div class="d-flex align-items-center">
                                <input type="color" name="color" id="color" class="form-control form-control-color"
                                    value="{{ old('color', '#ff0000') }}" style="width: 60px; height: 40px; padding: 2px;">
                                <span id="color-preview" class="ms-3 px-3 py-2 rounded"
                                    style="border:1px solid #ccc; background: {{ old('color', '#ff0000') }};">
                                    {{ old('color', '#ff0000') }}
                                </span>
                            </div>
                            @error('color')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-8">
                            <label for="shadow">Shadow</label>
                            <input type="text" name="shadow" id="shadow" class="form-control"
                                value="{{ old('shadow', '0px 0px 10px 0px') }}">
                            @error('shadow')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Campaign</button>
                </form>

            </div>
        </div>
    </div>
</div>


{{-- Script --}}
<script>
    document.getElementById('color').addEventListener('input', function() {
        let color = this.value;
        let preview = document.getElementById('color-preview');
        preview.style.backgroundColor = color;
        preview.textContent = color;
    });
</script>


@endsection