@extends('layouts.appPopup')
@section('script')
	<script>
		$(document).ready(function () {
			if (parent && parent.location) {
				parent.location.reload();   
				parent.$.colorbox.close(); 
			}
		});
	</script>	
@endsection
