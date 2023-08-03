@extends('layouts.appquick')
@section('content')
<div class="card">
    <div class="card-header">
        <h1 class="card-title">Verificación</h1>
    </div>
    <div class="card-body">
        <embed src="{{asset(env('MINIO_URL')).'/'.$file_path}}" type="application/pdf" width="100%" height="600px" />
    </div>
@endsection