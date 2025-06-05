@extends('layouts.appPopup')
@section('content')
<div class="card">
     <td class="text-center">
     @if (!empty($cedula->cedula_tipo))
         <a href="{{ asset($cedula->cedula_cargada) }}" target="_blank">
                 <?php echo htmlspecialchars_decode(iconoArchivo($cedula->cedula_cargada)); ?>
         </a><br>       
     @endif
    </td>   
</div>
@endsection