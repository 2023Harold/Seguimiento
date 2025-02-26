@extends('layouts.reportedoc')
    @section('Titulo')
        Registro del acuerdo de radicación
    @endsection
@section('content')

{{-- Inversión Física --}}
<div>      
        <p style="font-family: Arial; color: #000000; font-size: 14px; text-align: justify;">
            V I S T O el memorándum número <span style="color: blue;">{{$modelo->num_memo_recepcion_expediente}}</span>, presentado por <span style="color: blue;">{{$auditoria->tipo_auditoria->nombre_ae}}</span>, <span style="color: blue;">"Auditor Especial de  {{(str_contains($auditoria->tipo_auditoria->nombre_ae, 'Jaime')?'Cumplimiento Financiero e Inversión Física':'Legalidad y Desempeño' )}}" </span> del Órgano Superior de Fiscalización del Estado de México, 
            por medio del cual remite a la Unidad de Seguimiento el Expediente Técnico derivado de la Auditoría <span style="color: blue;">{{$auditoria->tipo_auditoria->descripcion}}</span> practicada a<span style="color: blue;">{{$relacion4['entidad']}}</span>, 
            por el período comprendido <span style="color: blue;">{{$auditoria->periodo_revision}}</span>; por lo que, con fundamento en los artículos 1, 3, 4 <span style="color: blue;">{{$relacion4['fraccion']}}</span>, 5, 6, 7, 8, 9, 21, 42 Bis, 53 fracción I, 54,<span style="color: blue;"> {{$relacion4['recomendaciones01']}}</span>,55 párrafo segundo de la Ley de Fiscalización Superior del Estado de México y; 
            3 fracciones XIII Bis  <span style="color: blue;">{{$relacion4['recomendaciones02']}}</span>, 4, 6 fracciones III, XVIII,  <span style="color: blue;">{{$relacion4['siPRAS']}}</span> y XXXVII, 23 y 47 del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; se emite el siguiente:
            <br><br>     
        </p> 
        <p style="font-family: Arial; color: #000000; font-size: 14px; text-align: center; width:100%;"><strong>ACUERDO</strong></p><br>
        <p style="font-family: Arial; color: #000000; font-size: 14px; text-align: justify;">
            <strong>PRIMERO.</strong> Se tienen por presentados el memorándum, el Informe de Auditoría y el Expediente Técnico de la Auditoría <span style="color: blue;">{{$auditoria->tipo_auditoria->descripcion}}</span> practicada a <span style="color: blue;">{{$relacion4['entidad']}}</span>, 
                por el período comprendido <span style="color: blue;">{{ $auditoria->periodo_revision}}</span> y ordenada mediante el oficio número <span style="color: blue;">{{$modelo->numero_acuerdo}}</span>, por lo que se admite para los trámites subsecuentes. <br><br>
            <strong>SEGUNDO. Se admite</strong> a trámite el Expediente Técnico de la Auditoría citada al epígrafe. <br><br>
            <strong>TERCERO. Se ordena</strong> radicar y registrar el Expediente de Seguimiento en el Libro de Gobierno de esta Unidad con el número progresivo <span style="color: blue;">{{$modelo->numero_expediente}}</span>. <br><br>
            {{$relacion4['siPRAS01']}}

            <strong>{{$relacion4['orden']}}</strong> En términos de los artículos 14 y 16 de la Constitución Política de los Estados Unidos Mexicanos; 53, 54 y <span style="color: blue;">{{$relacion4['recomendaciones01']}}</span> de la Ley de Fiscalización Superior del Estado de México y; 6 fracciones XXV y XXXVII y 47 fracciones III, IV, XII y XIX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México,
            se cita a <span style="color: blue;">{{$auditoria->comparecencia->nombre_titular}}</span>, <span style="color: blue;">{{$auditoria->comparecencia->cargo_titular}}</span>, para que comparezca de manera personal por sí o a través de su representante legal o enlace administrativo debidamente autorizado a las 
            <span style="color: blue;">{{strtolower($relacion4['hora01'])}}</span> horas<span style="color: blue;">, del día </span> <span style="color: blue;">{{strtolower($relacion4['day01'])}} de {{strtolower($relacion4['mes01'])}}</span>; en el domicilio de la Unidad de Seguimiento de este Órgano Superior de Fiscalización del Estado de México, 
            sito en Avenida José María Pino Suárez Sur, números 104, 106 y 108, Colonia Cinco de Mayo, Toluca, Estado de México, C.P. 50090. Lo anterior con el objeto de que se puntualicen las observaciones detalladas en el Informe de Auditoría y se ponga a la vista del compareciente el Expediente Técnico de la Auditoría citada en el acuerdo PRIMERO del presente. <br><br>

            Para el desahogo de la comparecencia a la que se le cita en términos del presente acuerdo, únicamente se le dará acceso a <span style="color: blue;">{{$auditoria->comparecencia->nombre_titular}}</span>, <span style="color: blue;">{{$auditoria->comparecencia->cargo_titular}}</span>, para o en su caso al representante legal o enlace administrativo,
            así como a <span style="color: blue;">{{$relacion4['ambito01']}}</span> personas, quienes deberán presentar identificación oficial vigente con fotografía y firma, con el apercibimiento que para el caso de no comparecer el día y hora señalados en el presente acuerdo y, en su caso, no acreditar debidamente la designación y/o autorización del representante legal o enlace administrativo,
            se tendrá por satisfecha dicha comparecencia.<br><br>
            
            Es importante precisar que, para el caso de señalar representante legal o enlace administrativo, este deberá estar debidamente designado y/o autorizado mediante oficio y/o escrito dirigido a la Auditora Superior de Fiscalización del Estado de México, con copia de conocimiento al Titular de la Unidad de Seguimiento y, que deberá ser presentado en la Oficialía de Partes del Órgano Superior de Fiscalización del Estado de México,
            en un plazo de 24 horas previas al desahogo de la comparecencia de cuenta.<br><br>

            <strong>{{$relacion4['orden01']}}</strong> Se ordena el inicio de la Etapa de Aclaración y <span style="color: blue;">{{$relacion4['recomendaciones03']}}</span> de las observaciones subsistentes en materia de Inversión Física y, que se encuentran detalladas en el Informe de Auditoría; por lo cual, con fundamento en lo dispuesto en el artículo 54 fracción I de la Ley de Fiscalización Superior del Estado de México, se concede a la entidad fiscalizada un plazo de 30 (Treinta)
            días hábiles contados a partir del día <span style="color: blue;">{{strtolower($relacion4['day02'])}} de {{strtolower($relacion4['mes02'])}}</span>; y que fenece el <span style="color: blue;">{{strtolower($relacion4['day03'])}} de {{strtolower($relacion4['mes03'])}}</span>, a efecto de que se presenten los elementos, documentos y datos fehacientes que aclaren o solventen el contenido de las acciones de cuenta, o en su caso,
            manifieste lo que a su derecho convenga <span style="color: blue;">{{$relacion4['recomendaciones04']}}</span>.<br><br>

            La información y/o documentación que exhiba la entidad fiscalizada en relación a las observaciones de mérito, deberá presentarse en medio impreso, digital y certificada.<br><br>

            <strong>{{$relacion4['ultimoOrden']}}</strong>. En términos de Ley, notifíquese los acuerdos correspondientes a la<span style="color: blue;">{{$relacion4['siPRAS02']}}</span>, o a su equivalente.<br><br>
            
            Así lo acordó y firma Luis Ignacio Sierra Villa, Titular de la Unidad de Seguimiento del Órgano Superior de Fiscalización del Estado de México, a los <span style="color: blue;">{{$relacion4['fechaactual']}}</span>.<br><br>
            
        </p> <br><br><br><br><br><br><br><br>
        <hr>
        <p style="font-family: Arial; color: #000000; font-size: 9px; text-align: justify;">
            LISV/{{$relacion4['iniciales']}}*
        </p>      
    </div>
            @if("si hay algo al final siempre"=="si") 
            <div style="page-break-before: always;">
                <p style="font-family: Arial; color: #000000; font-size: 14px; text-align: justify; page-break-inside: always;">
                    <div style="page-break-before: always;">
                        <p style="font-family: Arial; color: #000000; font-size: 14px; text-align: justify; page-break-inside: always;">
                        </p>
                    </div>
                </p>
            </div>
            @endif
@endsection
