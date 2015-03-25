@extends('layouts/main')

@section('title')
Migrate
@stop

@section('header')
@stop

@section('content')
   <div class="row">
        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading" align='center'>
                    Update Stock Database
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            {{ Form::open(array('action' => 'migrate', 'files' => true, 'id'=>'migrate'), 'POST') }}
                                <br>

                                <div class="form-group">
                                    <label>CSV File</label>
                                    
                                </div>
                                {{ Form::file('file[]', ['multiple' => true]) }}
                                <br>
                                <center><button type="submit" class="btn btn-default">Submit</button></center>
                            {{ Form::close() }}
                        </div>
                	</div>
            	</div>
            </div>
        </div>
    </div>
@stop

@section('footer')
@stop