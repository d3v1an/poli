@extends ('layout.admin')

<!-- Contenido -->
@section ('content')
<div id="page-content">
    <!-- Inbox Header -->
    <div class="content-header">
        <div class="header-section">
            <h1><i class="gi gi-notes_2"></i> {{ $actor->name }}<br><small>Reporte de auditorias en electronicos</small></h1>
        </div>
    </div>
    <ul class="breadcrumb breadcrumb-top">
        <li>Panel de control</li>
        <li>Reportes</li>
        <li>Electronicos</li>
    </ul>
    <!-- END Inbox Header -->

    <!-- Inbox Content -->
    <div class="row">
        <!-- Inbox Menu -->
        <div class="col-sm-4 col-lg-3">
            <!-- Menu Block -->
            <div class="block full">
                <!-- Menu Title -->
                <div class="block-title clearfix">
                    <div class="block-options pull-left">
                        Personajes
                    </div>
                </div>
                <!-- END Menu Title -->

                <!-- Menu Content -->
                <ul class="nav nav-pills nav-stacked">
                    @foreach ($actors as $actor)
                        <li>
                            <a href="{{ URL::to('cp/report/electronics/' . $actor->id) }}">
                                <span class="badge pull-right">{{ $actor->enews->count() }}</span>
                                <i class="fa fa-angle-right fa-fw"></i> <strong>{{ $actor->name }}</strong>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <!-- END Menu Content -->
            </div>
            <!-- END Menu Block -->

        </div>
        <!-- END Inbox Menu -->

        <!-- Messages List -->
        <div class="col-sm-8 col-lg-9">
            <!-- Messages List Block -->
            <div class="block">
                <!-- Messages List Title -->
                <div class="block-title">
                    <h2>Noticias auditadas</h2>
                    <div class="block-options pull-right">
                        <div class="btn-group btn-group-sm">
                            <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default dropdown-toggle enable-tooltip" data-toggle="dropdown" title="Opciónes"><span class="caret"></span></a>
                            <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                                <li>
                                    <a href="javascript:void(0)" id="btn-data-range">Rango de fechas</a>
                                </li>
                                <!--
                                <li class="dropdown-header">Exportar</li>
                                <li>
                                    <a href="javascript:void(0)" id="btn-export" data-type="excel" data-actor="{{ $aid }}">Excel</a>
                                </li>
                                -->
                            </ul>
                        </div>
                        <!-- <a class="btn btn-sm btn-default" title="" data-toggle="tooltip" href="{{ URL::to('cp/excel/export/' . $aid) }}" data-original-title="Exportar a Excel">
                            <i class="gi gi-disk_save"></i>
                        </a> -->
                    </div>
                </div>
                <!-- END Messages List Title -->


                <!-- Messages List Content -->
                <div class="table-responsive">
                    <table class="table table-vcenter table-striped table-condensed table-report">
                        <thead>
                            <tr>
                                <th style="width: 30px;" class="text-center"><input type="checkbox"></th>
                                <th style="width: 150px;">Fecha</th>
                                <th>Programa</th>
                                <th>Nota</th>
                                <th class="text-center">Titulo</th>
                                <th>Actores</th>
                                <th>Auditor</th>
                                <th>Calificación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=0; ?>
                            @foreach($audits as $audit)
                            <?php
                                $tmp_actors = array();
                                foreach ($audit->audit->pieces as $p) {
                                    if(!in_array($p->actor->name, $tmp_actors)) {
                                        array_push($tmp_actors,$p->actor->name);
                                    }
                                }
                            ?>
                            <tr>
                                <td class="text-center"><input type="checkbox" id="checkbox1-{{ $i+1 }}" name="checkbox1-{{ $i+1 }}" data-id="{{ $audit->id }}"></td>
                                <td>{{ $audit->created_at }}</td>
                                <td>{{ $audit->program->name }}</td>
                                <td><button data-content="{{ strip_tags($audit->note) }}" class="btn btn-xs btn-success btn-note">Nota</button></td>
                                <td class="text-center">{{ $audit->title }}</td>
                                <td><button title="" data-placement="top" data-html="true" data-content="{{ implode('</br>',$tmp_actors) }}" data-toggle="popover" data-trigger="hover" class="btn btn-xs btn-warning">({{ count($tmp_actors) }}) Actores</button></td>
                                <td>{{ $audit->audit->user->username }}</td>
                                <td><button data-note="{{ $audit->id }}" class="btn btn-xs btn-primary btn-cal">Calificación</button></td>
                            </tr>
                            <?php $i++; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- END Messages List Content -->


            </div>
            <!-- END Messages List Block -->
        </div>
        <!-- END Messages List -->
    </div>
    <!-- END Inbox Content -->

</div>
@stop
<!-- /Contenido -->

<!-- Dialogos -->
@section ('dialogs')
    <!-- Dialogo de nota -->
    <div id="modal-note" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title" id="modal-note-title"></h3>
                </div>
                <div class="modal-body" id="modal-note-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Dialogo de nota -->

    <!-- Dialogo de temas -->
    <div id="modal-topic" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title" id="modal-topic-title"></h3>
                </div>
                <div class="modal-body" id="modal-topic-body">
                    <table class="table table-borderless table-striped table-condensed table-vcenter" id="table-badgets">
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Dialogo de temas -->

    <!-- Rango de fechas -->
    <div id="modal-data-range" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title" id="modal-note-title">Filtro por rango de fecha</h3>
                </div>
                <div class="modal-body" id="modal-note-body">
                    <form action="/cp/report/printed/" id="form-data-range" name="form-data-range" method="post" class="form-horizontal form-bordered">
                        <input type="hidden" id="aid" name="aid" value="{{ $aid }}">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Seleccion de rango</label>
                            <div class="col-md-9">
                                <div class="input-group input-daterange" data-date-format="yyyy-mm-dd">
                                    <input type="text" id="data-range-init" name="data-range-init" class="form-control text-center" placeholder="Inicial">
                                    <span class="input-group-addon"><i class="fa fa-angle-right"></i></span>
                                    <input type="text" id="data-range-end" name="data-range-end" class="form-control text-center" placeholder="Final">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-success goto-range">Filtrar</button>
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Dialogo de nota -->
@stop
<!-- /Dialogos -->

<!-- Scripts adicionales -->
@section ('scripts')
    @parent
    
    {{ HTML::script('js/pages/ereports.js') }}

    <script>
        <?php
            $tt = array();
            foreach ($audits as $a) {
                $tt["_".$a->id] = array();
                foreach ($a->audit->pieces as $p) {
                    $t = array();
                    $t['actor']   = $p->actor->name;
                    $t['tema']    = $p->topic->text;
                    $t['type']    = $p->type->name;
                    $t['status']  = $p->status;
                    array_push($tt["_".$a->id],$t);
                }
            }
        ?>
        var _audits = Array();
            _audits = {{ json_encode($tt) }};
        ReportData.init();
    </script>
@stop