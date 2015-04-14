@extends ('layout.admin')

<!-- Contenido -->
@section ('content')
    <div id="page-content">
        <!-- Blank Header -->
        <div class="content-header">
            <div class="header-section">
                <h1>
                    <i class="gi gi-user"></i><span class="char-name"></span><br><small>Numeo de notas [<span class="char-note-counter"></span>]</small>
                </h1>
            </div>
        </div>
        <ul class="breadcrumb breadcrumb-top">
            <li>Panel de control</li>
            <li>Personajes</li>
        </ul>
        <!-- END Blank Header -->

        <div class="block full">
            <!-- Block Tabs Title -->
            <div class="block-title">
                @if(Auth::user()->role_id <= 1)
                <div class="block-options pull-right">
                    <div class="btn-group btn-group-sm">
                        <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default dropdown-toggle enable-tooltip" data-toggle="dropdown" title="Opciónes"><span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                            <li>
                                <a href="javascript:void(0)" id="btn-add-actor">Nuevo Actor</a>
                                <a href="javascript:void(0)" id="btn-add-tema">Nuevo Tema</a>
                                <a href="javascript:void(0)" id="btn-add-type">Nuevo Tipo</a>
                            </li>
                        </ul>
                    </div>
                </div>
                @endif
                <ul class="nav nav-tabs" data-toggle="tabs">
                    <li class="active"><a href="#tab-main">Jalisco <span class="tab-main-count"></span></a></li>
                    <li><a href="#tab-estados">D.F. / Estados <span class="tab-estados-count"></span></a></li>
                    <li><a href="#tab-revistas">Revistas <span class="tab-revistas-count"></span></a></li>
                    <li><a href="#tab-portales">Portales <span class="tab-portales-count"></span></a></li>
                </ul>
            </div>
            <!-- END Block Tabs Title -->

            <!-- Tabs Content -->
            <div class="tab-content">

                <div class="tab-pane active" id="tab-main">
                    <div id="tab-res-main" class="panel-group">
                    </div>
                </div>

                <div class="tab-pane" id="tab-estados">
                    <div id="tab-res-estados" class="panel-group">
                    </div>
                </div>

                <div class="tab-pane" id="tab-revistas">
                    <div id="tab-res-revistas" class="panel-group">
                    </div>
                </div>
                <div class="tab-pane" id="tab-portales">
                    <div id="tab-res-portales" class="panel-group">
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
<!-- Dialogo de documento -->
<div id="modal-doc" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="modal-doc-title"></h3>
            </div>
            <div class="modal-body" id="modal-doc-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- /Dialogo de documento -->

<!-- Dialogo de analisis -->
<div id="modal-analytic" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="modal-analytic-title">Análisis de nota</h3>
            </div>
            <div class="modal-body" id="modal-analytic-body">
                

                <!-- Select Components Content -->
                <form action="#" id="note-detaill" name="note-detaill" method="post" class="form-horizontal form-bordered" onsubmit="return false;">

                    <input type="hidden" id="note_id" name="note_id">
                    <input type="hidden" id="meta" name="meta">
                    <fieldset>

                        <legend><span class="label pull-right" id="label-audit"></span><i class="fa fa-angle-right"></i> Periodico - <span class="text-muted" id="label-fecha"></span></legend>

                        <!-- Autor -->
                        <div class="form-group">
                            <div class="col-md-12 text-center">
                                <span class="label label-info" id="nd-autor"></span> 
                                <span class="label label-info" id="nd-section">Seccion : N/D</span>
                                <span class="label label-info" id="nd-category">Categoria : N/D</span>
                                <span class="label label-info" id="nd-page">Pagina : N/D</span>
                            </div>
                        </div>
                        <!-- /Autor -->

                        <!-- Titulo -->
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="note-title">Titulo</label>
                            <div class="col-md-10">
                                <input type="text" id="note-title" name="note-title" class="form-control" readonly>
                            </div>
                        </div>
                        <!-- /Titulo -->

                        <!-- Encabezado -->
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="note-header">Encabezado</label>
                            <div class="col-md-10">
                            <textarea id="note-header" name="note-header" class="form-control" rows="3" readonly style="resize: none;"></textarea>
                            </div>
                        </div>
                        <!-- /Encabezado -->

                        <!-- Nota -->
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="note-text">Nota</label>
                            <div class="col-md-10">
                            <textarea id="note-text" name="note-text" class="form-control" rows="5" readonly></textarea>
                            </div>
                        </div>
                        <!-- /Nota -->

                        <!-- Pie de pagina -->
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="note-pie">Pie de pagina</label>
                            <div class="col-md-10">
                                <input type="text" id="note-pie" name="note-pie" class="form-control" readonly>
                            </div>
                        </div>
                        <!-- /Pie de pagina -->

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
                                <select id="note-actor" name="note-actor" class="select-chosen" data-placeholder="Actor..">
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

<!-- Dialogo de documento -->
<div id="modal-actor" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="modal-actor-title">Nuevo actor</h3>
            </div>
            <div class="modal-body">
                 <form action="#" id="form-actor" name="form-actor" method="post" class="form-horizontal form-bordered" onsubmit="return false;">
                     <!-- Titulo -->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="actor-name">Actor</label>
                        <div class="col-md-10">
                            <input type="text" id="actor-name" name="actor-name" class="form-control">
                        </div>
                    </div>
                    <!-- /Titulo -->
                 </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-new-actor">Agregar</button>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- /Dialogo de documento -->

<!-- Dialogo de documento -->
<div id="modal-tema" class="modal fade" tabindex="1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="modal-tema-title">Nuevo tema</h3>
            </div>
            <div class="modal-body">
                 <form action="#" id="form-tema" name="form-tema" method="post" class="form-horizontal form-bordered" onsubmit="return false;">
                     <!-- Titulo -->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="actor-tema">Tema</label>
                        <div class="col-md-10">
                            <input type="text" id="actor-tema" name="actor-tema" class="form-control">
                        </div>
                    </div>
                    <!-- /Titulo -->
                 </form>
            </div> 
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-new-tema">Agregar</button>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- /Dialogo de documento -->

<!-- Dialogo de documento -->
<div id="modal-type" class="modal fade" tabindex="1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title" id="modal-type-title">Nuevo tipo</h3>
            </div>
            <div class="modal-body">
                 <form action="#" id="form-type" name="form-type" method="post" class="form-horizontal form-bordered" onsubmit="return false;">
                     <!-- Titulo -->
                    <div class="form-group">
                        <label class="col-md-2 control-label" for="actor-type">Tipo</label>
                        <div class="col-md-10">
                            <input type="text" id="actor-type" name="actor-type" class="form-control">
                        </div>
                    </div>
                    <!-- /Titulo -->
                 </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-success" id="btn-new-type">Agregar</button>
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- /Dialogo de documento -->

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
    {{ HTML::script('js/pages/characters.js') }}
    <script>
        var _res_ip             = '{{ Config::get('rest.ip') }}';
        var _current_character  = {{ $active }};
        $(function(){
            CharacterData.init();
            CharacterData.load(_current_character);
            CharacterData.actors();
            CharacterData.themes();
            CharacterData.types();
        });
    </script>
@stop
