<span class="h5">
	<ul class="nav nav-tabs nav-tabs-line mb-5">
		<li class="nav-item">

			<a class="nav-link {{
			(Route::current()->getName() == 'buzonseg.index')||
			(Route::current()->getName() == 'buzonradicacion.index') ? 'active' : '' }}" data-toggle="tab" href="{{route('buzonradicacion.index')}}">
				{{-- <span	class="badge badge-square badge-secondary h-20px w-20px t-10 translate-middle pulse pulse-danger top-0 start-100"
						style="visibility:visible" id="badge_id">
					<span class="pulse-ring" style="visibility:visible" id="pulse_id"></span>
				</span>- --}}
				Radicación &nbsp;&nbsp;&nbsp;
			</a>
		</li>
		<li class="nav-item">

			<a class="nav-link {{
			(Route::current()->getName() == 'buzonseg.index')||
			(Route::current()->getName() == 'buzoncomparecencia.index') ? 'active' : '' }}" data-toggle="tab" href="{{route('buzoncomparecencia.index')}}">
				{{-- <span	class="badge badge-square badge-secondary h-20px w-20px t-10 translate-middle pulse pulse-danger top-0 start-100"
						style="visibility:visible" id="badge_id">
					<span class="pulse-ring" style="visibility:visible" id="pulse_id"></span>
				</span>- --}}
				Comparecencia &nbsp;&nbsp;&nbsp;
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link {{
				(Route::current()->getName() ==   'buzonseg.index')||
				(Route::current()->getName() == 'buzonacuerdosconclusion.index') ? 'active' : '' }}" data-toggle="tab" href="{{route('buzonacuerdosconclusion.index')}}">
				{{--<span	class="badge badge-square badge-secondary h-20px w-20px t-10 translate-middle pulse pulse-danger top-0 start-100"
						style="visibility:visible" id="badge_id">
					<span class="pulse-ring" style="visibility:visible" id="pulse_id"></span>
				</span>- --}}
				Acuerdo de Conclusión &nbsp;&nbsp;&nbsp;
			</a>
		</li>
		{{--<li class="nav-item">
			<a class="nav-link {{
				(Route::current()->getName() == 'buzonpras.index') ? 'active' : '' }}" data-toggle="tab" href="{{route('buzonpras.index')}}">
				<span	class="badge badge-square badge-secondary h-20px w-20px t-10 translate-middle pulse pulse-danger top-0 start-100"
						style="visibility:visible" id="badge_id">
					<span class="pulse-ring" style="visibility:visible" id="pulse_id"></span>
				</span>- 
				PRAS &nbsp;&nbsp;&nbsp;
			</a>
		</li>--}}
		@if (getSession("cp")!=2022 )
			<li class="nav-item">
				<a class="nav-link {{
					(Route::current()->getName() == 'buzonrecomendaciones.index') ? 'active' : '' }}" data-toggle="tab" href="{{route('buzonrecomendaciones.index')}}">
					{{--<span	class="badge badge-square badge-secondary h-20px w-20px t-10 translate-middle pulse pulse-danger top-0 start-100"
							style="visibility:visible" id="badge_id">
						<span class="pulse-ring" style="visibility:visible" id="pulse_id"></span>
					</span>- --}}
					Recomendaciones &nbsp;&nbsp;&nbsp;
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link {{
					(Route::current()->getName() == 'buzonpliegosobservacion.index') ? 'active' : '' }}" data-toggle="tab" href="{{route('buzonpliegosobservacion.index')}}">
					{{--<span	class="badge badge-square badge-secondary h-20px w-20px t-10 translate-middle pulse pulse-danger top-0 start-100"
							style="visibility:visible" id="badge_id">
						<span class="pulse-ring" style="visibility:visible" id="pulse_id"></span>
					</span>- --}}
					Pliegos de Observación &nbsp;&nbsp;&nbsp;
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link {{
					(Route::current()->getName() == 'buzonsolicitudes.index') ? 'active' : '' }}" data-toggle="tab" href="{{route('buzonsolicitudes.index')}}">
					{{--<span	class="badge badge-square badge-secondary h-20px w-20px t-10 translate-middle pulse pulse-danger top-0 start-100"
							style="visibility:visible" id="badge_id">
						<span class="pulse-ring" style="visibility:visible" id="pulse_id"></span>
					</span>- --}}
					Solicitudes de aclaración &nbsp;&nbsp;&nbsp;
				</a>
			</li>
		@endif
		<li class="nav-item">
			<a class="nav-link {{
				(Route::current()->getName() == 'buzoninformes.index') ? 'active' : '' }}" data-toggle="tab" href="{{route('buzoninformes.index')}}">
				{{--<span	class="badge badge-square badge-secondary h-20px w-20px t-10 translate-middle pulse pulse-danger top-0 start-100"
						style="visibility:visible" id="badge_id">
					<span class="pulse-ring" style="visibility:visible" id="pulse_id"></span>
				</span>- --}}
				Informes &nbsp;&nbsp;&nbsp;
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link {{
				(Route::current()->getName() == 'buzonturnooic.index') ? 'active' : '' }}" data-toggle="tab" href="{{route('buzonturnooic.index')}}">
				{{--<span	class="badge badge-square badge-secondary h-20px w-20px t-10 translate-middle pulse pulse-danger top-0 start-100"
						style="visibility:visible" id="badge_id">
					<span class="pulse-ring" style="visibility:visible" id="pulse_id"></span>
				</span>- --}}
				Turno OIC &nbsp;&nbsp;&nbsp;
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link {{
				(Route::current()->getName() == 'buzonturnoui.index') ? 'active' : '' }}" data-toggle="tab" href="{{route('buzonturnoui.index')}}">
				{{--<span	class="badge badge-square badge-secondary h-20px w-20px t-10 translate-middle pulse pulse-danger top-0 start-100"
						style="visibility:visible" id="badge_id">
					<span class="pulse-ring" style="visibility:visible" id="pulse_id"></span>
				</span>- --}}
				Turno UI &nbsp;&nbsp;&nbsp;
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link {{
				(Route::current()->getName() == 'buzonturnoenvioarchivo.index') ? 'active' : '' }}" data-toggle="tab" href="{{route('buzonturnoenvioarchivo.index')}}">
				{{--<span	class="badge badge-square badge-secondary h-20px w-20px t-10 translate-middle pulse pulse-danger top-0 start-100"
						style="visibility:visible" id="badge_id">
					<span class="pulse-ring" style="visibility:visible" id="pulse_id"></span>
				</span>- --}}
				Turno envío archivo &nbsp;&nbsp;&nbsp;
			</a>
		</li>

	</ul>
</span><br>
