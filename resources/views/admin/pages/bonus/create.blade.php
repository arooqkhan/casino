@extends('admin.master.main')

@section('content')
<div class="row">
    <div class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="widget-header">
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center mb-2">
                    <h3>Create Bonus</h3>
                    <a href="{{ route('bonus.index') }}" class="btn btn-success">Back</a>
                </div>
            </div>
        </div>

        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <form action="{{ route('bonus.store') }}" method="POST">
                    @csrf

                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <label for="bonusType">Bonus Type</label>
                            <select name="type" id="bonusType" class="form-control" required>
                                <option value="deposit_bonus" {{ old('type')=='deposit_bonus' ? 'selected' : '' }}>Deposit Bonus</option>
                                <option value="free_spins" {{ old('type')=='free_spins' ? 'selected' : '' }}>Free Spins</option>
                            </select>
                            @error('type')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="campaign">Select Campaign</label>
                            <select name="campaign_id" id="campaign" class="form-control">
                                <option value="">-- None --</option>
                                @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->id }}" {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                    {{ $campaign->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('campaign_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <label for="validFrom">Valid From</label>
                            <input type="date" name="valid_from" id="validFrom" class="form-control" value="{{ old('valid_from') }}">
                            @error('valid_from')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="validUntil">Valid Until</label>
                            <input type="date" name="valid_until" id="validUntil" class="form-control" value="{{ old('valid_until') }}">
                            @error('valid_until')
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

                      <div class="row mb-4">
        <div class="col-12">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="4" class="form-control"
                placeholder="Enter bonus description...">{{ old('description') }}</textarea>
            @error('description')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

                    <button type="submit" class="btn btn-primary">Save Bonus</button>
                </form>



            </div>
        </div>
    </div>
</div>

{{-- Script for live color preview --}}
<script>
    document.getElementById('color').addEventListener('input', function() {
        let color = this.value;
        let preview = document.getElementById('color-preview');
        preview.style.backgroundColor = color;
        preview.textContent = color;
    });
</script>
@endsection