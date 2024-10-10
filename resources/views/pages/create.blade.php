@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Create User'])
    <div class="container-fluid py-4">
        <div class="row mt-4">
            <div class="col-lg-12 mb-lg-0 mb-4">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Create Example</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Input 1 <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Input 2 <span
                                            class="text-danger">*</span></label>
                                    <input class="form-control" type="email" name="email">
                                </div>
                            </div>
                        </div>
                        <div class="text-end mt-2">
                            <button onclick="history.back()" class="btn btn-secondary btn-md ms-auto">Back</button>
                            <button class="btn btn-success btn-md ms-auto">Save</button>
                        </div>
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
