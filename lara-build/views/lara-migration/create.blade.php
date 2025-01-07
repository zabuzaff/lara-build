@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Create Migration'])
    <div class="container-fluid py-4">
        <div class="row mt-4">
            <div class="col-lg-12 mb-lg-0 mb-4">
                @if (session()->has('success') || session()->has('error'))
                    <div id="alert">
                        @include('components.alert')
                    </div>
                @endif
                <form role="form" method="POST" action={{ route('lara-migration.store') }} enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Create Migration</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Table Name <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="table_name" required>
                                    </div>
                                </div>
                                <div class="col-md-4 pt-2">
                                    <button id="add-column" type="button"
                                        class="btn btn-primary btn-sm ms-auto mt-4 me-2"><i class="fa fa-plus"
                                            aria-hidden="true"></i> &nbsp;Add Column</button>
                                    <button id="add-relation" type="button"
                                        class="btn btn-secondary btn-sm ms-auto mt-4"><i class="fa fa-plus"
                                            aria-hidden="true"></i> &nbsp;Add Relation</button>
                                </div>
                            </div>
                            <div id="column-repeater">
                                <div class="row column">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Column Name <span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="column[0][name]" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Column Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select column-type" required name="column[0][type]"
                                                onchange="displayAdditional(this, 0)">
                                                <option>Select Column Type</option>
                                                <option value="string">String</option>
                                                <option value="text">Text</option>
                                                <option value="integer">Integer</option>
                                                <option value="foreign">Foreign ID</option>
                                                <option value="float">Float</option>
                                                <option value="boolean">Boolean</option>
                                                <option value="date">Date</option>
                                                <option value="time">Time</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-none" id="integer-form-0">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Form</label>
                                            <br>
                                            <div class="form-check mb-3 form-check-inline pt-1">
                                                <input class="form-check-input" type="radio"
                                                    name="column[0][additional_integer]" value="number">
                                                <label class="custom-control-label">Number</label>
                                            </div>
                                            <div class="form-check form-check-inline pt-1">
                                                <input class="form-check-input" type="radio"
                                                    name="column[0][additional_integer]" value="select">
                                                <label class="custom-control-label">Select</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-none" id="foreign-form-0">
                                        <div class="form-group">
                                            <label class="form-control-label">Foreign Table Name
                                                <span class="text-danger">*</span></label>
                                            <select class="form-select" name="column[0][additional_foreign]">
                                                <option value="">Select Foreign Table</option>
                                                <option value="users">users</option>
                                                @foreach ($existingMigrations as $migration)
                                                    <option value="{{ $migration->table_name }}">
                                                        {{ $migration->table_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="form-check mt-2 pt-4">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    name="column[0][is_nullable]">
                                                <label class="custom-control-label">Nullable</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 pt-2">
                                        <button type="button" class="btn btn-danger btn-sm ms-auto mt-4 remove-column"><i
                                                class="fa fa-trash" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark d-none">
                            <div id="relation-repeater" class="d-none">
                                <div class="row relation">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Relation Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" name="relation[0][type]">
                                                <option value="">Select Relation Type</option>
                                                <option value="hasOne">Has One</option>
                                                <option value="hasMany">Has Many</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Foreign Table Name
                                                <span class="text-danger">*</span></label>
                                            <input class="form-control" type="text" name="relation[0][foreign_table]">
                                        </div>
                                    </div>
                                    <div class="col-md-1 pt-2">
                                        <button type="button"
                                            class="btn btn-danger btn-sm ms-auto mt-4 remove-relation"><i
                                                class="fa fa-trash" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-end">
                                <button type="button" onclick="history.back()"
                                    class="btn btn-secondary btn-md ms-auto">Back</button>
                                <button type="submit" class="btn btn-primary btn-md ms-auto">Save</button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth.footer')
    </div>
@endsection

@push('js')
    <script>
        function displayAdditional(elem, index) {
            if ($(elem).val() == 'integer') {
                if (!$(`#foreign-form-${index}`).hasClass('d-none')) {
                    $(`#foreign-form-${index}`).addClass('d-none');
                }
                $(`#integer-form-${index}`).removeClass('d-none');
            } else if ($(elem).val() == 'foreign') {
                if (!$(`#integer-form-${index}`).hasClass('d-none')) {
                    $(`#integer-form-${index}`).addClass('d-none');
                }
                $(`#foreign-form-${index}`).removeClass('d-none');
            } else {
                if (!$(`#integer-form-${index}`).hasClass('d-none')) {
                    $(`#integer-form-${index}`).addClass('d-none');
                } else if (!$(`#foreign-form-${index}`).hasClass('d-none')) {
                    $(`#foreign-form-${index}`).addClass('d-none');
                }
            }
        }

        let columnIndex = 1;

        $('#add-column').click(function() {
            let newColumn = $('.column').first().clone();
            newColumn.find('input, select, #foreign-form-0, #integer-form-0').each(function() {
                if ($(this).is('input') || $(this).is('select')) {
                    let name = $(this).attr('name');
                    name = name.replace(/\[\d+\]/, '[' + columnIndex + ']');
                    $(this).attr('name', name);
                }

                if ($(this).is('input[type="checkbox"]') || $(this).is('input[type="radio"]')) {
                    $(this).prop('checked', false);
                } else if ($(this).is('select[class="form-select column-type"]')) {
                    $(this).attr('onchange', `displayAdditional(this, ${columnIndex})`);
                } else if ($(this).is('div[id="foreign-form-0"]')) {
                    $(this).attr('id', `foreign-form-${columnIndex}`);
                } else if ($(this).is('div[id="integer-form-0"]')) {
                    $(this).attr('id', `integer-form-${columnIndex}`);
                } else {
                    $(this).val('');
                }
            });

            $('#column-repeater').append(newColumn);
            columnIndex++;
        });

        $(document).on('click', '.remove-column', function() {
            if ($('.column').length > 1) {
                $(this).closest('.column').remove();
            } else {
                Swal.fire(
                    'Warning!',
                    'At least one column is required.',
                    'warning'
                )
            }
        });

        let relationIndex = 0;

        $('#add-relation').click(function() {
            if (relationIndex == 0) {
                $('.horizontal').removeClass('d-none');
                $('#relation-repeater').removeClass('d-none');
                relationIndex++;
                return;
            }
            let newRelation = $('.relation').first().clone();
            newRelation.find('input, select').each(function() {
                let name = $(this).attr('name');
                name = name.replace(/\[\d+\]/, '[' + relationIndex + ']');
                $(this).attr('name', name);
                $(this).val('');
            });

            $('#relation-repeater').append(newRelation);
            relationIndex++;
        });

        $(document).on('click', '.remove-relation', function() {
            if ($('.relation').length > 1) {
                $(this).closest('.relation').remove();
            }
        });
    </script>
@endpush
