@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit User'])
    <div class="container-fluid py-4">
        <div class="row mt-4">
            <div class="col-lg-12 mb-lg-0 mb-4">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Edit User</h6>
                    </div>
                    <div class="card-body p-3">
                        <form role="form" method="POST" action={{ route('user.update', ['user' => $data['id']]) }}
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Name <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="name"
                                            value="{{ $data['name'] }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" type="email" name="email"
                                            value="{{ $data['email'] }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Role <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="role" required>
                                            <option selected>Select Role</option>
                                            <option @if ($data['role'] == 'admin') selected @endif value="admin">Admin
                                            </option>
                                            <option @if ($data['role'] == 'user') selected @endif value="user">User
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end mt-2">
                                <a href="{{ route('user.index') }}" class="btn btn-secondary btn-md">Back</a>
                                <button type="submit" class="btn btn-primary btn-md ms-auto">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

@push('js')
    <script></script>
@endpush
