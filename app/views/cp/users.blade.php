@extends ('layout.admin')

<!-- Contenido -->
@section ('content')
    <div id="page-content">
        <!-- Blank Header -->
        <div class="content-header">
            <div class="header-section">
                <h1>
                    <i class="gi gi-user"></i>Usuarios<br><small>Administracion de usuarios</small>
                </h1>
            </div>
        </div>
        <ul class="breadcrumb breadcrumb-top">
            <li>Panel de control</li>
            <li>Usuarios</li>
        </ul>
        <!-- END Blank Header -->

        <!-- Datatables Content -->
        <div class="block full">
            <div class="block-title clearfix">
                <div class="block-options pull-left">
                    <div class="btn-group btn-group-sm">
                        <a href="javascript:void(0)" class="btn btn-alt btn-default dropdown-toggle enable-tooltip" data-toggle="dropdown" title="Opciones"><span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-custom">
                            <li>
                                <a href="javascript:void(0)" data-target="#modal-user-add" data-toggle="modal"><i class="gi gi-user_add pull-right"></i>Nuevo usuario</a>
                                <a href="javascript:void(0)" data-target="#modal-roles" data-toggle="modal"><i class="gi gi-crown pull-right"></i>Roles</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <h2 class="pull-right"><strong>Usuarios</strong> listado</h2>
            </div>
            
            <div class="table-responsive">
                <table id="users-datatable" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Tipo</th>
                            <th class="text-center">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END Datatables Content -->
    </div>
@stop
<!-- /Contenido -->

<!-- Dialogos -->
@section ('dialogs')
<!-- Nuevo usuario de sistema -->
<div id="modal-user-add" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-users"></i> Nuevo usuario</h2>
            </div>
            <!-- END Modal Header -->

            <!-- Modal Body -->
            <div class="modal-body">
                <form action="users" id="form-add-usesr" name="form-add-usesr" method="post" class="form-horizontal form-bordered">
                    <input type="hidden" id="user-role" name="user-role">
                    <fieldset>
                        <legend>Informacion de usuario</legend>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Usuario</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" id="user-user" name="user-user" class="form-control" placeholder="Usuario">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="lavel-role">Rol</span> <span class="caret"></span></button>
                                        <ul class="dropdown-menu dropdown-menu-right user-add-role-selection">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="user-firstname">Nombre</label>
                            <div class="col-md-8">
                                <input type="text" id="user-firstname" name="user-firstname" class="form-control" placeholder="Nombre">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="user-lastname">Apellido</label>
                            <div class="col-md-8">
                                <input type="text" id="user-lastname" name="user-lastname" class="form-control" placeholder="Apellido">
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Contraseña</legend>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="user_password">Contraseña</label>
                            <div class="col-md-8">
                                <input type="password" id="user_password" name="user_password" class="form-control" placeholder="Contraseña de acceso al sistema">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="user_repassword">Confirmacion de contraseña</label>
                            <div class="col-md-8">
                                <input type="password" id="user_repassword" name="user_repassword" class="form-control" placeholder="Confirmacion de contraseña">
                            </div>
                        </div>
                    </fieldset>
                    <div class="form-group form-actions">
                        <div class="col-xs-12 text-right">
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-sm btn-primary" id="sys-add-user">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- END Modal Body -->
        </div>
    </div>
</div>
<!-- END Nuevo usuario de sistema -->

<!-- Nuevo usuario de sistema -->
<div id="modal-user-edit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-users"></i> Edicion de usuario</h2>
            </div>
            <!-- END Modal Header -->

            <!-- Modal Body -->
            <div class="modal-body">
                <form action="users" id="form-edit-usesr" name="form-edit-usesr" method="post" class="form-horizontal form-bordered">
                    <input type="hidden" id="user-id" name="user-id">
                    <input type="hidden" id="user-role" name="user-role">
                    <fieldset>
                        <legend>Informacion requerida</legend>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Usuario</label>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" id="user-user" name="user-user" class="form-control" readonly>
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="lavel-role">Rol</span> <span class="caret"></span></button>
                                        <ul class="dropdown-menu dropdown-menu-right user-edit-role-selection">
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="user-firstname">Nombre</label>
                            <div class="col-md-8">
                                <input type="text" id="user-firstname" name="user-firstname" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="user-lastname">Apellido</label>
                            <div class="col-md-8">
                                <input type="text" id="user-lastname" name="user-lastname" class="form-control">
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Contraseña</legend>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="user_password">Contraseña</label>
                            <div class="col-md-8">
                                <input type="password" id="user_password" name="user_password" class="form-control" placeholder="Contraseña de acceso al sistema">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="user_repassword">Confirmacion de contraseña</label>
                            <div class="col-md-8">
                                <input type="password" id="user_repassword" name="user_repassword" class="form-control" placeholder="Confirmacion de contraseña">
                            </div>
                        </div>
                    </fieldset>
                    <div class="form-group form-actions">
                        <div class="col-xs-12 text-right">
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-sm btn-primary" id="sys-edit-user">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- END Modal Body -->
        </div>
    </div>
</div>
<!-- END Nuevo usuario de sistema -->

<!-- Nuevo usuario de sistema -->
<div id="modal-roles" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="gi gi-crown"></i> Roles</h2>
            </div>
            <!-- END Modal Header -->

            <!-- Modal Body -->
            <div class="modal-body">
                <form action="roles" id="form-roles" name="form-roles" method="post" class="form-horizontal form-bordered">
                    <input type="hidden" id="form-role-cmd" name="form-role-cmd" value="new">
                    <input type="hidden" id="form-role-id" name="form-role-id">
                    <fieldset>
                        <div class="form-group">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" placeholder="Nombre de rol" class="form-control" name="role-name" id="role-name">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="button" id="btn-add-role"><i class="gi gi-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="table-responsive">
                            <table id="roles-datatable" class="table table-vcenter table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Rol</th>
                                        <th style="width: 150px;" class="text-center">Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </fieldset>
                    <div class="form-group form-actions">
                        <div class="col-xs-12 text-right">
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- END Modal Body -->
        </div>
    </div>
</div>
<!-- END Nuevo usuario de sistema -->
@stop
<!-- /Dialogos -->

<!-- Scripts adicionales -->
@section ('scripts')
    @parent
    <!-- DATA TABES SCRIPT -->
    {{ HTML::script('js/pages/tablesDatatables.js') }}
    {{ HTML::script('js/pages/users.js') }}
    <script>
        var uid = '{{ Crypt::encrypt(Auth::user()->id) }}';
        $(function(){
            UsersDatatables.init();
            Users.init();
        });
    </script>
@stop
