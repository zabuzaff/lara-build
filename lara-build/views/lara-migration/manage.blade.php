@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Manage Migration'])
    <div class="container-fluid py-4">
        <div class="row mt-4">
            <div class="col-lg-12 mb-lg-0 mb-4">
                @if (session()->has('success') || session()->has('error'))
                    <div id="alert">
                        @include('components.alert')
                    </div>
                @endif
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between mb-3">
                        <h6>Manage Migration</h6>
                        <div>
                            <a href="{{ route('lara-migration.create') }}"
                                class="btn btn-secondary btn-sm float-end mb-0 ms-2">Create
                                Migration</a>
                            <a href="#" id="migrate-btn" onclick="migrate('{{ route('lara-migration.migrate') }}')"
                                class="btn btn-primary btn-sm float-end mb-0 @if ($datas->isEmpty()) disabled @endif">Run
                                Migration</a>
                        </div>
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
                                            Migration Name
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Created At
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            Generated
                                        </th>
                                        <th
                                            class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!$datas->isEmpty())
                                        @foreach ($datas as $data)
                                            <tr>
                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0 ms-3">
                                                        {{ $loop->iteration + ($datas->currentPage() - 1) * $datas->perPage() }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="text-sm mb-0">
                                                        {{ $data->table_name }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm mb-0">
                                                        {{ $data->created_at }}</p>
                                                </td>
                                                <td>
                                                    @if ($data->generated_at == null)
                                                        <i class="fa fa-times ms-3" aria-hidden="true"></i>
                                                    @else
                                                        <i class="fa fa-check ms-3" aria-hidden="true"></i>
                                                    @endif
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="text-primary me-3" href="#"
                                                            onclick="generate('{{ route('lara-migration.generate') }}', {{ $data->id }})"><i
                                                                class="fa fa-cogs fa-lg" aria-hidden="true"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Generate"></i></a>
                                                        <a class="text-secondary me-3"
                                                            href="{{ route('lara-migration.edit', ['lara_migration' => $data->id]) }}"><i
                                                                class="fa fa-pencil-square-o fa-lg" aria-hidden="true"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Edit"></i></a>
                                                        <a class="text-danger" href="#"
                                                            onclick="deleteRecord('{{ route('lara-migration.destroy', ['lara_migration' => $data->id]) }}')"><i
                                                                class="fa fa-trash-o fa-lg" aria-hidden="true"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="Delete"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="align-middle text-center">
                                                <p class="text-sm font-weight-bold mb-0">There is no migration
                                                    available.
                                                </p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            <div class="px-3 pt-4">
                                {{ $datas->links() }}
                            </div>
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
        function deleteRecord(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2A2A2A',
                cancelButtonColor: '#008080',
                confirmButtonText: 'Yes, delete it!',
                preConfirm: (input) => {
                    return fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                _token: "{{ csrf_token() }}"
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
                        'Deleted!',
                        'The migration has been deleted.',
                        'success'
                    )
                    setTimeout(() => {
                        document.location.reload();
                    }, 2000);
                }
            })
        }

        function generate(url, id) {
            Swal.fire({
                title: 'Generate migration?',
                text: "You will have to regenerate this migration if new changes are made.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#2A2A2A',
                cancelButtonColor: '#008080',
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
                                id: id,
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
                        'Generated!',
                        'The migration has been generated.',
                        'success'
                    )
                    setTimeout(() => {
                        document.location.reload();
                    }, 2000);
                }
            })
        }

        function migrate(url) {
            Swal.fire({
                title: 'Run migration?',
                text: "This will only migrate:fresh the generated migration.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#2A2A2A',
                cancelButtonColor: '#008080',
                confirmButtonText: 'Yes, migrate it!',
                preConfirm: (input) => {
                    return fetch(url, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                _token: "{{ csrf_token() }}"
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
                        'Migrated!',
                        'The migration has been completed.',
                        'success'
                    )
                    setTimeout(() => {
                        document.location.reload();
                    }, 2000);
                }
            })
        }
    </script>
@endpush
