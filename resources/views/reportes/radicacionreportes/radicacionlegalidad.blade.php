@extends('layouts.reportedoc')
@section('Titulo')
    Registro del acuerdo de radicación
@endsection
@section('content')
<div>
        <p style="font-family: Arial; color: #000000; font-size: 14px; text-align: justify;">
            V I S T O el memorándum número <span style="color: blue;">{{$modelo->num_memo_recepcion_expediente}}</span>, presentado por <span style="color: blue;">{{$auditoria->tipo_auditoria->nombre_ae}}</span>, <span style="color: blue;">"Auditor Especial de  {{(str_contains($auditoria->tipo_auditoria->nombre_ae, 'Jaime')?'Cumplimiento Financiero e Inversión Física':'Legalidad y Desempeño' )}}" </span> del Órgano Superior de Fiscalización del Estado de México, 
            por medio del cual remite a la Unidad de Seguimiento el Expediente Técnico derivado de la Auditoría <span style="color: blue;">{{$auditoria->tipo_auditoria->descripcion}}</span> practicada a <span style="color: blue;">{{ $auditoria->entidad_fiscalizable}}</span>, 
            por el período comprendido <span style="color: blue;">{{$auditoria->periodo_revision}}</span>, así como, el Informe de Auditoría correspondiente, 
            en el que se presentan los datos que identifican los resultados finales obtenidos con las observaciones determinadas a la citada entidad fiscalizada; 
            con fundamento en los artículos 1, 3, 4 fracción <span style="color: blue;">{{ ((!empty ($auditoria->entidadFiscalizable->Ambito)&&$auditoria->entidadFiscalizable->Ambito=='Municipal')?
            'II., IV., V. en el artículo 3 fracción XVII del Código Financiero del Estado de México y Municipios, y aquellos que manejen recursos del Estado, Municipios, o en su caso provenientes de la federación;':'')
            }}{{((!empty ($auditoria->entidadFiscalizable->Ambito)&& $auditoria->entidadFiscalizable->Ambito=='Estatal')?'I. Los Poderes Públicos del Estado. III. Los organismos autónomos. IV. Los Organismos Auxiliares. V. Los fideicomisos previstos en el artículo 3 fracción XVII del Código Financiero del Estado de México y Municipios, y aquellos que manejen recursos del Estado, Municipios, o en su caso provenientes de la federación':'')}}</span>
            , 5, 6, 7, 8, 9, 21, 42 Bis, 53 fracción XXX, 54, {{count($auditoria->accionesrecomendaciones)>0?'54 Bis,':''}} {{count($auditoria->accionespras)>0?'55 párrafo segundo y':''}} {{count($auditoria->accionesrecomendaciones)>0?'59,':''}} de la Ley de Fiscalización Superior del Estado de México y; 
            3 fracciones XIII Bis {{count($auditoria->accionesrecomendaciones)>0?'y XXIII Bis':''}}, 4, 6 fracciones III, XVIII {{count($auditoria->accionespras)>0?', XIX, XXV':''}} y XXXVII, 23, 26 fracción XXXI y 47 del Reglamento Interior del Órgano Superior de Fiscalización del Estado de México; se emite el siguiente: <br><br>
        </p>        
        <p style="font-family: Arial; color: #000000; font-size: 14px; text-align: center; width:100%;"><strong>ACUERDO</strong></p><br>
        <p style="font-family: Arial; color: #000000; font-size: 14px; text-align: justify;">
            <strong>PRIMERO.</strong> Se tienen por presentados el memorándum, el Informe de Auditoría y el Expediente Técnico de la Auditoría <span style="color: blue;">{{$auditoria->tipo_auditoria->descripcion}}</span> practicada a <span style="color: blue;">{{$auditoria->entidad_fiscalizable}}</span>, 
                por el período comprendido <span style="color: blue;">{{ $auditoria->periodo_revision}}</span> y ordenada mediante el oficio número <span style="color: blue;">{{$modelo->numero_acuerdo}}</span>, por lo que se admite para los trámites subsecuentes. <br><br>
            <strong>SEGUNDO.</strong> <strong>Se admite</strong> a trámite el Expediente Técnico de la Auditoría citada al epígrafe. <br><br>
            <strong>TERCERO.</strong> Se ordena radicar, formar y registrar el Expediente de Seguimiento en el Libro de Gobierno de esta Unidad con el número progresivo <span style="color: blue;">{{$modelo->numero_expediente}}</span>.<br><br>

            <strong>CUARTO.</strong> En términos de los artículos 14 y 16 de la Constitución Política de los Estados Unidos Mexicanos; 53, 54 {{count($auditoria->accionesrecomendaciones)>0?' y 54 Bis':''}} de la Ley de Fiscalización Superior del Estado de México y; 6 fracciones XXV y XXXVII y 47 fracciones III, IV, XII y XIX del 
            Reglamento Interior del Órgano Superior de Fiscalización del Estado de México, se cita a  <span style="color: blue;">{{$auditoria->comparecencia->nombre_titular}}</span>, <span style="color: blue;">{{$auditoria->comparecencia->cargo_titular}}</span>, para que comparezca de manera personal por sí o a través de sus representantes legales o enlaces debidamente autorizados a las 
            <span style="color: blue;">{{strtolower($relacion4['hora01'])}}</span> horas <span style="color: blue;">, del día </span> <span style="color: blue;">{{strtolower($relacion4['day01'])}} {{strtolower($relacion4['mes01'])}} </span><span style="color: blue;">{{strtolower($relacion4['fechacomparecenciatxt'])}}</span>en el domicilio de la Unidad de Seguimiento de este Órgano Superior de 
            Fiscalización del Estado de México, sito en Avenida José María Pino Suárez Sur, números 104, 106 y 108, Colonia Cinco de Mayo, Toluca, Estado de México, C.P. 50090. 
            Lo anterior con el objeto <strong>de que se puntualicen las observaciones detalladas en el Informe de Auditoría y se ponga a la vista del compareciente el Expediente Técnico de la Auditoría citada en el acuerdo PRIMERO del presente. </strong> <br><br>

            Para el desahogo de la comparecencia a la que se le cita en términos del <strong>presente acuerdo</strong>, <u>únicamente se le dará acceso a  <span style="color: blue;">{{$auditoria->comparecencia->nombre_titular}}</span>, <span style="color: blue;">{{$auditoria->comparecencia->cargo_titular}}</span>, para o en su caso al representante legal o enlace administrativo, así como a <span style="color: blue;">{{strtolower($relacion4['ambito01'])}}</span> 
            personas, quienes deberán presentar identificación oficial vigente con fotografía y firma</u>, con el apercibimiento que para el caso de no comparecer el día y hora señalados en el presente acuerdo y, 
            en su caso, no acreditar debidamente la designación y/o autorización del representante legal o enlace administrativo, se tendrá por satisfecha dicha comparecencia. <br><br>

            Es importante precisar que, para el caso de señalar representante legal o enlace administrativo, este deberá estar debidamente designado y/o autorizado mediante oficio y/o escrito dirigido a la Auditora Superior de Fiscalización del Estado de México, con copia de conocimiento al Titular de la Unidad de Seguimiento y, que <u>deberá ser presentado</u> en la Oficialía de Partes del Órgano Superior de Fiscalización del Estado de México, <u>en un plazo de 24 horas
            previas al desahogo de la comparecencia de cuenta.</u> <br><br>
            
            <strong>QUINTO. Se ordena el inicio de la Etapa de Aclaración de las observaciones subsistentes en materia de Legalidad </strong> y, que se encuentran detalladas en el Informe de Auditoría; por lo cual, con fundamento en lo dispuesto en el artículo 54 fracción I de la Ley de Fiscalización Superior del Estado de México, se concede a la entidad fiscalizada un plazo de 30 (Treinta) días hábiles contados a partir del día <span style="color: blue;">{{strtolower($relacion4['day02'])}} {{strtolower($relacion4['mes02'])}}</span> 
            y que fenece el <span style="color: blue;">{{strtolower($relacion4['day03'])}} {{strtolower($relacion4['mes03'])}}</span>, a efecto de que se presenten los elementos, documentos y datos fehacientes que aclaren o solventen el contenido de las acciones de cuenta, o en su caso, manifieste lo que a su derecho convenga. <br><br>

            <strong>SEXTO. Para el Proceso de Atención a las Recomendaciones en materia de Legalidad</strong> que se encuentran detalladas en el Informe de Auditoría, con fundamento en lo dispuesto en el artículo 54 Bis fracción II de la Ley de Fiscalización Superior del Estado de México, se ordena dar seguimiento a las mismas, en el en el término de <span style="color: blue;">{{$auditoria->radicacion->plazo_maximo}}({{strtolower($relacion4['plazomaximo'])}})</span> días hábiles, plazo que fue convenido con el Órgano Superior de Fiscalización del Estado de México,
            detallado en el Acta de Reunión de Resultados Finales y Cierre de Auditoría <span style="color: blue;">{{strtolower($relacion4['cierre'])}}</span>, integrada en autos del expediente referido en el numeral Segundo del presente acuerdo, a efecto de precisen las mejoras realizadas y las acciones emprendidas en relación con las recomendaciones determinadas, o en su caso, justifique su improcedencia.<br><br>

            Derivado de lo anterior, en términos del artículo 42 Bis de la Ley de Fiscalización Superior del Estado de México, apercíbasele a la entidad fiscalizada para que en caso de no dar cumplimento a los términos y plazos de mérito, de manera pertinente, completa, veraz y que guarde plena relación con las observaciones de cuenta o presentar la información o documentación fuera de los plazos y formas convenidas, se aplicará el medio de apremio correspondiente señalado en el artículo 59 fracción II de la Ley de Fiscalización Superior del Estado de México, 
            que será equivalente a 100 veces el valor diario de la Unidad de Medida y Actualización (UMA) vigente, determinada por el Instituto Nacional de Estadística y Geografía, publicada el diez de enero de dos mil veinticinco, en el Diario Oficial de la Federación,<strong> que corresponde a la cantidad de $113.14 (Ciento trece pesos 14/100 M.N.) por día, que multiplicada por cien, asciende a un monto de $11,314.00 (Once mil trescientos catorce pesos 00/100 M.N.)</strong>. Y en caso de una conducta renuente y/o contumaz de incumplimiento que obstaculice el proceso de fiscalización, 
            además de imponer un nuevo medio de apremio que podrá alcanzar 1,500 veces el valor diario de la unidad de medida y actualización, se promoverán las responsabilidades de conformidad con la Ley General de Responsabilidades Administrativas, Ley de Responsabilidades Administrativas del Estado de México y Municipios, y demás legislación penal aplicable, lo anterior en términos del artículo 42 Bis de la Ley de Fiscalización Superior del Estado de México.<br><br>

            La información y/o documentación que exhiba la entidad fiscalizada en relación a las observaciones de mérito,<strong><u> deberá presentarse en medio impreso, digital y certificada.</u></strong> <br><br>
            
            <strong>SËPTIMO.</strong> En términos de Ley, notifíquese los acuerdos correspondientes a la entidad fiscalizada y al Órgano Interno de Control de <span style="color: blue;">{{strtolower($auditoria->entidad_fiscalizable)}}</span>, o a su equivalente. <br><br>

            Así lo acordó y firma Luis Ignacio Sierra Villa, Titular de la Unidad de Seguimiento del Órgano Superior de Fiscalización del Estado de México, a los <span style="color: blue;">{{$relacion4['fechaactual']}}</span>. <br><br>
        </p> <br><br><br><br><br><br><br>
        <hr>
        <p style="font-family: Arial; color: #000000; font-size: 8px; text-align: justify;">
            LISV/{{$relacion4['iniciales']}}*
        </p>      
    </div>
    <div style="page-break-before: always;">
        <p style="font-family: Arial; color: #000000; font-size: 14px; text-align: justify; page-break-inside: always;">
            
            @if (count($auditoria->accionespras)>0)  
                y al Órgano Interno de Control de <span style="color: blue;">{{$auditoria->entidad_fiscalizable}}</span>, o a su equivalente. <br><br>   
            @else
            o a su equivalente. <br><br>            
            @endif 
            Así lo acordó y firma Luis Ignacio Sierra Villa, Titular de la Unidad de Seguimiento del Órgano Superior de Fiscalización del Estado de México, a los <span style="color: blue;">{{fechaactualreporte()}}</span>.
        </p>
    </div>
    
@endsection
