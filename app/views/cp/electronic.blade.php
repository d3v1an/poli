@extends ('layout.admin')

<!-- Contenido -->
@section ('content')
    <div id="page-content">

        <!-- Blank Header -->
        <div class="content-header">
            <div class="header-section">
                <h1>
                    <i class="gi gi-wifi_alt"></i><span class="char-name">Electronicos</span><br><small>Captura de medios electronicos</small>
                </h1>
            </div>
        </div>
        <ul class="breadcrumb breadcrumb-top">
            <li>Panel de control</li>
            <li>Electronicos</li>
            <li>Captura</li>
        </ul>
        <!-- END Blank Header -->

        @if(Session::has('success_message'))
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="fa fa-check-circle"></i> Success</h4> {{ Session::get( 'success_message' ) }} 
        </div>
        @endif

        @if(Session::has('error_message'))
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="fa fa-times-circle"></i> Error</h4> {{ Session::get( 'error_message' ) }}
        </div>
        @endif

        <div class="block full">
            <!-- Block Tabs Title -->
            <div class="block-title">
                <div class="block-options pull-right">
                    <div class="btn-group btn-group-sm">
                        <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default dropdown-toggle enable-tooltip" data-toggle="dropdown" title="Opciónes"><span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                            <li>
                                <a href="javascript:void(0)" id="btn-add-nota">Nueva nota</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <ul class="nav nav-tabs" data-toggle="tabs">
                    <li class="active"><a href="#tab-main">Electronicos <span class="tab-main-count"></span></a></li>
                </ul>
            </div>
            <!-- END Block Tabs Title -->

            <!-- Tabs Content -->
            <div class="tab-content">

                <div class="tab-pane active" id="tab-main">
                    <div id="tab-res-main" class="panel-group">
                        
                        <!-- / -->
                        @if(count($enews)>0)
                        <?php $i=0; ?>
                        @foreach($enews as $e)
                        <div class="panel panel-default-d3">
                            <div class="panel-heading-d3">
                                <div class="widget">
                                    <div class="widget-d3">
                                        <!-- <a href="#tab_res_main_1" data-parent="#tab-res-main" data-toggle="collapse" class="widget-image-container pull-left">
                                            <img src="http://www.gaimpresos.com/img/portadas/thumbs/thumb-33.jpg" alt="El Informador" class="widget-image">
                                        </a> -->
                                        <div class="row pull-left">
                                            <div class="col-md-12">
                                                <a href="#tab_res_main_{{ $i+1 }}" data-parent="#tab-res-main" data-toggle="collapse" class="notelink">
                                                <dl>
                                                    <dt>{{ $e->title }}</dt>
                                                    <dt>{{ $e->program->name }} | <i class="gi gi-clock"></i> {{ $e->date}} {{ $e->hour }}</dt>
                                                    <dd>
                                                        <span class="label label-success"># {{ $i+1 }}</span>
                                                        <span class="label label-danger">ID : {{ $e->id }}</span>
                                                        <span class="label label-info">Fuente : {{ $e->program->source->name }}</span>
                                                        <span class="label label-info">Autor : {{ $e->comunicator->name }}</span>
                                                    </dd>
                                                </dl>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-collapse collapse" id="tab_res_main_{{ $i+1 }}">
                                <div class="panel-body">
                                    <!-- <div class="widget-extra-full mb7">
                                        <div class="btn-group">
                                            <button data-id="{{ $e->id }}" data-type="analytic" title="Analizar nota" data-toggle="tooltip" class="btn btn-default btn-tool" disabled><i class="fa fa-stethoscope"></i></button>
                                        </div>
                                    </div> -->
                                    <div class="row">
                                        
                                        <div class="col-md-3 right-border">

                                            @if( strpos($e->type,'video') !== false)
                                            <div class="maxvidsize">
                                                <video id="sampleMovie" width="100%" controls class="" style="margin:5px;" preload="none">
                                                    <source src="/uploads/{{ $e->file }}" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' />
                                                </video>
                                                <p><button class="btn btn-xs btn-success download-media" type="button" data-media="{{ $e->file }}">Descargar</button></p>
                                            </div>
                                            @else
                                            <div class="maxvidsize">
                                                <audio src="/uploads/{{ $e->file }}" width="100%" controls class="" style="margin:5px;" preload="none"></audio>
                                                <p><button class="btn btn-xs btn-success download-media" type="button" data-media="{{ $e->file }}">Descargar</button></p>
                                            </div>
                                            @endif

                                        </div>
                                        <div class="col-md-9"> 
                                            <dl>
                                                <dt>Encabezado</dt>
                                                <dd>{{ $e->header }}</dd>
                                                <dt>Nota</dt>
                                                <dd class="text-justify">{{ $e->note }}</dd>
                                                <dt>Calificación</dt>
                                                <dd>
                                                    @if( count($e->audit->pieces) > 0 )
                                                    <table class="table table-borderless table-striped table-condensed table-vcenter" id="table-badgets">
                                                        <tbody>
                                                        @foreach($e->audit->pieces as $p)
                                                            <tr id="_a_{{ $p->id }}">
                                                                <td style="width: 180px;" class="_c_actor">{{ $p->actor->name }}</td>
                                                                <td><strong class="_c_topic">{{ $p->topic->text }}</strong></td>
                                                                <td><strong class="_c_type">{{ $p->type->name }}</strong></td>
                                                                <td class="text-center _c_status" style="width: 70px;"><span class="label label-{{ $p->status=='p'? 'success': ($p->status=='n'? 'danger':'default') }}">{{ $p->status=='p'? 'Positiva': ($p->status=='n'? 'Negativa':'Neutral') }}</span></td>
                                                                <td class="text-right" style="width: 70px;">
                                                                    <div class="btn-group btn-group-xs">
                                                                        <button class="btn btn-xs btn-default btn-edit" data-pid="{{ $p->id }}" data-actor="{{ $p->actor_id }}" data-topic="{{ $p->topic_id }}" data-type="{{ $p->type_id }}" data-status="{{ $p->status }}"><i class="gi gi-pencil"></i></button>
                                                                        <button class="btn btn-xs btn-danger btn-delete" data-id="{{ $e->id }}" data-pid="{{ $p->id }}"><i class="gi gi-remove_2"></i></button>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                    @endif
                                                </dd>
                                            </dl>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php $i++; ?>
                        @endforeach
                        @endif
                        <!-- // -->

                    </div>
                </div>
            </div>
            <!-- END Tabs Content -->
        </div>

    </div>
@stop
<!-- /Contenido -->

<!-- Dialogos -->
@section ('dialogs')
<!-- Dialogo de analisis -->
<div id="modal-note" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="modal-analytic-title">Nueva nota de electronicos</h3>
            </div>
            <div class="modal-body" id="modal-analytic-body">
                

                <!-- Select Components Content -->
                <form action="/cp/electronic/add_note" id="note-detaill" name="note-detaill" method="post" class="form-horizontal form-bordered" enctype="multipart/form-data">
                    <input type="hidden" id="meta" name="meta">
                    <fieldset>

                        <!-- fuente -->
                        <div class="form-group">
                            <div class="col-md-4">
                                <select id="note-program" name="note-program" class="form-control">
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="_note_source" name="_note_source" readonly placeHolder="Canal..">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" id="_note_conductor" name="_note_conductor" readonly placeHolder="Condictor..">
                            </div>
                        </div>
                        <!-- /Fuente -->

                        <!-- Autor -->
                        <div class="form-group">
                            <div class="col-md-4">
                                <select id="note-autor" name="note-autor" class="form-control">
                                </select>
                            </div>
                            <label class="col-md-2 control-label" for="to_actor">Actor</label>
                            <div class="col-md-6">
                                <select id="to_actor" name="to_actor" class="form-control">
                                </select>
                            </div>
                        </div>
                        <!-- /Autor -->

                        <!-- Titulo -->
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="note-title">Titulo</label>
                            <div class="col-md-10">
                                <input type="text" id="note-title" name="note-title" class="form-control">
                            </div>
                        </div>
                        <!-- /Titulo -->

                        <!-- Encabezado -->
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="note-header">Encabezado</label>
                            <div class="col-md-10">
                            <textarea id="note-header" name="note-header" class="form-control" rows="3" style="resize: none;"></textarea>
                            </div>
                        </div>
                        <!-- /Encabezado -->

                        <!-- Nota -->
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="noteText">Nota</label>
                            <div class="col-md-10">
                            <textarea id="noteText" name="noteText" class="form-control ckeditor" rows="5"></textarea>
                            </div>
                        </div>
                        <!-- /Nota -->

                         <!-- Titulo -->
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="note-fecha">Fecha</label>
                            <div class="col-md-3">
                                <input type="text" id="note-fechar" name="note-fecha" class="form-control input-datepicker" data-date-format="yyyy-mm-dd" placeholder="yyyy-mm-dd">
                            </div>
                            <label class="col-md-2 control-label" for="note-hora">Hora</label>
                            <div class="col-md-3">
                                <div class="input-group bootstrap-timepicker">
                                    <input type="text" id="note-hora" name="note-hora" class="form-control input-timepicker24">
                                    <span class="input-group-btn">
                                        <a href="javascript:void(0)" class="btn btn-primary"><i class="fa fa-clock-o"></i></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- /Titulo -->

                        <!-- Titulo -->
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="note-title">Archivo</label>
                            <div class="col-md-3">
                                <input type="file" id="note-file" name="note-file">
                            </div>
                            <label class="col-md-2 control-label" for="note-media-type">Tipo</label>
                            <div class="col-md-3">
                                <select id="note-media-type" name="note-media-type" class="form-control">
                                    <option value="video">Video</option>
                                    <option value="audio">Audio</option>
                                </select>
                            </div>
                        </div>
                        <!-- /Titulo -->

                    </fieldset>
                    <!-- Select2 plugin (class is initialized in js/app.js -> uiInit()), for extra usage examples you can check out http://ivaynberg.github.io/select2/ -->
                    <fieldset>
                        <legend><i class="fa fa-angle-right"></i> Análisis</legend>
                        <div class="form-group">
                            <table class="table table-borderless table-striped table-condensed table-vcenter" id="table-badgets">
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            
                            <div class="col-md-3">
                                <select id="note-actor" name="note-actor" class="select-chosen form-control" data-placeholder="Actor..">
                                <option></option><!-- Required for data-placeholder attribute to work with Chosen plugin -->
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select id="note-topic" name="note-topic" class="select-chosen form-control" data-placeholder="Tema..">
                                    <option></option><!-- Required for data-placeholder attribute to work with Chosen plugin -->
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select id="note-type" name="note-type" class="select-chosen form-control" data-placeholder="Tipo..">
                                    <option></option><!-- Required for data-placeholder attribute to work with Chosen plugin -->
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select id="note-calification" name="note-calification" class="form-control" size="1">
                                    <option value="p">Positiva</option>
                                    <option value="n">Negativa</option>
                                    <option value="nn">Neutra</option>
                                </select>
                            </div>

                            <div class="col-md-1">
                                <button class="btn btn-sm btn-success" id="btn-add-info"><i class="fa fa-check"></i></button>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="col-md-12">
                            <!-- Default Tabs -->
                            <ul class="nav nav-tabs push" data-toggle="tabs">
                                <li class="active"><a href="#e-tabs-actores">Actores</a></li>
                                <li><a href="#e-tabs-temas">Temas</a></li>
                                <li><a href="#e-tabs-tipos">Tipos</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="e-tabs-actores">
                                    <div class="form-group">
                                        <label for="new-actor" class="col-md-2 control-label">Actor</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="new-actor" id="new-actor">
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-sm btn-primary btn-add-actor"><i class="fa fa-angle-right"></i> Agregar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="e-tabs-temas">
                                    <div class="form-group">
                                        <label for="new-tema" class="col-md-2 control-label">Tema</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="new-tema" id="new-tema">
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-sm btn-primary btn-add-tema"><i class="fa fa-angle-right"></i> Agregar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="e-tabs-tipos">
                                    <div class="form-group">
                                        <label for="new-tipo" class="col-md-2 control-label">Tipo</label>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="new-tipo" id="new-tipo">
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-sm btn-primary btn-add-tipo"><i class="fa fa-angle-right"></i> Agregar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END Default Tabs -->
                        </div>
                    </fieldset>

                </form>
                <!-- END Select Components Content -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-add-audit">Guardar</button>
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- /Dialogo de analisis -->

<!-- Dialogo de calificacion -->
<div id="modal-calificacion" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="modal-analytic-title">Modificacion de calificacion</h3>
            </div>
            <div class="modal-body" id="modal-analytic-body">
                <!-- Select Components Content -->
                <form action="/cp/electronic/add_note" id="form-note-calification" name="form-note-calification" method="post" class="form-horizontal form-bordered" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="pid" name="pid">
                    <div class="form-group">
                        <div class="col-md-3">
                            <select id="c-note-actor" name="c-note-actor" class="form-control">
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="c-note-topic" name="c-note-topic" class="form-control">
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="c-note-type" name="c-note-type" class="form-control">
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="c-note-status" name="c-note-status" class="form-control" size="1">
                                <option value="p">Positiva</option>
                                <option value="n">Negativa</option>
                                <option value="nn">Neutra</option>
                            </select>
                        </div>
                    </div>
                </form>
                <!-- END Select Components Content -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-edit-audit">Guardar</button>
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- /Dialogo de calificacion -->
@stop
<!-- /Dialogos -->

<!-- Scripts adicionales -->
@section ('scripts')
    @parent
    <!-- DATA TABES SCRIPT -->
    {{ HTML::script('js/pages/tablesDatatables.js') }}
    {{ HTML::script('js/moment-wl.js') }}
    {{ HTML::script('js/ckeditor/ckeditor.js') }}
    {{ HTML::script('js/pages/electronicos.js') }}
@stop