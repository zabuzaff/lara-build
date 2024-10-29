@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Form Example'])
    <div class="container-fluid py-4">
        <div class="row mt-4">
            <div class="col-lg-12 mb-lg-0 mb-4">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Form Example</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Input Text</label>
                                    <input class="form-control" type="text">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Input Email</label>
                                    <input class="form-control" type="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Input Number</label>
                                    <input class="form-control" type="number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Input Date</label>
                                    <input class="form-control flatpickr" placeholder="Please select date" type="text">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Input Time</label>
                                    <input class="form-control time-flatpickr" placeholder="Please select time"
                                        type="text">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Input File</label>
                                    <input class="form-control" type="file">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Select</label>
                                    <select class="form-select">
                                        <option selected>Open this select menu</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Textarea</label>
                                    <textarea class="form-control" cols="30" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Checkbox</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1">Check this custom
                                            checkbox</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Radio</label>
                                    <br>
                                    <div class="form-check mb-3 form-check-inline">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="customRadio1">
                                        <label class="custom-control-label" for="customRadio1">Toggle this custom
                                            radio</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="flexRadioDefault"
                                            id="customRadio2">
                                        <label class="custom-control-label" for="customRadio2">Or toggle this other custom
                                            radio</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-color-input" class="form-control-label">Color</label>
                                    <input class="form-control" type="color" value="#5e72e4" id="example-color-input">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-color-input" class="form-control-label">Toggle </label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault"
                                            checked="">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">Checked
                                            switch</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <label for="example-color-input" class="form-control-label mb-3">Modal</label>
                        @include('components.modal')
                        <hr>
                        <label for="example-color-input" class="form-control-label mb-3">Tabs</label>
                        <div class="nav-wrapper position-relative end-0">
                            <ul class="nav nav-pills nav-fill p-1" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab"
                                        href="#profile-tabs-simple" role="tab" aria-controls="profile"
                                        aria-selected="true">
                                        My Profile
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#dashboard-tabs-simple"
                                        role="tab" aria-controls="dashboard" aria-selected="false">
                                        Address
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="profile-tabs-simple" role="tabpanel"
                                    aria-labelledby="home-tab">
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Enter
                                                    Name</label>
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Enter
                                                    Email</label>
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="dashboard-tabs-simple" role="tabpanel"
                                    aria-labelledby="profile-tab">
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Enter
                                                    Address</label>
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Enter
                                                    State</label>
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <label for="example-color-input" class="form-control-label mb-3">Collapse</label>
                            <br>
                            <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                Click Here
                            </button>
                            <div class="collapse mb-3" id="collapseExample">
                                <div class="card card-frame card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Input 1</label>
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Input 2</label>
                                                <input class="form-control" type="text">
                                            </div>
                                        </div>
                                    </div>
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
    <script>
    </script>
@endpush
