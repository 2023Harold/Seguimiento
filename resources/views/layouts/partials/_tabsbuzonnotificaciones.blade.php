<span class="h5">
	<ul class="nav nav-tabs nav-tabs-line mb-5">
		<li class="nav-item">

			<a class="nav-link {{
			(Route::current()->getName() == 'notificaciones.index') ? 'active' : '' }}" data-toggle="tab" href="{{route('notificaciones.index')}}">
				{{-- <span	class="badge badge-square badge-secondary h-20px w-20px t-10 translate-middle pulse pulse-danger top-0 start-100"
						style="visibility:visible" id="badge_id">
					<span class="pulse-ring" style="visibility:visible" id="pulse_id"></span>
				</span>- --}}
				Todas &nbsp;&nbsp;&nbsp;
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link {{
				(Route::current()->getName() == 'buzonnotificacionesleidas.index') ? 'active' : '' }}" data-toggle="tab" href="{{route('buzonnotificacionesleidas.index')}}">
				{{--<span	class="badge badge-square badge-secondary h-20px w-20px t-10 translate-middle pulse pulse-danger top-0 start-100"
						style="visibility:visible" id="badge_id">
					<span class="pulse-ring" style="visibility:visible" id="pulse_id"></span>
				</span>- --}}
				Leídas&nbsp;&nbsp;&nbsp;
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link {{
				(Route::current()->getName() == 'buzonnotificacionespendientes.index') ? 'active' : '' }}" data-toggle="tab" href="{{route('buzonnotificacionespendientes.index')}}">
				<span	class="badge badge-square badge-secondary h-20px w-20px t-10 translate-middle pulse pulse-danger top-0 start-100"
						style="visibility:visible" id="badge_id">{{totalFaltantesNotificaciones()}}
					<span class="pulse-ring" style="visibility:visible" id="pulse_id"></span>
				</span>
				Pendientes&nbsp;&nbsp;&nbsp;
			</a>
		</li>
	</ul>
</span>
