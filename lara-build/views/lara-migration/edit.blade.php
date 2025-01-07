@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Migration'])
    <div class="container-fluid py-4">
        <div class="row mt-4">
            <div class="col-lg-12 mb-lg-0 mb-4">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Edit Migration</h6>
                    </div>
                    <div class="card-body p-3">
                        <form role="form" method="POST"
                            action={{ route('lara-migration.update', ['lara_migration' => $data->id]) }}
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Table Name <span
                                                class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="table_name" required
                                            value="{{ $data->table_name }}">
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
                                @foreach ($data->columns as $key => $column)
                                    <div class="row column">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Column Name <span
                                                        class="text-danger">*</span></label>
                                                <input class="form-control" type="text"
                                                    name="column[{{ $key }}][name]" required
                                                    value="{{ $column->name }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Column Type <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select column-type" required
                                                    name="column[{{ $key }}][type]"
                                                    onchange="displayAdditional(this, {{ $key }})">
                                                    <option>Select Column Type</option>
                                                    <option @if ($column->type == 'string') selected @endif
                                                        value="string">String</option>
                                                    <option @if ($column->type == 'text') selected @endif
                                                        value="text">Text</option>
                                                    <option @if ($column->type == 'integer') selected @endif
                                                        value="integer">Integer</option>
                                                    <option @if ($column->type == 'foreign') selected @endif
                                                        value="foreign">Foreign ID</option>
                                                    <option @if ($column->type == 'float') selected @endif
                                                        value="float">Float</option>
                                                    <option @if ($column->type == 'boolean') selected @endif
                                                        value="boolean">Boolean</option>
                                                    <option @if ($column->type == 'date') selected @endif
                                                        value="date">Date</option>
                                                    <option @if ($column->type == 'time') selected @endif
                                                        value="time">Time</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 {{ $column->type == 'integer' ? '' : 'd-none' }}"
                                            id="integer-form-{{ $key }}">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Form</label>
                                                <br>
                                                <div class="form-check mb-3 form-check-inline pt-1">
                                                    <input class="form-check-input" type="radio" value="number"
                                                        name="column[{{ $key }}][additional_integer]"
                                                        id="customRadio1"
                                                        {{ $column->additional == 'number' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="customRadio1">Number</label>
                                                </div>
                                                <div class="form-check form-check-inline pt-1">
                                                    <input class="form-check-input" type="radio" value="select"
                                                        name="column[{{ $key }}][additional_integer]"
                                                        id="customRadio2"
                                                        {{ $column->additional == 'select' ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="customRadio2">Select</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 {{ $column->type == 'foreign' ? '' : 'd-none' }}"
                                            id="foreign-form-{{ $key }}">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Foreign Table
                                                    Name
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-select"
                                                    name="column[{{ $key }}][additional_foreign]">
                                                    <option value="">Select Foreign Table</option>
                                                    <option @if ($column->additional == 'users') selected @endif
                                                        value="users">users</option>
                                                    @foreach ($existingMigrations as $migration)
                                                        <option @if ($column->additional == $migration->table_name) selected @endif
                                                            value="{{ $migration->table_name }}">
                                                            {{ $migration->table_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <div class="form-check mt-2 pt-4">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                        name="column[{{ $key }}][is_nullable]"
                                                        @if ($column->is_nullable == '1') checked @endif>
                                                    <label class="custom-control-label">Nullable</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-1 pt-2">
                                            <button type="button"
                                                class="btn btn-danger btn-sm ms-auto mt-4 remove-column"><i
                                                    class="fa fa-trash" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <hr class="horizontal dark @if ($data->relations->count() == 0) d-none @endif">
                            <div id="relation-repeater" class="@if ($data->relations->count() == 0) d-none @endif">
                                @forelse ($data->relations as $key => $relation)
                                    <div class="row relation">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Relation Type
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-select" name="relation[{{ $key }}][type]">
                                                    <option value="">Select Relation Type</option>
                                                    <option @if ($relation->type == 'belongsTo') selected @endif
                                                        value="hasOne">Has One</option>
                                                    <option @if ($relation->type == 'hasMany') selected @endif
                                                        value="hasMany">Has Many</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Foreign Table
                                                    Name
                                                    <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text"
                                                    name="relation[{{ $key }}][foreign_table]"
                                                    value="{{ $relation->foreign_table }}">
                                            </div>
                                        </div>
                                        <div class="col-md-1 pt-2">
                                            <button type="button"
                                                class="btn btn-danger btn-sm ms-auto mt-4 remove-relation"><i
                                                    class="fa fa-trash" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="row relation">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Relation Type
                                                    <span class="text-danger">*</span></label>
                                                <select class="form-select" name="relation[0][type]">
                                                    <option value="">Select Relation Type</option>
                                                    <option value="hasOne">Has One</option>
                                                    <option value="hasMany">Has Many</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="example-text-input" class="form-control-label">Foreign Table
                                                    Name
                                                    <span class="text-danger">*</span></label>
                                                <input class="form-control" type="text"
                                                    name="relation[0][foreign_table]">
                                            </div>
                                        </div>
                                        <div class="col-md-1 pt-2">
                                            <button type="button"
                                                class="btn btn-danger btn-sm ms-auto mt-4 remove-relation"><i
                                                    class="fa fa-trash" aria-hidden="true"></i></button>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            <div class="text-end mt-2">
                                <a href="{{ route('lara-migration.index') }}" class="btn btn-secondary btn-md">Back</a>
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

        let columnIndex = parseInt("{{ $data->columns->count() }}") + 1;

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

        let relationIndex = parseInt("{{ $data->relations->count() }}");

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
