@extends('layouts.reportedoc')
@section('Titulo')
Registro del acuerdo de radicación
{{-- Radicacion desempeño --}}
@endsection
@section('content')
<div>
        <p style="font-family: Arial; color: #000000; font-size: 14px; text-align: justify;"> 
            V I S T O el memorándum número <span style="color: blue;">{{$modelo->num_memo_recepcion_expediente}}</span>, presentado por <span style="color: blue;">{{$auditoria->tipo_auditoria->nombre_ae}}</span>, <span style="color: blue;">"Auditor Especial de  {{(str_contains($auditoria->tipo_auditoria->nombre_ae,)?'Titular de la Auditoría Especial de Desempeño y Legalidad' )}}" </span> del Órgano Superior de Fiscalización del Estado de México, 
            por medio del cual remite a la Unidad de Seguimiento el Expediente Técnico derivado de la Auditoría <span style="color: blue;">{{$auditoria->tipo_auditoria->descripcion}}</span> practicada a <span style="color: blue;">{{ $auditoria->entidad_fiscalizable}}</span>, 
            por el período comprendido <span style="color: blue;">{{$auditoria->periodo_revision}}</span>, así como, el Informe de Auditoría correspondiente, en el que se presentan los datos que identifican los resultados finales obtenidos con las observaciones determinadas a la citada entidad fiscalizada; 
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
            <strong>SEGUNDO.</strong> <strong>Se admite</strong> a trámite el Expediente Técnico de la Auditoría citada al epígrafe, el cual contiene el Informe de Resultados Finales correspondiente. <br><br>
            <strong>TERCERO.</strong> Se ordena radicar, formar y registrar el Expediente de Seguimiento en el Libro de Gobierno de esta Unidad con el número progresivo <span style="color: blue;">{{$modelo->numero_expediente}}</span> y túrnese a la Dirección de Seguimiento competente. <br><br>
            <strong>CUARTO.</strong> Notifíquese mediante oficio a la entidad fiscalizada, el Informe de Auditoría de mérito, para su conocimiento y efectos legales a que haya lugar. <br><br>
            <strong>QUINTO.</strong> En términos de los articulos 14 y 16 de la Constitución Política de los Estados Unidos Mexicanos; 53 y 54 Bis de la Ley de Fiscalización del Estado de México y ; 6 fracciones XXV y XXXXVII y 47 fracciones III, IV, XII y XIX del Reglamento Interno del Órgano Superior de Fiscalización del Estado de México, se cita a <span style="color: blue;">{{ $auditoria->entidad_fiscalizable}}</span>, para que comparezca de manera 
            personal por sí o a través de sus representantes legales o enlaces debidamente autorizados a las (HORA); en el domicilio de la Unidad de Seguimiento de este Órgano Superior de Fiscalización del Estado de México, sito en Avenida José María Pino Suárez Sur, números 104, 106 y 108, Colonia Cinco de Mayp, Toluca, Estado de México, C.P. 50090. Lo anterior con el objeto de que se puntualicen las recomendaciones detalladas del Informe de Auditoría a 
            que se alude en el acuerdo TERCERO  del presente y para su consulta, se ponga a la vista del compareciente el expediente téxcino de la auditoría citada en el acuerdo PRIMERO del presente.
             
            Para el desahogo de la comparecencia a la que se le cita en términos del presente acuerdo, se deberá presentar identificación oficical vigente con fotografía y firma, con el apercibimiento que para el caso de no comparecer el día y hora señalados en el presente acuerdo y, en su caso, no acreditar debidamente la designación y/o autorización de los representantes legales o enlaces administrativos, se tendrá por satisfecha dicha comparecencia.
           
            Es importante precisar que, para el caso de señalar representantes legales o enlaces administrativos, estos deberán estar debidamente designados y/o autorizados mediante oficio y/o escrito dirigido a la Auditora Superior de Fiscalización del Estado de México, con copia de conocimiento al Titular de la Unidad de Seguimiento y, que deberá ser presentado en la Oficialía de Partes del Órgano Superior de Fiscalización del Estado de México, en un plazo
            de 24 horas previas al desahogo de la comparecencia de cuenta.

            Es importante precisar que, para el caso de señalar representantes legales o enlaces administrativos, estos deberán estar debidamente designados y/o autorizados mediante oficio y/o escrito dirigido a la Auditora Superior de Fiscalización del Estado de México, con copia de conocimiento al Titular de la Unidad de Seguimiento y, 
            que deberá ser presentado en la Oficialía de Partes del Órgano Superior de Fiscalización del Estado de México, en un plazo de 24 horas previas al desahogo de la comparecencia de cuenta. <br><br>

            Cabe señalar que, para el caso de que se designen y/o autoricen a dos o más personas, se deberá designar en el oficio y/o escrito antes mencionado, a un representante común de entre ellas, de no hacerse el nombramiento en comento, esta autoridad considerará como representante común a la persona señalada en primer término. <br><br>            
            
            <strong>SEXTO.</strong> Se ordena el inicio del Proceso de Atención {{count($auditoria->accionesrecomendaciones)>0?'a las Recomendaciones,'''}} en materia de Desempeño que se encuentran de la Ley de Fiscalización Superior del Estado de México, se ordena dar seguimiento a las mismas, en el término de XXX días hábiles, plazo que fue convenido con el Órgano Superior de Fiscalización del Estado de México, detallado en el Acta de Reunión de Resultados
            Finales y Cierre de Auditoría XXX , integrada en autos del expediente referido en el numeral Segundo del presente acuerdo. 

            Derivado de lo anterior, en términos del artículo 42 Bis de la Ley de Fiscalización Superior del Estado de México, se apercibe para que en caso de no dar cumplimento a los términos y plazos de mérito, de manera pertinente, completa, veraz y que guarde plena relación con las recomendaciones de cuenta o presentar la información o documentación fuera de los plazos y formas convenidas, se aplicará el medio de apremio correspondiente señalado en el 
            artículo 59 fracción II de la Ley de Fiscalización Superior del Estado de México, que será equivalente a 100 veces el valor diario de la Unidad de Medida y Actualización (UMA) vigente, determinada por el Instituto Nacional de Estadística y Geografía, publicada el diez de enero de dos mil veinticuatro, en el Diario Oficial de la Federación, que corresponde a la cantidad de $108.57 (Ciento ocho pesos 57/100 M.N.) por día, que multiplicada por cien, 
            asciende a un monto de $10,857.00 (Diez mil ochocientos cincuenta y siete pesos 00/100 M.N.). Y en caso de una conducta renuente y/o contumaz de incumplimiento que obstaculice el proceso de fiscalización, además de imponer un nuevo medio de apremio que podrá alcanzar 1,500 veces el valor diario de la unidad de medida y actualización, se promoverán las responsabilidades de conformidad con la Ley General de Responsabilidades Administrativas, Ley de 
            Responsabilidades Administrativas del Estado de México y Municipios, y demás legislación penal aplicable, lo anterior en términos del artículo 42 Bis de la Ley de Fiscalización Superior del Estado de México.
            
            La información y/o documentación que exhiba la entidad fiscalizada en relación a las recomendaciones de mérito, deberá presentarse en medio impreso, digital y certificada. <br><br>                        

            <strong>OCTAVO.</strong>En términos de Ley, notifiquese el presente proveído a la entidad fiscalizada y al Órgano Interno de Control de XXX, o a su equivalente. 
            
            <span style="color: blue;">{{$auditoria->tipo_auditoria->descripcion}}</span> que se encuentran detalladas en el multicitado Informe de Auditoría, con fundamento en lo dispuesto en el artículo 54 Bis fracción II  de la Ley de Fiscalización Superior del Estado de México, 
            se ordena dar seguimiento a las mismas, en el término de <span style="color: blue;">{{$modelo->plazo_maximo}} ({{$relacion4['plazomaximo']}})</span> días hábiles, plazo que fue convenido con el Órgano Superior de Fiscalización del Estado de México, detallado en el Acta de Reunión de Resultados Finales y Cierre de Auditoría: <span style="color: red;">XXXXX</span>, integrada en autos del expediente referido en el numeral Segundo del presente acuerdo. <br><br>
        </p> <br><br><br><br><br><br><br><br>
        <hr>
        <p style="font-family: Arial; color: #000000; font-size: 8px; text-align: justify;">           
            Artículo 54 Bis. Con relación a las recomendaciones, el proceso de su atención se desarrollará de la siguiente manera: - - - II. La información, documentación o consideraciones aportadas por las entidades fiscalizadas para atender las recomendaciones en los plazos convenidos, deberán precisar las mejoras realizadas y las acciones emprendidas. En caso contrario, deberán justificar su improcedencia. <br>                              
        </p>      
    </div>
    <div>
        <p>
            Así lo acordó y firma Luis Ignacio Sierra Villa, Titular de la Unidad de Seguimiento del Órgano Superior de Fiscalización del Estado de México, a los <span style="color: blue;">{{fechaactualreporte()}}</span>.
        </p>
    </div>
    
@endsection
