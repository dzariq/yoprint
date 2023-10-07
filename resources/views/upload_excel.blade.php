<!-- resources/views/upload_excel.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Upload Excel File</div>

                <div class="panel-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <form action="{{ route('export.csv') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="file">Choose Excel File:</label>
                            <input type="file" name="file" id="file" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                    <br/>
                    <div class="table-responsive" >
                        <table class="table">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <td>Time</td>
                                        <td>Filename</td>
                                        <td>Status</td>
                                    </tr>
                                </thead>
                                <tbody id="ajax-content">
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

