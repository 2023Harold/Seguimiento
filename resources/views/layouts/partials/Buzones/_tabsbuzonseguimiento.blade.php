<span class="h5">
	<ul class="nav nav-tabs nav-tabs-line mb-5">
		<li class="nav-item">

			<a class="nav-link {{
			(Route::current()->getName() == 'buzonseg.index')||
			(Route::current()->getName() == 'buzonradicacion.index')||
			(Route::current()->getName() == 'buzoncomparecencia.index')||
			(Route::current()->getName() == 'buzonacuerdosconclusion.index')||
			(Route::current()->getName() == 'buzonpras.index')||
			(Route::current()->getName() == 'buzonrecomendaciones.index')||
			(Route::current()->getName() == 'buzonpliegosobservacion.index')||
			(Route::current()->getName() == 'buzonsolicitudes.index')||
			(Route::current()->getName() == 'buzoninformes.index')||
			(Route::current()->getName() == 'buzonturnooic.index')||
			(Route::current()->getName() == 'buzonturnoui.index')||
			(Route::current()->getName() == 'buzonturnoenvioarchivo.index') ? 'active' : '' }}" data-toggle="tab" href="{{route('buzonradicacion.index')}}">
				{{-- <span	class="badge badge-square badge-secondary h-20px w-20px t-10 translate-middle pulse pulse-danger top-0 start-100"
						style="visibility:visible" id="badge_id">
					<span class="pulse-ring" style="visibility:visible" id="pulse_id"></span>
				</span>- --}}
				Auditorias &nbsp;&nbsp;&nbsp;
			</a>
		</li>
	</ul>
</span>
