@extends ('layout.admin')

<!-- Contenido -->
@section ('content')
<div id="page-content">
    <!-- Inbox Header -->
    <div class="content-header">
        <div class="header-section">
            <h1><i class="gi gi-book"></i> Catalogos<br><small>Informacion miscelánea</small></h1>
        </div>
    </div>
    <ul class="breadcrumb breadcrumb-top">
        <li>Panel de control</li>
        <li>Catalogos</li>
    </ul>
    <!-- END Inbox Header -->

    <!-- Inbox Content -->
    <div class="row">

        <!-- Fuentes (Canales) -->
        <div class="col-sm-4 col-lg-3">
            <!-- Menu Block -->
            <div class="block full">
                <!-- Menu Title -->
                <div class="block-title clearfix">
                    <div class="block-options pull-left">
                        Fuentes
                    </div>
                    <div class="block-options pull-right">
                        <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" id="add_fuente" data-toggle="tooltip" title="Agregar fuente"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
                <!-- END Menu Title -->

                <!-- Responsive Full Content -->
                <div class="table-responsive">
                    <table class="table table-vcenter table-striped tsources">
                        <thead>
                            <tr>
                                <th>Fuente</th>
                                <th style="width: 150px;" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- END Responsive Full Content -->
            </div>
            <!-- END Menu Block -->
        </div>
        <!-- END Fuentes (Canales) -->

        <!-- Programas -->
        <div class="col-sm-4 col-lg-6">
            <!-- Menu Block -->
            <div class="block full">
                <!-- Menu Title -->
                <div class="block-title clearfix">
                    <div class="block-options pull-left">
                        Programas
                    </div>
                    <div class="block-options pull-right">
                        <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" id="add_programa" data-toggle="tooltip" title="Agregar programa"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
                <!-- END Menu Title -->

                <!-- Responsive Full Content -->
                <div class="table-responsive">
                    <table class="table table-vcenter table-striped tprogram">
                        <thead>
                            <tr>
                                <th>Fuente</th>
                                <th>Programa</th>
                                <th>Comunicador</th>
                                <th style="width: 150px;" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- END Responsive Full Content -->
            </div>
            <!-- END Menu Block -->
        </div>
        <!-- END Programas -->

        <!-- Comunicadores -->
        <div class="col-sm-4 col-lg-3">
            <!-- Menu Block -->
            <div class="block full">
                <!-- Menu Title -->
                <div class="block-title clearfix">
                    <div class="block-options pull-left">
                        Comunicadores
                    </div>
                    <div class="block-options pull-right">
                        <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default" id="add_comunicador" data-toggle="tooltip" title="Agregar comunicador"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
                <!-- END Menu Title -->

                <!-- Responsive Full Content -->
                <div class="table-responsive">
                    <table class="table table-vcenter table-striped tcomunicators">
                        <thead>
                            <tr>
                                <th>Comunicador</th>
                                <th style="width: 150px;" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- END Responsive Full Content -->
            </div>
            <!-- END Menu Block -->
        </div>
        <!-- END Comunicadores -->

    </div>

</div>
@stop
<!-- /Contenido -->

<!-- Dialogos -->
@section ('dialogs')
    
    <!-- Dialogo de nota -->
    <div id="modal-fuente" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title" id="modal-fuente-title">Fuente</h3>
                </div>
                <div class="modal-body" id="modal-note-body">
                    <!-- Horizontal Form Content -->
                    <form action="/" method="post" id="form-fuente" name="form-fuente" class="form-horizontal form-bordered" onsubmit="return false;">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <input type="text" id="data_fuente" name="data_fuente" class="form-control" placeholder="Fuente..">
                        </div>
                    </form>
                    <!-- END Horizontal Form Content -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-success" id="btn-add-fuente">Agregar</button>
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Dialogo de nota -->

    <!-- Dialogo de nota -->
    <div id="modal-programa" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title" id="modal-programa-title">Progama</h3>
                </div>
                <div class="modal-body" id="modal-note-body">
                    <!-- Horizontal Form Content -->
                    <form action="/" method="post" id="form-programa" name="form-programa" class="form-horizontal form-bordered" onsubmit="return false;">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <select size="1" class="form-control" name="data_fuente" id="data_fuente">
                                <option value="0">Fuente..</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" id="data_programa" name="data_programa" class="form-control" placeholder="Programa..">
                        </div>
                        <div class="form-group">
                            <select size="1" class="form-control" name="data_comunicador" id="data_comunicador">
                                <option value="0">Comunicador..</option>
                            </select>
                        </div>
                    </form>
                    <!-- END Horizontal Form Content -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-success" id="btn-add-programa">Agregar</button>
                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Dialogo de nota -->

    <!-- Dialogo de nota -->
    <div id="modal-comunicador" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title" id="modal-comunicador-title">Comunicador</h3>
                </div>
                <div class="modal-body" id="modal-note-body">
                    <!-- Horizontal Form Content -->
                    <form action="/" method="post" id="form-comunicador" name="form-comunicador" class="form-horizontal form-bordered" onsubmit="return false;">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <input type="text" id="data_comunicador" name="data_comunicador" class="form-control" placeholder="Comunicador..">
                        </div>
                    </form>
                    <!-- END Horizontal Form Content -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-success" id="btn-add-comunicador">Agregar</button>
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

    {{ HTML::script('js/pages/misc.js') }}
    
@stop