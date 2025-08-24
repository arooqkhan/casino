@extends('admin.master.main')

@section('content')
<div class="container mt-5">
  <div class="card shadow-lg p-4" style="max-width: 600px; margin: auto;">
    <h3 class="mb-4">Update Profile</h3>

    <form action="{{ route('userprofile.update') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <!-- First Name -->
      <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text"
          class="form-control"
          id="first_name"
          name="first_name"
          value="{{ old('first_name', auth()->user()->first_name) }}"
          required>
      </div>

      <!-- Last Name -->
      <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text"
          class="form-control"
          id="last_name"
          name="last_name"
          value="{{ old('last_name', auth()->user()->last_name) }}"
          required>
      </div>

      <!-- Email -->
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email"
          class="form-control"
          id="email"
          name="email"
          value="{{ old('email', auth()->user()->email) }}"
          required>
      </div>

      <!-- Profile Image -->
  <div class="mb-3">
    <label for="image" class="form-label">Profile Image</label>
    <input type="file"
        class="form-control"
        id="image"
        name="image">

    @if(auth()->user()->image)
        <div class="mt-3">
            <!-- Full Image -->
            <p><strong>Full Image:</strong></p>
            <img src="{{ asset(auth()->user()->image) }}"
                alt="Full Profile Image"
                class="img-fluid rounded shadow">
        </div>
    @endif
</div>

      <!-- Submit Button -->
      <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
  </div>
</div>
@endsection