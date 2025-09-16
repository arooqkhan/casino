@extends('admin.master.main')

@section('content')
<div class="row">
    <div class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="widget-header">
            <div class="row">
                <div class="col-12 d-flex justify-content-between align-items-center mb-2">
                    <h3>Edit FAQ</h3>
                    <a href="{{ route('faq.index') }}" class="btn btn-success">Back</a>
                </div>
            </div>
        </div>

        <div class="statbox widget box box-shadow">
            <div class="widget-content widget-content-area">
                <form action="{{ route('faq.update', $faq->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Question --}}
                    <div class="row mb-4">
                        <div class="col-sm-12">
                            <label for="question">Question</label>
                            <input type="text" name="question" id="question" class="form-control"
                                value="{{ old('question', $faq->question) }}" required>
                            @error('question')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Answer --}}
                    <div class="row mb-4">
                        <div class="col-sm-12">
                            <label for="answer">Answer</label>
                            <textarea name="answer" id="answer" rows="4" class="form-control" required>{{ old('answer', $faq->answer) }}</textarea>
                            @error('answer')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="row mb-4">
                        <div class="col-sm-6">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="active" {{ old('status', $faq->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $faq->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update FAQ</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
