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
	                
	                var _item  = '<tr>';
	                    _item += '   <td style="width: 180px;">' + _audits["_" + _dnote][i].actor.name + '</td>';
	                    _item += '    <td><strong>' + _audits["_" + _dnote][i].topic.text + '</strong></td>';
	                    _item += '    <td><strong>' + (_audits["_" + _dnote][i].type!=undefined?_audits["_" + _dnote][i].type.name:'') + '</strong></td>';
	                    _item += '   <td class="text-center" style="width: 70px;"><span class="label label-' + (_audits["_" + _dnote][i].status=='p' ? 'success' : (_audits["_" + _dnote][i].status=='n' ? 'danger' : 'default') ) + '">' + (_audits["_" + _dnote][i].status=='p' ? 'Positiva' : (_audits["_" + _dnote][i].status=='n' ? 'Negativa' : 'Neutral') ) + '</span></td>';
	                    _item += '</tr>'

	                $("table#table-badgets tbody").append(_item);
	            };

	            $("#modal-topic").modal('show');

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
            		window.location.href = '/cp/excel/export/tb/' + _actor;
            	} else if(_type=='excel-type-b-full') {
            		window.location.href = '/cp/excel/export/tb-full';
            	}

            	e.preventDefault();
            });

 		}
 	}

 }();