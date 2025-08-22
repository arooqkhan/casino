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

                    <button type="submit" class="btn btn-primary">Save Bonus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
