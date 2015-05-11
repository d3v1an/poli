var ReportData   = function(id) {

 	return {

 		init: function() {

 			$(".btn-note").click(function(){
	            $("#modal-note-body").html($(this).data('content'));
	            $("#modal-note").modal('show');
	        });

	        $(".btn-cal").click(function(){

	            var _dnote = $(this).data('note');

	            $("table#table-badgets tbody").empty();

	            for (var i = 0; i < _audits["_" + _dnote].length; i++) {
	                
	                var _item  = '<tr id="_dnote_' + i + '">';
	                    _item += '   <td style="width: 180px;" class="actor-tag">' + _audits["_" + _dnote][i].actor.name + '</td>';
	                    _item += '    <td class="tema-tag"><strong>' + _audits["_" + _dnote][i].topic.text + '</strong></td>';
	                    _item += '    <td class="type-tag"><strong>' + (_audits["_" + _dnote][i].type!=undefined?_audits["_" + _dnote][i].type.name:'') + '</strong></td>';
	                    _item += '    <td class="text-center status-tag" style="width: 70px;"><span class="label label-' + (_audits["_" + _dnote][i].status=='p' ? 'success' : (_audits["_" + _dnote][i].status=='n' ? 'danger' : 'default') ) + '">' + (_audits["_" + _dnote][i].status=='p' ? 'Positiva' : (_audits["_" + _dnote][i].status=='n' ? 'Negativa' : 'Neutral') ) + '</span></td>';
	                    _item += '	  <td>';
	                    _item += '		<div class="btn-group btn-group-xs">';
	                    _item += '			<button class="btn btn-xs btn-default btn-edit" data-dnote="' + _dnote + '" data-dni="' + i + '" data-id="' + _audits["_" + _dnote][i].pivot.audit_id + '" data-pid="' + _audits["_" + _dnote][i].pivot.piece_id + '" data-aid="' + _audits["_" + _dnote][i].actor_id + '" data-tid="' + _audits["_" + _dnote][i].topic_id + '" data-tyid="' + _audits["_" + _dnote][i].type_id + '" data-sid="' + _audits["_" + _dnote][i].status + '"><i class="gi gi-pencil"></i></button>';
	                    _item += '		</div>';
	                    _item += '	  </td>';
	                    _item += '</tr>';

	                $("table#table-badgets tbody").append(_item);
	            };

	            $('.btn-edit').click(function(){

	            	var _dnote 		= $(this).data('dnote');
	            	var _dnote_indx	= $(this).data('dni');
	            	var _id 		= $(this).data('id');
	            	var _piece_id	= $(this).data('pid');
	            	var _actor_id	= $(this).data('aid');
	            	var _topic_id	= $(this).data('tid');
	            	var _type_id	= $(this).data('tyid');
	            	var _status		= $(this).data('sid');

	            	var _form 		= $('#form-cal-range');

	            	$('#dnote',_form).val(_dnote);
	            	$('#dni',_form).val(_dnote_indx);

	            	$('#audit_id',_form).val(_id);
	            	$('#piece_id',_form).val(_piece_id);
	            	$('#character',_form).val(_actor_id);
	            	$('#tema',_form).val(_topic_id);
	            	$('#tipo',_form).val(_type_id);
	            	$('#status',_form).val(_status);

	            	$('#modal-audit-opts').css('display','block');
	            });

	            $('.btn-delete').click(function(){

	            	var _dnote 		= $(this).data('dnote');
	            	var _dni 		= $(this).data('dni');
	            	var _piece_id	= $(this).data('pid');
	            	var _audit_id	= $(this).data('aid');

	            	$.d3POST('/cp/report/printed/del',{aid:_audit_id,pid:_piece_id},function(data){
	            		if(data.status==true) {
	                        $.bootstrapGrowl(data.message, {
	                            type: "success",
	                            delay: 4500,
	                            allow_dismiss: true
	                        });
	                        $('table tbody tr[id="_dnote_' + _dni + '"]').remove();
	                    } else {
	                    	$.bootstrapGrowl(data.message, {
	                            type: "danger",
	                            delay: 4500,
	                            allow_dismiss: true
	                        });
	                    }
	            	});

	            });

	            $("#modal-topic").modal('show');

	        });

			$('.btn-del-audit').click(function(e){

				var _aid = $(this).data('aid');
				var _tid = $(this).data('tid');

				var _confirm = confirm('Realmente desea eliminar esta auditoria?');

				if(_confirm==true) {

					$.d3POST('/cp/report/printed/audit/del',{aid:_aid},function(data){
						if(data.status==true) {
	                        $.bootstrapGrowl(data.message, {
	                            type: "success",
	                            delay: 4500,
	                            allow_dismiss: true
	                        });
	                        $('table.table-report tbody tr[id="' + _tid + '"]').remove();
	                    } else {
	                    	$.bootstrapGrowl(data.message, {
	                            type: "danger",
	                            delay: 4500,
	                            allow_dismiss: true
	                        });
	                    }
					});
				}

				e.preventDefault();
			});

			// Actualizar auditoria
			$('.btn-save-audit').click(function(e){

				var _form 	= $('#form-cal-range');

				var _dnote 		= $('#dnote',_form).val();
	            var _dni 		= $('#dni',_form).val();
				var _audit_id 	= $('#audit_id',_form).val();
            	var _piece_id 	= $('#piece_id',_form).val();
            	var _actor_id 	= $('#character',_form).val();
            	var _actor_txt 	= $('#character option:selected',_form).text();
            	var _topic_id 	= $('#tema',_form).val();
            	var _topic_txt 	= $('#tema option:selected',_form).text();
            	var _type_id 	= $('#tipo',_form).val();
            	var _type_txt 	= $('#tipo option:selected',_form).text();
            	var _status 	= $('#status',_form).val();

				$.d3POST('/cp/report/printed/audit',_form.serialize(),function(data){

					if(data.status==true) {
                        $.bootstrapGrowl(data.message, {
                            type: "success",
                            delay: 4500,
                            allow_dismiss: true
                        });

                        _audits["_" + _dnote][_dni].actor_id = _actor_id;
                        _audits["_" + _dnote][_dni].topic_id = _topic_id;
                        _audits["_" + _dnote][_dni].topic.text = _topic_txt;
                        _audits["_" + _dnote][_dni].type_id  = _type_id;
                        _audits["_" + _dnote][_dni].type.name  = _type_txt;
                        _audits["_" + _dnote][_dni].status   = _status;

                        var _tdc  = '<span class="label label-' + (_status=='p' ? 'success' : (_status=='n' ? 'danger' : 'default') ) + '">' + (_status=='p' ? 'Positiva' : (_status=='n' ? 'Negativa' : 'Neutral') ) + '</span>';
                        var _tema = '<strong>' + _topic_txt + '</strong>';
                        var _type = '<strong>' + _type_txt + '</strong>';

                        $('table tbody tr[id="_dnote_' + _dni + '"] td.status-tag').html(_tdc);
                        $('table tbody tr[id="_dnote_' + _dni + '"] td.actor-tag').html(_actor_txt);
                        $('table tbody tr[id="_dnote_' + _dni + '"] td.tema-tag').html(_tema);
                        $('table tbody tr[id="_dnote_' + _dni + '"] td.type-tag').html(_type);

                        $('#modal-audit-opts').css('display','none');

                    } else {
                        $.bootstrapGrowl(data.message, {
                            type: "danger",
                            delay: 4500,
                            allow_dismiss: true
                        });
                    }
				});

				e.preventDefault();
			});

	        App.datatables();

	        /* Initialize Datatables */
	        $('.table-report').dataTable({
	            // "aoColumnDefs": [ { "bSortable": false, "aTargets": [ 1, 5 ] } ],
	            "aoColumnDefs": [
	            					{
	            						"aTargets": [0],
						                "bSearchable": false,
						                "bSortable": false
						            },
						            {
	            						"aTargets": [3],
						                "bSearchable": false,
						                "bSortable": false
						            },
						            {
	            						"aTargets": [5],
						                "bSearchable": false,
						                "bSortable": false
						            },
						            {
	            						"aTargets": [7],
						                "bSearchable": false,
						                "bSortable": false
						            }
	            				],
	            "iDisplayLength": 10,
	            "aLengthMenu": [[10, 20, 30, -1], [10, 20, 30, "Todo"]]
	        });

	        /* Add placeholder attribute to the search input */
	        $('.dataTables_filter input').attr('placeholder', 'Buscar');

	        // Boton de rango de fechas
	        $('#btn-data-range').click(function(e){
	        	$('#modal-data-range').modal('show');
	        	e.preventDefault();
	        });

	        $("#data-range-init").datepicker({
	        	autoclose: true,
	        	language: 'es',
	        	format: 'yyyy-mm-dd',
	        	startDate: '-2m',
    			endDate: '-1d'
	        });

	        $("#data-range-end").datepicker({
	        	autoclose: true,
	        	language: 'es',
	        	format: 'yyyy-mm-dd',
	        	startDate: '-2m',
    			endDate: '-1'
	        }); 

	        // Boton de acicon de rango
	        $('.goto-range').click(function(e){
	        	
	        	var _form 		= $('#form-data-range');
	        	var _aid 		= $('#aid', _form).val();
	        	var _dataInit 	= $('#data-range-init', _form).val();
	        	var _dataEnd 	= $('#data-range-end', _form).val();

	        	if(_aid=='') {
	        		alert('ID de personaje invalido');
	        		return false;
	        	}
	        	if(_dataInit=='') {
	        		alert('Fecha inicial invalida');
	        		return false;
	        	}
	        	if(_dataEnd=='') {
	        		alert('Fecha final invalida');
	        		return false;
	        	}

	        	$('#aid', _form).val('');
	        	$('#data-range-init', _form).val('');
	        	$('#data-range-end', _form).val('');

	        	window.location.href = '/cp/report/printed/' + _aid + ':' + _dataInit + ':' + _dataEnd;

	        	e.preventDefault();
	        });

	        // Ids seleccionados
	        var _ids = [];

	        /* Select/Deselect all checkboxes in tables */
            $('thead input:checkbox').click(function() {

            	_ids = [];
            	
                var checkedStatus   = $(this).prop('checked');
                var table           = $(this).closest('table');

                $('tbody input:checkbox', table).each(function() {
                    $(this).prop('checked', checkedStatus);
                    if(checkedStatus==true) _ids.push($(this).data('id'));
                });

            });
            $('tbody input:checkbox').click(function(){
            	var isChecked = $(this).prop('checked');
            	if(isChecked==true) _ids.push($(this).data('id'));
            });

            $(".btn-export").click(function(e){

            	var _type 	= $(this).data('type');
            	var _actor 	= $(this).data('actor');
            	var _xids 	= _ids.join(',');

            	if(_type=='excel-type-a') {
            		
            		if(_ids.length < 1) window.location.href = '/cp/excel/export/ta/' + _actor;
            		else window.location.href = '/cp/excel/export/ta/' + _actor + ':' + _xids;

            	} else if(_type=='excel-type-b') {

            		var _ranged 	= $(this).data('ranged');
            		var _dataInit 	= $(this).data('init');
            		var _dataEnd 	= $(this).data('end');

            		if(_ranged) window.location.href = '/cp/excel/export/tb_range/' + _actor + ':' + _dataInit + ':' + _dataEnd;
            		else window.location.href = '/cp/excel/export/tb/' + _actor;

            	} else if(_type=='excel-type-b-full') {

            		var _ranged 	= $(this).data('ranged');
            		var _dataInit 	= $(this).data('init');
            		var _dataEnd 	= $(this).data('end');

            		if(_ranged) window.location.href = '/cp/excel/export/tb-full/' + _dataInit + ':' + _dataEnd;
            		else window.location.href = '/cp/excel/export/tb-full';

            	}

            	e.preventDefault();
            });

 		}
 	}

 }();