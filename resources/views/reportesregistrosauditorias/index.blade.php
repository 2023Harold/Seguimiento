@extends('layouts.app')
@section('breadcrums')
    {{ Breadcrumbs::render('reportesregistrosauditorias.index',$auditorias) }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12 mt-2">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">
                    <a href="{{ route('home') }}"><i class="fa fa-arrow-alt-circle-left fa-1x text-primary"></i></a> &nbsp;
                    Reportes Registros de Auditorias
                </h1>
            </div> 
            <div class="card-body">  
				
				<div class="card" >
					<div class="card-body text-center bg-light">
						<h5 class="card-title">{{$auditorias}} Auditorías </h5></br>
						<div class="row">
							<div class="col-md-4">
								<div class="card" >
									<div class="card-body bg-secondary">
										<h5 class="card-title">{{$radicaciones}} Radicaciones </h5>
									</div>
								</div>							
							</div>
							<div class="col-md-4">
								<div class="card" >
									<div class="card-body bg-secondary">
										<h5 class="card-title">{{$comparecencias}} Comparecencias</h5>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="card" >
									<div class="card-body bg-secondary">
										<h5 class="card-title">{{$acuerdoconclusion}} Acuerdo de conlusion</h5>
									</div>
								</div>
							</div>						
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="card" >
									<div class="card-body bg-secondary">
										<h5 class="card-title">{{$radicaciones}} Informes de Auditorias </h5>
									</div>
								</div>							
							</div>						
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="card" >
									<div class="card-body bg-secondary">
										<h5 class="card-title">{{$turnosui}} Turnos UI </h5>
									</div>
								</div>							
							</div>
							<div class="col-md-4">
								<div class="card" >
									<div class="card-body bg-secondary">
										<h5 class="card-title">{{$turnooic}} Turnos OIC</h5>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="card" >
									<div class="card-body bg-secondary">
										<h5 class="card-title">{{$turnotat}} Turnos a Archivo</h5>
									</div>
								</div>
							</div>						
						</div>
					</div>
				</div>
            </div>
		</div>
	</div>
</div>
@endsection														
