@extends('layouts.reportedoc')
@section('Titulo')
Registro del acuerdo de radicación
{{-- Radicacion desempeño --}}
@endsection
@section('content')
<div>
        <p style="font-family: Arial; color: #000000; font-size: 14px; text-align: justify;"> 
            <strong> V I S T O</strong> el memorándum número <span style="color: blue;">{{$modelo->num_memo_recepcion_expediente}}</span> presentado por <span style="color: blue;">{{$auditoria->tipo_auditoria->nombre_ae}}</span>, <span style="color: blue;">"Auditor Especial de {{(str_contains($auditoria->tipo_auditoria->nombre_ae, 'Jaime')?'Cumplimiento Financiero e Inversión Física':'Legalidad y Desempeño' )}}" </span> del Órgano Superior de Fiscalización del Estado de México,
            por medio del cual remite a la Unidad de Seguimiento, el Expediente Técnico derivado de la Auditoría de Desempeño practicada a<span style="color: blue;">{{$relacion4['entidad']}}</span> por el período comprendido ; <span style="color: blue;">{{ $auditoria->periodo_revision}}</span> por lo que, con fundamento en los artículos 1, 3, 4 <span style="color: blue;">{{$relacion4['fraccion']}}</span>,
            5, 6, 7, 8, 9, 21, 42 Bis, 53 fracción II, 54 Bis y 59 de la Ley de Fiscalización Superior del Estado de México y; 3 fracción XXIII Bis, 4, 6 fracciones III, XVIII y XXXVII, 23 y 47 del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; se emite el siguiente:
            <br><br> 
        </p>        
        <p style="font-family: Arial; color: #000000; font-size: 14px; text-align: center; width:100%;"><strong>ACUERDO</strong></p><br>
        <p style="font-family: Arial; color: #000000; font-size: 14px; text-align: justify;">
            <strong>PRIMERO.</strong> Se tienen por presentados el memorándum y el Expediente Técnico de la Auditoría de Desempeño, practicada <span style="color: blue;">{{$relacion4['entidad']}}</span>, por el período comprendido del <span style="color: blue;">{{ $auditoria->periodo_revision}}</span> y ordenada mediante oficio número <span style="color: blue;">{{$modelo->numero_acuerdo}}</span>; por lo que se admiten para los trámites subsecuentes. <br><br>
            
            <strong>SEGUNDO.</strong> <strong>Se admite</strong> a trámite el Expediente Técnico de la Auditoría citada al epígrafe. <br><br>

            <strong>TERCERO.</strong> <strong>Se ordena</strong> radicar y registrar el Expediente de Seguimiento en el Libro de Gobierno de esta Unidad con el número progresivo <strong><span style="color: blue;">{{$modelo->numero_expediente}}</span>.</strong> <br><br>

            <strong>CUARTO.</strong> En términos de los artículos 14 y 16 de la Constitución Política de los Estados Unidos Mexicanos; 53, 54 Bis de la Ley de Fiscalización Superior del Estado de México y; 6 fracciones XXV y XXXVII y 47 fracciones III, IV, XII y XIX del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México, <strong>se cita a <span style="color: blue;">{{$auditoria->comparecencia->nombre_titular}}</span>, <span style="color: blue;">{{$auditoria->comparecencia->cargo_titular}}</span></strong>,
            para que comparezca de manera personal por sí o a través de su representante legal o enlace administrativo debidamente autorizado a las <strong><span style="color: blue;">{{strtolower($relacion4['hora01'])}}</span> horas<span style="color: blue;">, del día </span> <span style="color: blue;">{{strtolower($relacion4['day01'])}} de {{strtolower($relacion4['mes01'])}}</span>;</strong> en el domicilio de la Unidad de Seguimiento de este Órgano Superior de Fiscalización del Estado de México,
            sito en Avenida José María Pino Suárez Sur, números 104, 106 y 108, Colonia Cinco de Mayo, Toluca, Estado de México, C.P. 50090. <strong>Lo anterior con el objeto de que se puntualicen las observaciones detalladas en el Informe de Auditoría y se ponga a la vista del compareciente el Expediente Técnico de la Auditoría citada en el acuerdo PRIMERO del presente.</strong> <br><br>
            
            Para el desahogo de la comparecencia a la que se le cita en términos del presente acuerdo, <U>únicamente se le dará acceso a <span style="color: blue;">{{$auditoria->comparecencia->nombre_titular}}</span>, <span style="color: blue;">{{$auditoria->comparecencia->cargo_titular}}</span>, para o en su caso al representante legal o enlace administrativo, así como a <span style="color: blue;">{{$relacion4['ambito01']}}</span> personas, quienes deberán presentar identificación oficial vigente con fotografía y firma,</U>
            con el apercibimiento que para el caso de no comparecer el día y hora señalados en el presente acuerdo y, en su caso, no acreditar debidamente la designación y/o autorización del representante legal o enlace administrativo, se tendrá por satisfecha dicha comparecencia. <br><br>

            Es importante precisar que, para el caso de señalar representante legal o enlace administrativo, este deberá estar debidamente designado y/o autorizado mediante oficio y/o escrito dirigido a la Auditora Superior de Fiscalización del Estado de México, con copia de conocimiento al Titular de la Unidad de Seguimiento y, que deberá ser presentado en la Oficialía de Partes del Órgano Superior de Fiscalización del Estado de México,<u> en un plazo de 24 horas previas al desahogo de la comparecencia de cuenta.</u> <br><br>

            <strong>QUINTO.</strong> Para el Proceso de Atención a las Recomendaciones en materia de Desempeño</strong> que se encuentran detalladas en el Informe de Auditoría, con fundamento en lo dispuesto en el artículo 54 Bis fracción II de la Ley de Fiscalización Superior del Estado de México, se ordena dar seguimiento a las mismas, en el en el término de <span style="color: blue;">{{$auditoria->radicacion->plazo_maximo}} ({{$relacion4['plazomaximo']}})</span> días hábiles, plazo que fue convenido con el Órgano Superior de Fiscalización del Estado de México,
            detallado en el Acta de Reunión de Resultados Finales y Cierre de Auditoría <span style="color: blue;">{{$relacion4['cierre']}}</span>, integrada en autos del expediente referido en el numeral Segundo del presente acuerdo, a efecto de precisen las mejoras realizadas y las acciones emprendidas en relación con las recomendaciones determinadas, o en su caso, justifique su improcedencia. <br><br>
            
            Derivado de lo anterior, en términos del artículo 42 Bis de la Ley de Fiscalización Superior del Estado de México, apercíbasele a la entidad fiscalizada para que en caso de no dar cumplimento a los términos y plazos de mérito, de manera pertinente, completa, veraz y que guarde plena relación con las observaciones de cuenta o presentar la información o documentación fuera de los plazos y formas convenidas, se aplicará el medio de apremio correspondiente señalado en el artículo 59 fracción II de la Ley de Fiscalización Superior del Estado de México,
            que será equivalente a 100 veces el valor diario de la Unidad de Medida y Actualización (UMA) vigente, determinada por el Instituto Nacional de Estadística y Geografía, publicada el diez de enero de dos mil veinticinco, en el Diario Oficial de la Federación, <strong>corresponde a la cantidad de <span style="color: blue;">{{$relacion4['uma']}}</span></strong> Y en caso de una conducta renuente y/o contumaz de incumplimiento que obstaculice el proceso de fiscalización, además de imponer un nuevo medio de apremio que podrá alcanzar 1,500 veces el valor diario de la unidad de medida y actualización, 
            se promoverán las responsabilidades de conformidad con la Ley General de Responsabilidades Administrativas, Ley de Responsabilidades Administrativas del Estado de México y Municipios, y demás legislación penal aplicable, lo anterior en términos del artículo 42 Bis de la Ley de Fiscalización Superior del Estado de México. <br><br>
            
            La información y/o documentación que exhiba la entidad fiscalizada en relación a las observaciones de mérito,<strong><u> deberá presentarse en medio impreso, digital y certificada.</u></strong><br><br>                        

            <strong>SEXTO.</strong> En términos de Ley, notifíquese los acuerdos correspondientes a la entidad fiscalizada <span style="color: blue;">{{$relacion4['siPRAS']}}</span>, o a su equivalente. <br><br>  
            
            Así lo acordó y firma Luis Ignacio Sierra Villa, Titular de la Unidad de Seguimiento del Órgano Superior de Fiscalización del Estado de México, a los <span style="color: blue;">{{fechaactualreporte()}}</span>.<br><br>
        </p> <br><br><br><br><br><br><br><br>
        <hr>
        <p style="font-family: Arial; color: #000000; font-size: 9px; text-align: justify;">
            LISV/{{$relacion4['iniciales']}}*
        </p>      
    </div>

    
@endsection
