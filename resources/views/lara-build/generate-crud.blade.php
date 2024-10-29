@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Generate CRUD'])
    <div class="container-fluid py-4">
        <div class="row mt-4">
            <div class="col-lg-12 mb-lg-0 mb-4">
                @if (session()->has('success'))
                    <div id="alert">
                        @include('components.alert')
                    </div>
                @endif
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between mb-3">
                        <h6>Generate CRUD</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Table Name
                                        </th>
                                        <th>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="model-all">
                                                <label
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                    for="model-all">Model</label>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="view-all">
                                                <label
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                    for="view-all">View</label>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="controller-all">
                                                <label
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
                                                    for="controller-all">Controller</label>
                                            </div>
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($datas) > 0)
                                        @php
                                            $count = 1;
                                        @endphp
                                        @foreach ($datas as $data)
                                            <tr>
                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0 ms-3">
                                                        {{ $count++ }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="text-sm mb-0">
                                                        {{ $data }}</p>
                                                </td>
                                                <td>
                                                    <div class="form-check ms-5">
                                                        <input class="form-check-input model" type="checkbox"
                                                            id="model-{{ $data }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check ms-5">
                                                        <input class="form-check-input view" type="checkbox"
                                                            id="view-{{ $data }}">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-check ms-5">
                                                        <input class="form-check-input controller" type="checkbox"
                                                            id="controller-{{ $data }}">
                                                    </div>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="text-danger" href="#"
                                                            onclick="generate('{{ route('lara-build.generate') }}', '{{ $data }}')"><i
                                                                class="fa fa-bolt fa-lg" aria-hidden="true"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Generate"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="align-middle text-center">
                                                <p class="text-sm font-weight-bold mb-0">There is no tables migrated.
                                                </p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
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
        $('#model-all').change(function() {
            if ($(this).is(':checked')) {
                $('.model').prop('checked', true);
            } else {
                $('.model').prop('checked', false);
            }
        });

        $('#view-all').change(function() {
            if ($(this).is(':checked')) {
                $('.view').prop('checked', true);
            } else {
                $('.view').prop('checked', false);
            }
        });

        $('#controller-all').change(function() {
            if ($(this).is(':checked')) {
                $('.controller').prop('checked', true);
            } else {
                $('.controller').prop('checked', false);
            }
        });

        function generate(url, table) {
            Swal.fire({
                title: 'Generate table CRUD?',
                text: "You will have to regenerate this table CRUD if new changes are made.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#000080',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, generate it!',
                preConfirm: (input) => {
                    return fetch(url, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                _token: "{{ csrf_token() }}",
                                table,
                                model: ($(`#model-${table}`).is(':checked')) ? $(
                                    `#model-${table}`).val() : null,
                                view: ($(`#view-${table}`).is(':checked')) ? $(
                                    `#view-${table}`).val() : null,
                                controller: ($(`#controller-${table}`).is(':checked')) ? $(
                                    `#controller-${table}`).val() : null,
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText)
                            }
                            return response.json()
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Request failed: ${error}`
                            )
                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Generating!',
                        'The table CRUD is being generated.',
                        'success'
                    )
                    Swal.showLoading();
                    setTimeout(() => {
                        Swal.close();
                        document.location.reload();
                    }, 5000);
                }
            })
        }
    </script>
@endpush
