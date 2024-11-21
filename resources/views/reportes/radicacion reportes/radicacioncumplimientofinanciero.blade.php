@extends('layouts.reportedoc')
@section('Titulo')
Registro del acuerdo de radicación
@endsection
@section('content')
{{-- CUMPLIMIENTO FINANCIERO --}}
<div>
        <p style="font-family: Arial; color: #000000; font-size: 14px; text-align: justify;">
            V I S T O el memorándum número <span style="color: blue;">{{$modelo->num_memo_recepcion_expediente}}</span>, presentado por <span style="color: blue;">{{$auditoria->tipo_auditoria->nombre_ae}}</span>, <span style="color: blue;">"Auditor Especial de  {{(str_contains($auditoria->tipo_auditoria->nombre_ae, 'Jaime Enrique Perdigon Nieto')?'Cumplimiento Financiero e Inversión Física':'Legalidad y Desempeño' )}}" </span> del Órgano Superior de Fiscalización del Estado de México, 
            por medio del cual remite a la Unidad de Seguimiento el Expediente Técnico derivado de la Auditoría <span style="color: blue;">{{$auditoria->tipo_auditoria->descripcion}}</span> practicada a <span style="color: blue;">{{ $auditoria->entidad_fiscalizable}}</span>, 
            por el período comprendido <span style="color: blue;">{{$auditoria->periodo_revision}}</span>, así como, el Informe de Auditoría correspondiente, 
            en el que se presentan los datos que identifican los resultados finales obtenidos con las observaciones determinadas a la citada entidad fiscalizada; 
            con fundamento en los artículos 1, 3, 4 fracción 5, 6, 7, 8, 9, 21, 42 Bis, 53 fracción XXX, 54, 54 Bis, 55 párrafo segundo y 59 de la Ley de Fiscalización Superior del Estado de México y; 3 fracciones XIII Bis y XXIII Bis, 4, 6 fracciones III, XVIII, XIX, XXV y XXXVII, 23, 26 fracción XXXI y 47 del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; se emite el siguiente: <br><br>
        </p>        
        <p style="font-family: Arial; color: #000000; font-size: 14px; text-align: center; width:100%;"><strong>ACUERDO</strong></p><br>
        <p style="font-family: Arial; color: #000000; font-size: 14px; text-align: justify;">
            <strong>PRIMERO.</strong> Se tienen por presentados el memorándum, el Informe de Auditoría y el Expediente Técnico de la Auditoría <span style="color: blue;">{{$auditoria->tipo_auditoria->descripcion}}</span> practicada a <span style="color: blue;">{{$auditoria->entidad_fiscalizable}}</span>, 
                por el período comprendido <span style="color: blue;">{{ $auditoria->periodo_revision}}</span> y ordenada mediante el oficio número <span style="color: blue;">{{$modelo->numero_acuerdo}}</span>, por lo que se admite para los trámites subsecuentes. <br><br>
            <strong>SEGUNDO.</strong> Se admite a trámite el Expediente Técnico de la Auditoría citada al epígrafe, el cual contiene el Informe de Resultados Finales correspondiente. <br><br>
            <strong>TERCERO.</strong> Se ordena radicar, formar y registrar el Expediente de Seguimiento en el Libro de Gobierno de esta Unidad con el número progresivo <span style="color: blue;">{{$modelo->numero_expediente}}</span> y túrnese a la Dirección de Seguimiento competente. <br><br>
            <strong>CUARTO.</strong> Notifíquese mediante oficio a la entidad fiscalizada, el Informe de Auditoría de mérito, para su conocimiento y efectos legales a que haya lugar. <br><br>
            <strong>QUINTO.</strong> Con fundamento en lo previsto en los artículos 42 Bis, 53 fracción I y 55 párrafo segundo de la Ley de Fiscalización Superior del Estado de México; 12 párrafo segundo y 103 de la Ley de Responsabilidades Administrativas del Estado de México y Municipios y; 
            23 fracciones XIX y XLIV y 47 fracciones III, V, XII y XIX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México, túrnese por oficio al Órgano Interno de Control de <span style="color: blue;">{{$auditoria->entidad_fiscalizable}}</span> o a su equivalente, 
            las Promociones de Responsabilidad Administrativa Sancionatoria (PRAS) que se desprenden de los resultados obtenidos del acto de fiscalización de mérito, así como, su soporte documental correspondiente en copias certificadas, 
            para el efecto de que dicha autoridad {{((!empty ($auditoria->entidadFiscalizable->Ambito)&&$auditoria->entidadFiscalizable->Ambito))}} continúe con las investigaciones pertinentes y promueva las acciones procedentes. <br><br>
            
            <strong>SEXTO.</strong> En términos de los artículos 14 y 16 de la Constitución Política de los Estados Unidos Mexicanos; 53, 54 {{count($auditoria->accionesrecomendaciones)>0?' y 54 Bis':''}} de la Ley de Fiscalización Superior del Estado de México y; 6 fracciones XXV y XXXVII y 47 fracciones III, IV, XII y XIX del 
            Reglamento Interior del Órgano Superior de Fiscalización del Estado de México, se cita a  <span style="color: blue;">{{$auditoria->comparecencia->nombre_titular}}</span>, <span style="color: blue;">{{$auditoria->comparecencia->cargo_titular}}</span>, para que comparezca de manera personal por sí o a través de sus representantes legales o enlaces debidamente autorizados a las 
            <span style="color: blue;">{{strtolower($relacion4['horastxt'])}}</span> horas y <span style="color: blue;">{{strtolower($relacion4['mintxt'])}}</span> minutos, del día <span style="color: blue;">{{strtolower($relacion4['fechacomparecenciatxt'])}}</span> en el domicilio de la Unidad de Seguimiento de este Órgano Superior de 
            Fiscalización del Estado de México, sito en Avenida José María Pino Suárez Sur, números 104, 106 y 108, Colonia Cinco de Mayo, Toluca, Estado de México, C.P. 50090. 
            Lo anterior con el objeto de que se puntualicen las observaciones detalladas en el Informe de Auditoría a que se alude en el acuerdo TERCERO del presente y para su consulta, se ponga a la vista del compareciente el expediente técnico de la auditoría citada en el acuerdo PRIMERO del presente. <br><br>

            Para el desahogo de la comparecencia a la que se le cita en términos del presente acuerdo, se deberá presentar identificación oficial vigente con fotografía y firma, con el apercibimiento que para el caso de no comparecer el día y hora señalados en el presente acuerdo y, 
            en su caso, no acreditar debidamente la designación y/o autorización de los representantes legales o enlaces administrativos, se tendrá por satisfecha dicha comparecencia. <br><br>

            Es importante precisar que, para el caso de señalar representantes legales o enlaces administrativos, estos deberán estar debidamente designados y/o autorizados mediante oficio y/o escrito dirigido a la Auditora Superior de Fiscalización del Estado de México, con copia de conocimiento al Titular de la Unidad de Seguimiento y, 
            que deberá ser presentado en la Oficialía de Partes del Órgano Superior de Fiscalización del Estado de México, en un plazo de 24 horas previas al desahogo de la comparecencia de cuenta. <br><br>

            Cabe señalar que, para el caso de que se designen y/o autoricen a dos o más personas, se deberá designar en el oficio y/o escrito antes mencionado, a un representante común de entre ellas, de no hacerse el nombramiento en comento, esta autoridad considerará como representante común a la persona señalada en primer término. <br><br>
            
            @if ($auditoria->tipo_auditoria_id==1||$auditoria->tipo_auditoria_id==2)                
                    <strong>SÉPTIMO.</strong> Se ordena el inicio de la Etapa de Aclaración y del Proceso de Atención {{count($auditoria->accionesrecomendaciones)>0?'a las Recomendaciones,':''}} de las observaciones subsistentes en materia Cumplimmiento Financiero y, que se encuentran detalladas en el multicitado Informe de Auditoría; por lo cual, con fundamento en lo dispuesto en los artículos 54 fracción I  
                    {{count($auditoria->accionesrecomendaciones)>0?'y 54 Bis fracción II':''}}  de la Ley de Fiscalización Superior del Estado de México, se concede a la entidad fiscalizada un plazo de 30 (Treinta) días hábiles contados a partir del día <span style="color: blue;">{{strtolower($relacion4['fechainicioaclaraciontxt'])}}</span> y que fenece el día <span style="color: blue;">{{strtolower($relacion4['fechaterminoaclaraciontxt'])}}</span>, 
                    a efecto de que se presenten los elementos, documentos y datos fehacientes que aclaren o solventen el contenido de las acciones de cuenta, o en su caso, manifieste lo que a su derecho convenga; asimismo, se informe de las mejoras realizadas y las acciones emprendidas en relación a las recomendaciones de mérito, o en su caso, 
                    justifique su improcedencia, con el apercibimiento de que, en caso de no dar cumplimiento en el plazo concedido, se entenderán por no atendidas {{count($auditoria->accionesrecomendaciones)>0?'ni justificadas,':''}} dichas observaciones. <br><br>
                    La información y/o documentación que exhiba la entidad fiscalizada en relación a las observaciones de mérito, deberá presentarse en medio impreso, digital y certificada. <br><br>
            @endif
            

            <strong>OCTAVO.</strong> En terminos de la Ley notifiquese el presente proveído a la entidad fiscalizafda y al Órgano Interno de Control de {{$auditoria->entidad_fiscalizable}}, o a sus equivalente. 
            Así lo acordó y firma Luis Ignacio Sierra Villa, Titular de la Unidad de Seguimiento del Órgano Superior de Fiscalización del Estado de México, a los <span style="color: blue;">{{fechaactualreporte()}}</span>.

        <p style="font-family: Arial; color: #000000; font-size: 8px; text-align: justify;">
            Artículo 54.- La etapa de aclaración tiene como finalidad que la entidad fiscalizada, solvente o aclare el contenido de las observaciones. La etapa de aclaración se desarrollará de la siguiente manera: I. El Órgano Superior, formulará y entregará el contenido de las observaciones dentro de los informes de auditoría; <span style="text-decoration:underline;">para que la entidad fiscalizada, dentro del plazo de treinta días hábiles, aclare, solvente o manifieste lo que a su derecho convenga; (…)</span> <br>
            Artículo 54 Bis. Con relación a las recomendaciones, el proceso de su atención se desarrollará de la siguiente manera: - - - II. La información, documentación o consideraciones aportadas por las entidades fiscalizadas para atender las recomendaciones en los plazos convenidos, deberán precisar las mejoras realizadas y las acciones emprendidas. En caso contrario, deberán justificar su improcedencia. <br>            
        </p>      
    </div>    
@endsection
