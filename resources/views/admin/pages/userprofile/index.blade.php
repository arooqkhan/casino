@extends('admin.master.main')

@section('content')
<div class="container mt-5">
  <div class="card shadow-lg p-4" style="max-width: 600px; margin: auto;">
    <h3 class="mb-4 text-center">Update Profile</h3>

    <form action="{{ route('userprofile.update') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <!-- Profile Image Top Center -->
      <div class="d-flex justify-content-center mb-4">
        <div class="position-relative" style="width: 150px; height: 150px;">
          <img id="profilePreview"
               src="{{ auth()->user()->image ? asset(auth()->user()->image) : asset('1.png') }}"
               alt="Profile Image"
               class="rounded-circle shadow img-fluid"
               style="width: 150px; height: 150px; object-fit: cover;">

          <!-- Camera Icon Overlay -->
          <label for="image"
                 class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex justify-content-center align-items-center shadow"
                 style="width: 40px; height: 40px; cursor: pointer;">
            <i class="bi bi-camera"></i>
          </label>
          <input type="file" id="image" name="image" class="d-none" accept="image/*">
        </div>
      </div>

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

      <!-- Submit Button -->
      <button type="submit" class="btn btn-primary w-100">Update Profile</button>
    </form>
  </div>
</div>

<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Live Preview Script -->
<script>
  document.getElementById('image').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (event) {
        document.getElementById('profilePreview').setAttribute('src', event.target.result);
      };
      reader.readAsDataURL(file);
    }
  });
</script>
@endsection
