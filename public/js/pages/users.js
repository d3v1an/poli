/*
 *  Document   : login.js
 *  Author     : pixelcave
 *  Description: Custom javascript code used in Login page
 */

var _udto = null;
var _rdto = null;

var UsersDatatables = function () {

    return {
        init: function () {
            /* Initialize Bootstrap Datatables Integration */
            App.datatables();

            /* Tabla de usuario */
            _udto = $('#users-datatable').dataTable({
                "iDisplayLength": 10,
                "aLengthMenu": [[10, 20, 30], [10, 20, 30]],
                "bProcessing": true,
                "bServerSide": true,
                "sAjaxSource" : base_path+"/cp/users/load",
                "fnServerParams": function ( aoData ) {
		            aoData.push( { "name": "uid", "value": uid } );
		        },
                "columns" : [
		            { "data": "id" },
		            { "data": "username" },
		            { "data": "full_name" },
		            { "data": "role" },
		            { "data": null }
		        ],
		        "aoColumnDefs": [
		            { // id
		                "aTargets": [0],
		                "bSearchable": false,
		                "bSortable": true,
		                "sClass": "text-center"
		            },
		            { // Usuario
		                "aTargets": [1],
		                "bSearchable": true,
		                "bSortable": true,
		                "mRender": function (data, type, full) {
		                	return '<strong>'+full.username+'</strong>';
		                }
		            },
		            { // Nombre
		                "aTargets": [2],
		                "bSearchable": true,
		                "bSortable": false,
		            },
		            { // role
		                "aTargets": [3],
		                "bSearchable": false,
		                "bSortable": true,
		                "mRender": function (data, type, full) {

		                	var _badgetColor = 'label-info'; 

		                	if(full.role==1) _badgetColor = 'label-primary';

		                	return '<span class="label '+_badgetColor+'">'+full.role_name+'</span>';
		                }
		            },
		            { // actions
		                "aTargets": [4],
		                "bSearchable": false,
		                "bSortable": false,
		                "sClass": "text-center",
		                "mRender": function (data, type, full) {

		                	var _btn_groups  = '<div class="btn-group">';
		                		_btn_groups += '	<a href="javascript:void(0)" data-toggle="tooltip" title="Editar" data-action="edit" class="btn btn-xs btn-default" data-id="'+full.id+'"><i class="fa fa-pencil"></i></a>';
		                		_btn_groups += '	<a href="javascript:void(0)" data-toggle="tooltip" title="Eliminar" data-action="del" class="btn btn-xs btn-danger" data-id="'+full.id+'"><i class="fa fa-times"></i></a>';
		                		_btn_groups += '</div>';

		                	return _btn_groups;
		                },
		                "fnCreatedCell": function ( cell ) {

		                    $('a', cell).on('click', function (e) {

		                        var _action = $(this).data('action');
		                        var _id 	= $(this).data('id');

		                        if(_action=='edit') {

		                        	$.d3POST(base_path+'/cp/users/info',{id:_id},function(data){

									    if(data.status==true) {

									    	$("#form-edit-usesr").trigger('reset');
									    	
									    	$("#user-id","#form-edit-usesr").val(data.user.id);
									    	$(".lavel-role").html('').html(data.user.role.name);
									    	$("#user-role","#form-edit-usesr").val(data.user.role_id);
									    	$("#user-user","#form-edit-usesr").val(data.user.username);
									    	$("#user-firstname","#form-edit-usesr").val(data.user.first_name);
									    	$("#user-lastname","#form-edit-usesr").val(data.user.last_name);

									    }

									});

		                        	$("#modal-user-edit").modal('show');
		                        }
		                        else if(_action=='del') {
		                        	$(".confirm-content").html('').html('Realmente desea eliminar este suario del sistema?');
		                        	$("#fmc-param","#form-modal-confirm").val('user');
		                        	$("#fmc-value","#form-modal-confirm").val('del:'+_id);
		                        	$("#modal-confirm").modal('show');
		                        }

		                    });

		                }
		            }
        		],
                "order": [ 0, 'desc' ]
            });

            /* Add placeholder attribute to the search input */
            $('#users-datatable_filter input').attr('placeholder', 'Buscar');

            $('<label><button class="btn btn-primary btn-sm" id="users_data_refresh"><span class="fa fa-refresh"></span></button></label>').css('margin-left','5px').css('margin-top','2px').insertBefore('div#users-datatable_filter > label');
		    $('button#users_data_refresh').click(function(){
		        _udto.dataTable()._fnAjaxUpdate();
		    });

		    /* Tabla de roles */
            _rdto = $('#roles-datatable').dataTable({
                "iDisplayLength": 5,
                "aLengthMenu": [[5, 10], [5, 10]],
                "bProcessing": false,
                "bServerSide": true,
                "sAjaxSource" : base_path+"/cp/roles/load",
                "columns" : [
		            { "data": "id" },
		            { "data": "name" },
		            { "data": null }
		        ],
		        "aoColumnDefs": [
		            { // id
		                "aTargets": [0],
		                "bSearchable": false,
		                "bSortable": true,
		                "sClass": "text-center"
		            },
		            { // Nombre
		                "aTargets": [1],
		                "bSearchable": true,
		                "bSortable": true,
		            },
		            { // actions
		                "aTargets": [2],
		                "bSearchable": false,
		                "bSortable": false,
		                "sClass": "text-center",
		                "mRender": function (data, type, full) {

		                	var _btn_groups  = '<div class="btn-group">';
		                		_btn_groups += '	<a href="javascript:void(0)" data-toggle="tooltip" title="Editar" data-action="edit" class="btn btn-xs btn-default" data-id="'+full.id+'"><i class="fa fa-pencil"></i></a>';
		                		_btn_groups += '	<a href="javascript:void(0)" data-toggle="tooltip" title="Eliminar" data-action="del" class="btn btn-xs btn-danger" data-id="'+full.id+'"><i class="fa fa-times"></i></a>';
		                		_btn_groups += '</div>';

		                	return _btn_groups;
		                },
		                "fnCreatedCell": function ( cell ) {

		                    $('a', cell).on('click', function (e) {

		                        var _action = $(this).data('action');
		                        var _id 	= $(this).data('id');

		                        if(_action=='edit') {

		                        	$.d3POST(base_path+'/cp/roles/info',{id:_id},function(data){

									    if(data.status==true) {

									    	$("#form-roles").trigger('reset');
									    	
									    	$("#form-role-id","#form-roles").val(data.role.id);
									    	$("#form-role-cmd","#form-roles").val('edit');
									    	$("#role-name","#form-roles").val(data.role.name);

									    }

									});

		                        }
		                        else if(_action=='del') {
		                        	$(".confirm-content").html('').html('Realmente desea eliminar este rol del sistema?');
		                        	$("#fmc-param","#form-modal-confirm").val('roles');
		                        	$("#fmc-value","#form-modal-confirm").val('del:'+_id);
		                        	$("#modal-confirm").modal('show');
		                        }

		                    });

		                }
		            }
        		],
                "order": [ 0, 'asc' ]
            });

			 /* Add placeholder attribute to the search input */
            $('#roles-datatable_filter input').attr('name','role-table-search').attr('placeholder', 'Buscar');

        }
    };
}();

var Users = function() {

 	return {

 		init: function() {

 			// Boton de confirmacion de dialogos
 			$('#button-modal-confirm').click(function(e){
            	
            	var _param = $("#fmc-param","#form-modal-confirm").val();
            	var _value = $("#fmc-value","#form-modal-confirm").val();

            	var _g = $.bootstrapGrowl;

            	if(_param=='user') {

            		var _sval = _value.split(':');

            		if(_sval[0]=='del') {

            			$.d3POST(base_path+'/cp/users/del',{id:_sval[1]},function(data){

						    if(data.status==true) {
						        
						        _g(data.message, {
						            type: "success",
						            delay: 4500,
						            allow_dismiss: true
						        });

						        _udto.dataTable()._fnAjaxUpdate();

						    } else {
						        
						        _g(data.message, {
						            type: "danger",
						            delay: 4500,
						            allow_dismiss: true
						        });

						    }

						});

            		}

            	} else if(_param=='roles') {

            		var _sval = _value.split(':');

            		if(_sval[0]=='del') {

            			$.d3POST(base_path+'/cp/roles/del',{id:_sval[1]},function(data){

						    if(data.status==true) {
						        
						        _g(data.message, {
						            type: "success",
						            delay: 4500,
						            allow_dismiss: true
						        });

						        _rdto.dataTable()._fnAjaxUpdate();

						    } else {
						        
						        _g(data.message, {
						            type: "danger",
						            delay: 4500,
						            allow_dismiss: true
						        });

						    }

						});

            		}

            	}
            	
            	$("#modal-confirm").modal('hide');
            	
            	e.preventDefault();
            });

	 		// Validation config
	 		jQuery.validator.setDefaults({
				debug: true,
				success: "valid"
			});

	 		// Add user
	 		var _formAddData 	= $('#form-add-usesr');
	 		var _buttonRole 	= $('.btn-role', _formAddData);
	 		var _lavelRole 		= $('.lavel-role', _formAddData);
	 		var _inputRole 		= $('#user-role', _formAddData);

	 		$('#modal-user-add').on('show.bs.modal', function (e) {
	 			
	 			_formAddData.trigger('reset');

	 			$.d3GET(base_path+'/cp/roles/get',{},function(data){

				    if(data.status==true && data.roles.length>0) {

				    	$(".user-add-role-selection").html('');

				    	$.each(data.roles,function(i, item){

				    		var _roleItem = '<li><a href="javascript:void(0)" class="btn-role"  data-role="'+item.id+'">'+item.name+'</a></li>';
				    		
				    		$(".user-add-role-selection").append(_roleItem);
				    		
				    		if(item.default==1) {
				    			_lavelRole.html(item.name);
				    			_inputRole.val(item.id);
				    		}

				    	});

				    	$('.btn-role').on('click',function(e){

				 			var _role 	= $(this).data('role');
				 			var _lavel 	= $(this).html();

				 			_lavelRole.html(_lavel);
				 			_inputRole.val(_role);

				 		});

				    } else {

				    	$('#modal-user-add').modal('hide');
				        
				        _g(data.message, {
				            type: "danger",
				            delay: 4500,
				            allow_dismiss: true
				        });
				    }

				});

			});

			$('#modal-user-add').on('hidden.bs.modal', function (e) {
				$("#form-add-usesr :input").prop("disabled", false);
			});

			_formAddData.validate({
				errorClass: 'help-block animation-slideDown', // You can change the animation class for a different entrance animation - check animations page
				errorElement: 'div',
				errorPlacement: function(error, e) {
					e.parents('.form-group > div').append(error);
				},
				highlight: function(e) {
					$(e).closest('.form-group').removeClass('has-success has-error').addClass('has-error');
					$(e).closest('.help-block').remove();
				},
				success: function(e) {
					e.closest('.form-group').removeClass('has-success has-error');
					e.closest('.help-block').remove();
				},
				rules: {
					'user-user': {
				    	required: true,
				    	minlength: 5,
				    	lettersonly: true
					},
					'user-firstname': {
				    	required: true,
				    	minlength: 5
					},
					'user-lastname': {
				    	required: true,
				    	minlength: 5
					},
					'user_password': {
				    	required: true,
				    	minlength: 5
					},
					user_repassword: {
				    	equalTo: "#user_password"
				    }
				},
				messages: {
					'user-user': {
						required: 'Por favor ingrese un nombre usuario',
						minlength: 'Debe contener almenos 5 caracteres',
						lettersonly: 'Solo de admiten letras'
					},
					'user-firstname': {
						required: 'Por favor ingrese un nombre',
						minlength: 'Debe contener almenos 5 caracteres'
					},
					'user-lastname': {
						required: 'Por favor ingrese un apellido',
						minlength: 'Debe contener almenos 5 caracteres'
					},
					'user_password': {
						required: 'Por favor ingrese una contraseña',
						minlength: 'Debe contener almenos 5 caracteres'
					},
					user_repassword: {
				    	equalTo: 'La contraseña y la confirmacion no coinciden'
				    }
				},
				submitHandler: function(form) {

					$("#form-add-usesr :input").prop("disabled", true);

					var _role 		= $('#user-role',_formAddData).val();
					var _username 	= $('#user-user',_formAddData).val();
					var _fname 		= $('#user-firstname',_formAddData).val();
					var _lname 		= $('#user-lastname',_formAddData).val();
					var _password 	= $('#user_password',_formAddData).val();

					var _g 			= $.bootstrapGrowl;

					$.d3POST(base_path+'/cp/users',{role:_role,username:_username,first_name:_fname,last_name:_lname,password:_password},function(data){

					    if(data.status==true) {
					        
					        _g(data.message, {
					            type: "success",
					            delay: 4500,
					            allow_dismiss: true
					        });

					        $('#modal-user-add').modal('hide');

					        _udto.dataTable()._fnAjaxUpdate();

					    } else {
					        
					        _g(data.message, {
					            type: "danger",
					            delay: 4500,
					            allow_dismiss: true
					        });

					        $("#form-add-usesr :input").prop("disabled", false);
					    }

					});

				}
			});

			// Edit user
			var _formEditData 	= $('#form-edit-usesr');
	 		var _e_buttonRole 	= $('.btn-role', _formEditData);
	 		var _e_lavelRole 	= $('.lavel-role', _formEditData);
	 		var _e_inputRole 	= $('#user-role', _formEditData);
	 		var _validateForm;

	 		$('#modal-user-edit').on('show.bs.modal', function (e) {
	 			
	 			_formEditData.trigger('reset');

	 			$.d3GET(base_path+'/cp/roles/get',{},function(data){

				    if(data.status==true && data.roles.length>0) {

				    	$(".user-edit-role-selection").html('');

				    	$.each(data.roles,function(i, item){

				    		var _roleItem = '<li><a href="javascript:void(0)" class="btn-role"  data-role="'+item.id+'">'+item.name+'</a></li>';
				    		
				    		$(".user-edit-role-selection").append(_roleItem);

				    	});

				    	$('.btn-role').on('click',function(e){

				 			var _role 	= $(this).data('role');
				 			var _lavel 	= $(this).html();

				 			_e_lavelRole.html(_lavel);
				 			_e_inputRole.val(_role);

				 		});

				    } else {

				    	$('#modal-user-edit').modal('hide');
				        
				        _g(data.message, {
				            type: "danger",
				            delay: 4500,
				            allow_dismiss: true
				        });
				    }

				});

			});

			$('#modal-user-edit').on('hidden.bs.modal', function (e) {
				$("#form-edit-usesr :input").prop("disabled", false);
			});

			_e_buttonRole.click(function(e){

	 			var _role 	= $(this).data('role');
	 			var _lavel 	= $(this).html();

	 			_e_lavelRole.html(_lavel);
	 			_e_inputRole.val(_role);

	 		});

            _validateForm = _formEditData.validate({
				errorClass: 'help-block animation-slideDown', // You can change the animation class for a different entrance animation - check animations page
				errorElement: 'div',
				errorPlacement: function(error, e) {
					e.parents('.form-group > div').append(error);
				},
				highlight: function(e) {
					$(e).closest('.form-group').removeClass('has-success has-error').addClass('has-error');
					$(e).closest('.help-block').remove();
				},
				success: function(e) {
					e.closest('.form-group').removeClass('has-success has-error');
					e.closest('.help-block').remove();
				},
				rules: {
					'user-user': {
				    	required: true,
				    	minlength: 5
					},
					'user-firstname': {
				    	required: true,
				    	minlength: 5
					},
					'user-lastname': {
				    	required: true,
				    	minlength: 5
					},
					'user_password': {
				    	required: false,
				    	minlength: 5
					},
					user_repassword: {
				    	equalTo: {
				    		param: "#user_password",
				    		depends: function(element) {
			                    return (
			                    		$("#user_password", _formEditData).val() != '' &&
			                    		($(element).val() != $("#user_password", _formEditData).val())
			                    	);
			                }
				    	}
				    }
				},
				messages: {
					'user-user': {
						required: 'Por favor ingrese un nombre usuario',
						minlength: 'Debe contener almenos 5 caracteres'
					},
					'user-firstname': {
						required: 'Por favor ingrese un nombre',
						minlength: 'Debe contener almenos 5 caracteres'
					},
					'user-lastname': {
						required: 'Por favor ingrese un apellido',
						minlength: 'Debe contener almenos 5 caracteres'
					},
					'user_password': {
						required: 'Por favor ingrese una contraseña',
						minlength: 'Debe contener almenos 5 caracteres'
					},
					user_repassword: {
				    	equalTo: 'La contraseña y la confirmacion no coinciden'
				    }
				},
				submitHandler: function(form) {

					$(":input", form).prop("disabled", true);

					var _id 		= $('#user-id', form).val();
					var _role 		= $('#user-role', form).val();
					var _username 	= $('#user-user', form).val();
					var _fname 		= $('#user-firstname', form).val();
					var _lname 		= $('#user-lastname', form).val();
					var _password 	= $('#user_password', form).val();

					var _g 			= $.bootstrapGrowl;

					$.d3POST(base_path+'/cp/users/edit',{id:_id,role:_role,username:_username,first_name:_fname,last_name:_lname,password:_password},function(data){

					    if(data.status==true) {
					        
					        _g(data.message, {
					            type: "success",
					            delay: 4500,
					            allow_dismiss: true
					        });

					        $('#modal-user-edit').modal('hide');

					        _udto.dataTable()._fnAjaxUpdate();

					    } else {
					        
					        _g(data.message, {
					            type: "danger",
					            delay: 4500,
					            allow_dismiss: true
					        });

					        $(":input", form).prop("disabled", false);
					    }

					});

					return false;

				}
			});

			// Add role
			var _formAddRole 	= $('#form-roles');

			$("#btn-add-role").click(function(e){
				_formAddRole.submit();
				e.preventDefault();
			});

			$('#modal-roles').on('show.bs.modal', function (e) {
	 			_formAddRole.trigger('reset');
	 			$("#form-role-cmd",_formAddRole).val('new');
			});

			$('#modal-roles').on('hidden.bs.modal', function (e) {
				_formAddRole.trigger('reset');
	 			$("#form-role-cmd",_formAddRole).val('new');
			});

			_formAddRole.validate({
				errorClass: 'help-block animation-slideDown', // You can change the animation class for a different entrance animation - check animations page
				errorElement: 'div',
				errorPlacement: function(error, e) {
					e.parents('.form-group > div').append(error);
				},
				highlight: function(e) {
					$(e).closest('.form-group').removeClass('has-success has-error').addClass('has-error');
					$(e).closest('.help-block').remove();
				},
				success: function(e) {
					e.closest('.form-group').removeClass('has-success has-error');
					e.closest('.help-block').remove();
				},
				rules: {
					'role-name': {
				    	required: true,
				    	minlength: 4
					}
				},
				messages: {
					'role-name': {
						required: 'Por favor ingrese un nombre de rol',
						minlength: 'Debe contener almenos 4 caracteres'
					}
				},
				submitHandler: function(form) {

					var _id 		= $('#form-role-id', form).val();
					var _name 		= $('#role-name', form).val();
					var _cmd 		= $('#form-role-cmd', form).val();

					var _g 			= $.bootstrapGrowl;

					if(_cmd=='new') {

						$.d3POST(base_path+'/cp/roles',{name:_name},function(data){

						    if(data.status==true) {
						        
						        _g(data.message, {
						            type: "success",
						            delay: 4500,
						            allow_dismiss: true
						        });

						        $('#role-name', form).val('');

						        _rdto.dataTable()._fnAjaxUpdate();

						    } else {
						        
						        _g(data.message, {
						            type: "danger",
						            delay: 4500,
						            allow_dismiss: true
						        });
						    }

						});

					} else if(_cmd=='edit') {

						$.d3POST(base_path+'/cp/roles/edit',{id:_id,name:_name},function(data){

						    if(data.status==true) {
						        
						        _g(data.message, {
						            type: "success",
						            delay: 4500,
						            allow_dismiss: true
						        });

						        $('#role-name', form).val('');
						        $('#form-role-cmd', form).val('new');
						        $('#form-role-id', form).val('');

						        _rdto.dataTable()._fnAjaxUpdate();

						    } else {
						        
						        _g(data.message, {
						            type: "danger",
						            delay: 4500,
						            allow_dismiss: true
						        });
						    }

						});

					}

					return false;

				}
			});

	 	}
	 };

 }();
