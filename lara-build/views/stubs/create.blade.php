<?php
use Illuminate\Support\Str;

echo '@extends(\'layouts.app\', [\'class\' => \'g-sidenav-show bg-gray-100\'])' . PHP_EOL;

echo '@section(\'content\')' . PHP_EOL;
echo '    @include(\'layouts.navbars.auth.topnav\', [\'title\' => \'Create ' . Str::studly(Str::singular($data->table_name)) . '\'])' . PHP_EOL;
echo '    <div class="container-fluid py-4">' . PHP_EOL;
echo '        <div class="row mt-4">' . PHP_EOL;
echo '            <div class="col-lg-12 mb-lg-0 mb-4">' . PHP_EOL;
echo '                <div class="card mb-4">' . PHP_EOL;
echo '                    <div class="card-header pb-0">' . PHP_EOL;
echo '                        <h6>Create ' . Str::studly(Str::singular($data->table_name)) . '</h6>' . PHP_EOL;
echo '                    </div>' . PHP_EOL;
echo '                    <div class="card-body p-3">' . PHP_EOL;
echo '                        <form role="form" method="POST" action="{{ route(\'' . Str::kebab(Str::singular(trim($data->table_name))) . '.store\') }}" enctype="multipart/form-data">' . PHP_EOL;
echo '                            @csrf' . PHP_EOL;
echo '                            <div class="row">' . PHP_EOL;

foreach ($data->columns as $column) {
    if ($column->type == 'string') {
        echo '                                <div class="col-md-6">' . PHP_EOL;
        echo '                                    <div class="form-group">' . PHP_EOL;
        echo '                                        <label for="example-text-input" class="form-control-label">' . $column->name . ' <span class="text-danger">*</span></label>' . PHP_EOL;
        echo '                                        <input class="form-control" type="text" name="' . $column->name . '" required>' . PHP_EOL;
        echo '                                    </div>' . PHP_EOL;
        echo '                                </div>' . PHP_EOL;
    } elseif ($column->type == 'text') {
        echo '                                <div class="col-md-6">' . PHP_EOL;
        echo '                                    <div class="form-group">' . PHP_EOL;
        echo '                                        <label for="example-text-input" class="form-control-label">' . $column->name . ' <span class="text-danger">*</span></label>' . PHP_EOL;
        echo '                                        <textarea class="form-control" cols="30" rows="5" required name="' . $column->name . '"></textarea>' . PHP_EOL;
        echo '                                    </div>' . PHP_EOL;
        echo '                                </div>' . PHP_EOL;
    } elseif (($column->type == 'integer' && $column->additional == 'number') || $column->type == 'float') {
        echo '                                <div class="col-md-6">' . PHP_EOL;
        echo '                                    <div class="form-group">' . PHP_EOL;
        echo '                                        <label for="example-text-input" class="form-control-label">' . $column->name . ' <span class="text-danger">*</span></label>' . PHP_EOL;
        echo '                                        <input class="form-control" type="number" name="' . $column->name . '" required>' . PHP_EOL;
        echo '                                    </div>' . PHP_EOL;
        echo '                                </div>' . PHP_EOL;
    } elseif ($column->type == 'integer' && $column->additional == 'select') {
        echo '                                <div class="col-md-6">' . PHP_EOL;
        echo '                                    <div class="form-group">' . PHP_EOL;
        echo '                                        <label for="example-text-input" class="form-control-label">' . $column->name . ' <span class="text-danger">*</span></label>' . PHP_EOL;
        echo '                                        <select class="form-select" name="' . $column->name . '">' . PHP_EOL;
        echo '                                            <option selected>Open this select menu</option>' . PHP_EOL;
        echo '                                            <option value="1">One</option>' . PHP_EOL;
        echo '                                            <option value="2">Two</option>' . PHP_EOL;
        echo '                                            <option value="3">Three</option>' . PHP_EOL;
        echo '                                        </select>' . PHP_EOL;
        echo '                                    </div>' . PHP_EOL;
        echo '                                </div>' . PHP_EOL;
    } elseif ($column->type == 'boolean') {
        echo '                                <div class="col-md-6">' . PHP_EOL;
        echo '                                    <div class="form-group">' . PHP_EOL;
        echo '                                        <div class="form-check form-switch">' . PHP_EOL;
        echo '                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked="" name="' . $column->name . '">' . PHP_EOL;
        echo '                                            <label class="form-check-label" for="flexSwitchCheckDefault">' . $column->name . '</label>' . PHP_EOL;
        echo '                                        </div>' . PHP_EOL;
        echo '                                    </div>' . PHP_EOL;
        echo '                                </div>' . PHP_EOL;
    } elseif ($column->type == 'date') {
        echo '                                <div class="col-md-6">' . PHP_EOL;
        echo '                                    <div class="form-group">' . PHP_EOL;
        echo '                                        <label for="example-text-input" class="form-control-label">' . $column->name . ' <span class="text-danger">*</span></label>' . PHP_EOL;
        echo '                                        <input class="form-control flatpickr" placeholder="Please select date" name="' . $column->name . '" type="text">' . PHP_EOL;
        echo '                                    </div>' . PHP_EOL;
        echo '                                </div>' . PHP_EOL;
    } elseif ($column->type == 'time') {
        echo '                                <div class="col-md-6">' . PHP_EOL;
        echo '                                    <div class="form-group">' . PHP_EOL;
        echo '                                        <label for="example-text-input" class="form-control-label">' . $column->name . ' <span class="text-danger">*</span></label>' . PHP_EOL;
        echo '                                        <input class="form-control time-flatpickr" placeholder="Please select time" name="' . $column->name . '" type="text">' . PHP_EOL;
        echo '                                    </div>' . PHP_EOL;
        echo '                                </div>' . PHP_EOL;
    } elseif ($column->type == 'foreign') {
        echo '                                <div class="col-md-6">' . PHP_EOL;
        echo '                                    <div class="form-group">' . PHP_EOL;
        echo '                                        <label for="example-text-input" class="form-control-label">' . $column->name . ' <span class="text-danger">*</span></label>' . PHP_EOL;
        echo '                                        <select class="form-select" name="' . $column->name . '">' . PHP_EOL;
        echo '                                            <option selected>Open this select menu</option>' . PHP_EOL;
        echo '                                            @foreach ($' . Str::kebab(Str::plural($column->additional)) . ' as $' . Str::kebab(Str::singular($column->additional)) . ')' . PHP_EOL;
        echo '                                                <option value="{{ $' . Str::kebab(Str::singular($column->additional)) . '->id }}">{{ $' . Str::kebab(Str::singular($column->additional)) . '->id }}</option>' . PHP_EOL;
        echo '                                            @endforeach' . PHP_EOL;
        echo '                                        </select>' . PHP_EOL;
        echo '                                    </div>' . PHP_EOL;
        echo '                                </div>' . PHP_EOL;
    }
}

echo '                            </div>' . PHP_EOL;
echo '                            <div class="text-end mt-2">' . PHP_EOL;
echo '                                <button type="button" onclick="history.back()" class="btn btn-secondary btn-md ms-auto">Back</button>' . PHP_EOL;
echo '                                <button type="submit" class="btn btn-primary btn-md ms-auto">Save</button>' . PHP_EOL;
echo '                            </div>' . PHP_EOL;
echo '                        </form>' . PHP_EOL;
echo '                    </div>' . PHP_EOL;
echo '                </div>' . PHP_EOL;
echo '            </div>' . PHP_EOL;
echo '        </div>' . PHP_EOL;
echo '        @include(\'layouts.footers.auth.footer\')' . PHP_EOL;
echo '    </div>' . PHP_EOL;
echo '@endsection' . PHP_EOL;

echo '@push(\'js\')' . PHP_EOL;
echo '    <script></script>' . PHP_EOL;
echo '@endpush' . PHP_EOL;
?>
