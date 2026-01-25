@extends('layouts.dashboard')
@section('content')
        <header class="mb-3">
            <a href="#" class="burger-btn d-block d-xl-none">
                <i class="bi bi-justify fs-3"></i>
            </a>
        </header>

        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Presences</h3>
                        <p class="text-subtitle text-muted">Handle Presence data</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                <li class="breadcrumb-item" aria-current="page">Presence</li>
                                <li class="breadcrumb-item active" aria-current="page">New</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            Create
                        </h5>
                    </div>
                    <div class="card-body">

                        @if(in_array(Auth::user()->employee?->role?->title, ['Super Admin', 'HR Manager']))
                        <form action="{{ route('presences.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Employee</label>
                                <select name="employee_id" id="status" class="form-control">
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" 
                                    {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->fullname }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="check_in" class="form-label">Check In</label>
                                <input type="datetime" class="form-control datetime @error('check_in') is-invalid @enderror" name="check_in" value="{{ old('check_in') }}"
                                    required>
                                @error('check_in')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="check_out" class="form-label">Check Out</label>
                                <input type="datetime" class="form-control datetime @error('check_out') is-invalid @enderror" name="check_out" value="{{ old('check_out') }}"
                                    required>
                                @error('check_out')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="text" class="form-control date @error('date') is-invalid @enderror" name="date" value="{{ old('date') }}"
                                    required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="">Select Status</option>
                                    <option value="present" {{ old('status') == 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="absent" {{ old('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="leave" {{ old('status') == 'leave' ? 'selected' : '' }}>Leave</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <button type="submit" class="btn btn-primary mt-3">Submit</button>
                            <a href="{{ route('presences.index') }}" class="btn btn-secondary mt-3">Back to List</a>
                        </form>    

                        @else

                            <form action="{{ route('presences.store') }}" method="POST">
                                @csrf

                                <div class="mb-3"><b>Note: Please Allow The Location Access</b>
                                <br>
                                <br>
                                <div class="mb-3">
                                    <label for="latitude" class="form-label">Latitude</label>
                                    <input type="text" class="form-control"   name="latitude" id="latitude" value="{{ old('latitude') }}"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="longitude" class="form-label">Longitude</label>
                                    <input type="text" class="form-control   name="longitude" id="longitude" value="{{ old('longitude') }}" required>
                                </div>

                                <div class="mb-3">
                                    <iframe src="" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" width="500" height="300"></iframe>
                                </div>

                                <button type="submit" class="btn btn-primary mt-3" disabled id="btn-present">Present</button>
                                <a href="{{ route('presences.index') }}" class="btn btn-secondary mt-3">Back to List</a>
                            </form>

                        @endif
                    </div>
                </div>

            </section>
        </div>

       <iframe></iframe>

    <input type="hidden" id="latitude">
    <input type="hidden" id="longitude">
    <button id="btn-present" disabled>Check In</button>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const iframe = document.querySelector('iframe');
        const officeLat = -7.9037232;
        const officeLon = 112.5451389;
        const threshold = 15000;

        document.addEventListener('DOMContentLoaded', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lon;

                    const distance = getDistanceFromLatLonInMeters(lat, lon, officeLat, officeLon);

                    if (distance <= threshold) {
                        iframe.src = `https://maps.google.com/maps?q=${lat},${lon}&z=15&output=embed`;
                        Swal.fire({
                            icon: 'success',
                            title: 'Check-in Allowed',
                            text: 'You are within the allowed range to check in.',
                            confirmButtonColor: '#3085d6'
                        });
                        document.getElementById('btn-present').removeAttribute('disabled');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Check-in Not Allowed',
                            text: 'You are not within the allowed range to check in.',
                            confirmButtonColor: '#d33'
                        });
                    }
                }, (error) => {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Location Error',
                        text: 'Unable to retrieve your location: ' + error.message,
                    });
                });
            }
        });

        function getDistanceFromLatLonInMeters(lat1, lon1, lat2, lon2) {
            const R = 6371000;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }
    </script>

@endsection