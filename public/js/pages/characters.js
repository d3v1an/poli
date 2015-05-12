/*
 *  Document   : login.js
 *  Author     : pixelcave
 *  Description: Custom javascript code used in Login page
 */

var _noteMatrix     = {};

// Array temporal
var _analityc       = { data:[] };

var _auditedIds     = [];

var CharacterData   = function(id) {

 	return {

        init: function() {

            $("#btn-add-actor").click(function(){
                $("#modal-actor").modal('show');
            });

            $("#btn-new-actor").click(function(){

                var _form = $("#form-actor");
                var _name = $("#actor-name", _form).val();

                $.d3POST(base_path+'/ajax/add_actor',{name:_name},function(data){
                    if(data.status == true) {
                        $.bootstrapGrowl(data.message, {
                            type: "success",
                            delay: 4500,
                            allow_dismiss: true
                        });
                        $("#actor-name", _form).val('');
                        CharacterData.actors();
                    } else {
                        $.bootstrapGrowl(data.message, {
                            type: "danger",
                            delay: 4500,
                            allow_dismiss: true
                        });
                    }
                    $("#modal-actor").modal('hide');
                });

            });

            $("#btn-add-tema").click(function(){
                $("#modal-tema").modal('show');
            });

            $("#btn-new-tema").click(function(){

                var _form = $("#form-tema");
                var _text = $("#actor-tema", _form).val();

                $.d3POST(base_path+'/ajax/add_tema',{text:_text},function(data){
                    if(data.status == true) {
                        $.bootstrapGrowl(data.message, {
                            type: "success",
                            delay: 4500,
                            allow_dismiss: true
                        });
                        $("#actor-tema", _form).val('');
                        CharacterData.themes();
                    } else {
                        $.bootstrapGrowl(data.message, {
                            type: "danger",
                            delay: 4500,
                            allow_dismiss: true
                        });
                    }
                    $("#modal-tema").modal('hide');
                });

            });

            $("#btn-add-type").click(function(){
                $("#modal-type").modal('show');
            });

            $("#btn-new-type").click(function(){

                var _form = $("#form-type");
                var _text = $("#actor-type", _form).val();

                $.d3POST(base_path+'/ajax/add_type',{text:_text},function(data){
                    if(data.status == true) {
                        $.bootstrapGrowl(data.message, {
                            type: "success",
                            delay: 4500,
                            allow_dismiss: true
                        });
                        $("#actor-type", _form).val('');
                        //CharacterData.themes();
                    } else {
                        $.bootstrapGrowl(data.message, {
                            type: "danger",
                            delay: 4500,
                            allow_dismiss: true
                        });
                    }
                    $("#modal-type").modal('hide');
                });

            });

            $("#btn-add-info").click(function(e){

                var _form           = $("#note-detaill");

                var _actor_id       = $("#note-actor").val();
                var _topic_id       = $("#note-topic").val();
                var _type_id        = $("#note-type").val();
                var _status         = $("#note-calification").val();

                var _actor_text     = $("#note-actor option:selected").text();
                var _topic_text     = $("#note-topic option:selected").text();
                var _type_text      = $("#note-type option:selected").text();

                if(_actor_id==undefined || _actor_id=='' || _actor_id==0) {
                    $.bootstrapGrowl("Seleccione un actor", {
                        type: "danger",
                        delay: 4500,
                        allow_dismiss: true
                    });
                    return false;
                }

                if(_topic_id==undefined || _topic_id=='' || _topic_id==0) {
                    $.bootstrapGrowl("Seleccione un tema", {
                        type: "danger",
                        delay: 4500,
                        allow_dismiss: true
                    });
                    return false;
                }

                if(_type_id==undefined || _type_id=='' || _type_id==0) {
                    $.bootstrapGrowl("Seleccione un tipo", {
                        type: "danger",
                        delay: 4500,
                        allow_dismiss: true
                    });
                    return false;
                }

                var _filled = CharacterData.fillTempAudit({ actor_id: _actor_id, topic_id: _topic_id, type_id: _type_id, status: _status })

                if(_filled==true) {

                    var _item  = '<tr>';
                        _item += '    <td style="width: 180px;">' + _actor_text + '</td>';
                        _item += '    <td><strong>' + _topic_text + '</strong></td>';
                        _item += '    <td><strong>' + _type_text + '</strong></td>';
                        _item += '    <td class="text-center" style="width: 70px;"><span class="label label-' + (_status=='p' ? 'success' : (_status=='n' ? 'danger' : 'default') ) + '">' + (_status=='p' ? 'Positiva' : (_status=='n' ? 'Negativa' : 'Neutral') ) + '</span></td>';
                        _item += '</tr>';

                    $("table#table-badgets tbody").append(_item);
                
                }

                e.preventDefault();
            });

            $("#btn-add-audit").click(function(e){
                
                var _meta = [];

                $.each(_analityc.data,function(i, m) {
                    var _md = m.actor_id+':'+m.topic_id+':'+m.type_id+':'+m.status;
                    _meta.push(_md);
                });

                var _form           = $("#note-detaill");

                var _ranged         = ($("#date",_form).val()=='none' ? false : true);
                var _date           = $("#date",_form).val();
                var _noteId         = $("#note_id",_form).val();
                var _metaStr        = _meta.join("|");
                var _character_id   = _current_character;

                $.d3POST(base_path+'/ajax/add_audit',{note_id:_noteId,meta:_metaStr,chracter:_character_id, ranged:_ranged,date:_date},function(data){
                    //console.log(data);
                    if(data.status==true) {
                        $.bootstrapGrowl(data.message, {
                            type: "success",
                            delay: 4500,
                            allow_dismiss: true
                        });
                        _noteMatrix['_'+_noteId].Pieces = data.pieces;
                        //console.log(_noteMatrix);
                        $("#modal-analytic").modal('hide');
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

            $('#modal-analytic').on('hidden.bs.modal', function (e) {
                
                var _form = $("#note-detaill");

                $("#actor_1_id", _form).val('');
                $("#actor_2_id", _form).val('');
                $("#actor_3_id", _form).val('');

                $("#actor_topic_1_id", _form).val('');
                $("#actor_topic_2_id", _form).val('');
                $("#actor_topic_3_id", _form).val('');

                $("#actor_status_1", _form).val('');
                $("#actor_status_2", _form).val('');
                $("#actor_status_3", _form).val('');
                
                $("#body-badgets").html('');

                $("#note-actor").val('').trigger("chosen:updated");
                $("#note-topic").val('').trigger("chosen:updated");
                $("#note-type").val('').trigger("chosen:updated");
                
                _form.trigger('reset');
            })

        },

 		load: function(id,range) {

            $('#calendar').on('change',function(){
                var _to_url = base_path + '/cp/character/' + id + ':' + $(this).val();
                window.location.href = _to_url;
                return false;
            });

            // Section 1
            var _ids_url = (range!=false ? base_path+'/ajax/cur_ids/'+id + ':' + range : base_path+'/ajax/cur_ids/'+id);

            $.d3GET(_ids_url,{},function(data){
                if(data.length > 0) {
                    $.each(data, function(i, item){
                        _auditedIds.push(item.note_id);
                    });
                }
            }, false);

            // Prueba de ids [Section 1]
            /*
                Si se trae datos segun el rango de fechas
            */
            // console.log(_auditedIds);
            // return false;

            // Section 2
            var _data_url = (range!=false ? base_path+'/ajax/data/'+id + ':' + range : base_path+'/ajax/data/'+id);
            
 			$.d3GET(_data_url,{},function(data){

                // Prueba de ids [Section 2]
                /*
                    Si se trae datos segun el rango de fechas
                */
                // console.log(data);
                // console.log('TA01');
                // return false;
 				
                if(data.status == true) {

                	// Informacion de personaje
                	$("span.char-name").html(data.data.character);
                	$("span.char-note-counter").html( (data.data.main.data.length + data.data.estados.data.length + data.data.revistas.data.length + data.data.portales.data.length) );

                	// Informacion para tab main
                	$("span.tab-main-count").html('('+data.data.main.data.length+')');
                	if(data.data.main.data.length < 1) $("#tab-res-main").html('No hay informacion disponible para esta seccion');
                	CharacterData.fill(data.data.main,'main');

                	// Informacion para tab de estados
                	$("span.tab-estados-count").html('('+data.data.estados.data.length+')');
                	if(data.data.estados.data.length < 1) $("#tab-res-estados").html('No hay informacion disponible para esta seccion');
                	CharacterData.fill(data.data.estados,'estados');

                	// Informacion para tab de revistas
                	$("span.tab-revistas-count").html('('+data.data.revistas.data.length+')');
                	if(data.data.revistas.data.length < 1) $("#tab-res-revistas").html('No hay informacion disponible para esta seccion');
                	CharacterData.fill(data.data.revistas,'revistas');

                	// Informacion para tab de portales
                	$("span.tab-portales-count").html('('+data.data.portales.data.length+')');
                	if(data.data.portales.data.length < 1) $("#tab-res-portales").html('No hay informacion disponible para esta seccion');
                	CharacterData.fill(data.data.portales,'portales');

                    $("button.btn-tool").on('click',function(){
                        
                        var _type = $(this).data('type');

                        if(_type=='rpdf' || _type=='pdf' || _type=='img' || _type=='analytic') {

                            var _file_path = $(this).data('url');

                            if(_type=='rpdf') {

                                var _cobject = $('<iframe></iframe>')
                                            .attr('id','iframe_modal_content_tmp')
                                            .attr('frameborder', '0')
                                            .attr('allowtransparency','false')
                                            .attr('width','100%')
                                            .attr('height', '600')
                                            .attr('src','http://www.gaimpresos.com/boards/cut/' + _file_path+'#view=fit&scrollbar=0');
                            
                                $("#modal-doc-title").html('Recorte de testigo');
                                $("#modal-doc-body").html(_cobject);
                                $("#modal-doc").modal('show');

                            } else if(_type=='pdf') {

                                var _cobject = $('<iframe></iframe>')
                                            .attr('id','iframe_modal_content_tmp')
                                            .attr('frameborder', '0')
                                            .attr('allowtransparency','false')
                                            .attr('width','100%')
                                            .attr('height', '600')
                                            .attr('src','http://www.gaimpresos.com/' + _file_path+'#view=fit&scrollbar=0');

                                $("#modal-doc-title").html('Testigo en PDF');
                                $("#modal-doc-body").html(_cobject);
                                $("#modal-doc").modal('show');

                            } else if(_type=='img') {

                                var _cobject = $('<img></img>')
                                        .attr('id','image_modal_content_tmp')
                                        .attr('class','img-responsive')
                                        .attr('src','http://www.gaimpresos.com/' + _file_path);

                                $("#modal-doc-title").html('Testigo en imagen');
                                $("#modal-doc-body").html(_cobject);
                                $("#modal-doc").modal('show');
                            
                            } else if(_type=='analytic') {
                                
                                var id          = $(this).data('id');
                                var _id         = '_' + id;

                                var _auditted   = CharacterData.check(id);

                                var form        = $("#note-detaill");

                                _analityc       = { data:[] };

                                $("#note_id", form).val(id);

                                if(_auditted.status==true) {
                                    $("#label-audit").html('Auditada').removeClass('label-danger').addClass('label-success');
                                    $("button#btn-add-audit").prop('disabled', true);
                                    $("button#btn-add-info").prop('disabled', true);
                                } else {
                                    $("#label-audit").html('No Auditada').removeClass('label-success').addClass('label-danger');
                                    $("button#btn-add-audit").prop('disabled', false);
                                    $("button#btn-add-info").prop('disabled', false);
                                }

                                $("#label-fecha").html(_noteMatrix[_id].Fecha);
                                $('span#nd-autor', form).html('Autor : '+(_noteMatrix[_id].Autor.trim()==''?'N/D':_noteMatrix[_id].Autor));
                                $('span#nd-section', form).html('Seccion : '+(_noteMatrix[_id].seccion.trim()==''?'N/D':_noteMatrix[_id].seccion));
                                $('span#nd-category', form).html('Categoria : '+(_noteMatrix[_id].Categoria.trim()==''?'N/D':_noteMatrix[_id].Categoria));
                                $('span#nd-page', form).html('Pagina : '+(_noteMatrix[_id].PaginaPeriodico.trim()==''?'N/D':_noteMatrix[_id].PaginaPeriodico));

                                $('#note-title', form).val(_noteMatrix[_id].Titulo.replace(/<(?:.|\n)*?>/gm, ''));
                                $('#note-header', form).val(_noteMatrix[_id].Encabezado.replace(/<(?:.|\n)*?>/gm, ''));
                                $('#note-text', form).val(_noteMatrix[_id].Texto.replace(/<(?:.|\n)*?>/gm, ''));
                                $('#note-pie', form).val(_noteMatrix[_id].PieFoto.replace(/<(?:.|\n)*?>/gm, ''));

                                $("table#table-badgets tbody").html('');
                                
                                if(_auditted.data.pieces!=undefined && _auditted.data.pieces.length>0) {

                                    $.each(_auditted.data.pieces, function(i,item) {
                                        var _item  = '<tr id="_a_' + item.id + '">';
                                            _item += '    <td style="width: 180px;" class="_c_actor">' + item.actor.name + '</td>';
                                            _item += '    <td><strong class="_c_topic">' + item.topic.text + '</strong></td>';
                                            _item += '    <td><strong class="_c_tipo">' + (item.type!=undefined && item.type!=null? item.type.name:'') + '</strong></td>';
                                            _item += '    <td class="text-center _c_status" style="width: 70px;"><span class="label label-' + (item.status=='p' ? 'success' : (item.status=='n' ? 'danger' : 'default') ) + '">' + (item.status=='p' ? 'Positiva' : (item.status=='n' ? 'Negativa' : 'Neutral') ) + '</span></td>';
                                            _item += '    <td class="text-right" style="width: 70px;">';
                                            _item += '      <div class="btn-group btn-group-xs">';
                                            _item += '          <button class="btn btn-xs btn-default btn-edit" data-pid="' + item.id + '" data-actor="' + item.actor_id + '" data-topic="' + item.topic_id + '" data-type="' + item.type_id+ '" data-status="' + item.status + '"><i class="gi gi-pencil"></i></button>';
                                            _item += '          <button class="btn btn-xs btn-danger btn-delete" data-id="' + _auditted.data.id + '" data-pid="' + item.id + '"><i class="gi gi-remove_2"></i></button>';
                                            _item += '      </div>';
                                            _item += '    </td>';
                                            _item += '</tr>';

                                        $("table#table-badgets tbody").append(_item);
                                    });

                                    $("table#table-badgets tbody tr").on('click','button',function(){

                                        if($(this).hasClass('btn-edit')) {

                                            var _form   = $('#form-note-calification');
        
                                            var aid     = $(this).data('id');
                                            var pid     = $(this).data('pid');

                                            var actor   = $(this).data('actor');
                                            var topic   = $(this).data('topic');
                                            var type    = $(this).data('type');
                                            var status  = $(this).data('status');

                                            $('#id', _form).val(aid);
                                            $('#pid',_form).val(pid);

                                            $("#c-note-actor option[value='" + actor + "']", _form).prop('selected', true);
                                            $("#c-note-topic option[value='" + topic + "']", _form).prop('selected', true);
                                            $("#c-note-type option[value='" + type + "']", _form).prop('selected', true);
                                            $("#c-note-status option[value='" + status + "']", _form).prop('selected', true);
                                            
                                            $('#modal-calificacion').modal('show');
                                        
                                        } else if($(this).hasClass('btn-delete')) {

                                            var _enews_id = $(this).data('id');
                                            var _piece_id = $(this).data('pid');

                                            var _quest = confirm('Esta seguro de eliminar el elemento seleccionado?');

                                            if(_quest==true) {
                                                $.d3POST('/cp/printed/rpiece',{eaudit:_enews_id,piece:_piece_id},function(data){
                                                    if(data.status==true) {
                                                        $.bootstrapGrowl(data.message, {
                                                            type: "success",
                                                            delay: 4500,
                                                            allow_dismiss: true
                                                        });
                                                        $('table#table-badgets tbody tr[id="_a_' + _piece_id + '"]').remove();
                                                    } else {
                                                        $.bootstrapGrowl(data.message, {
                                                            type: "danger",
                                                            delay: 4500,
                                                            allow_dismiss: true
                                                        });
                                                    }
                                                });
                                            }
                                        }
                                    });
                                }
                                
                                $("#modal-analytic").modal('show');
                            }

                        }
                    });
                }

            },false);

            $('#modal-calificacion').on('hidden.bs.modal', function(e){
                if($('#modal-analytic').css('display')=='block') {
                   $('body').addClass('modal-open'); 
                }
            });

            $('#btn-edit-audit').click(function(e){

                var _form   = $('#form-note-calification');

                var pid     = $('#pid',_form).val();

                var _actor  = $("#c-note-actor", _form).val();
                var _topic  = $("#c-note-topic", _form).val();
                var _type   = $("#c-note-type", _form).val();
                var _status = $("#c-note-status", _form).val();

                $.d3POST('/cp/electronic/upiece',{pid:pid, actor:_actor, topic:_topic, type:_type, status:_status},function(data){

                    if(data.status==true) {
                        
                        $.bootstrapGrowl(data.message, {
                            type: "success",
                            delay: 4500,
                            allow_dismiss: true
                        });
                        
                        $('table#table-badgets tbody tr[id="_a_' + data.piece.id + '"] td._c_actor').html(data.piece.actor.name);
                        $('table#table-badgets tbody tr[id="_a_' + data.piece.id + '"] td strong._c_topic').html(data.piece.topic.text);
                        $('table#table-badgets tbody tr[id="_a_' + data.piece.id + '"] td strong._c_type').html(data.piece.type.name);

                        var _obj_status = '<span class="label label-default">Neutral</span>';

                        if(data.piece.status=='p') _obj_status = '<span class="label label-success">Positiva</span>';
                        else if(data.piece.status=='n') _obj_status = '<span class="label label-danger">Negativa</span>';
                        else if(data.piece.status=='nn') _obj_status = '<span class="label label-default">Neutral</span>';

                        $('table#table-badgets tbody tr[id="_a_' + data.piece.id + '"] td._c_status').html(_obj_status);

                        var _mt = $('table#table-badgets tbody tr[id="_a_' + data.piece.id + '"] td div');

                        $('button.btn-edit', _mt).data('actor',data.piece.actor.id);
                        $('button.btn-edit', _mt).data('topic',data.piece.topic.id);
                        $('button.btn-edit', _mt).data('type',data.piece.type.id);
                        $('button.btn-edit', _mt).data('status',data.piece.status);

                        $('#modal-calificacion').modal('hide');

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
            
 		},

 		fill: function(collection,tab) {

 			if(parseInt(collection.count) > 0) {
                	
        		$.each(collection.data, function(i, item){

                    _noteMatrix["_"+item.idEditorial]=item;
                    
                    if(item.audited) _noteMatrix["_"+item.idEditorial];

        			var _panel_item  = '<div class="panel panel-default-d3">';
        				_panel_item += '	<div class="panel-heading-d3">';
						_panel_item += '		<div class="widget">';
                        _panel_item += '    		<div class="widget-d3">';
                        _panel_item += '				<a class="widget-image-container pull-left" data-toggle="collapse" data-parent="#tab-res-' + tab + '" href="#tab_res_' + tab + '_' + (i+1) + '">';
                        _panel_item += '            		<img class="widget-image" alt="' + item.Periodico + '" src="http://www.gaimpresos.com/img/portadas/thumbs/thumb-' + item.idPeriodico + '.jpg">';
                        _panel_item += '        		</a>';
                        _panel_item += '				<div class="row pull-left">';
                        _panel_item += '					<div class="col-md-12">';
                        _panel_item += '    					<dl>';
                        _panel_item += '        					<dt>' + item.Titulo + '</dt>';
                        _panel_item += '        					<dt>' + item.Periodico + ' | ' + item.estado + ' <i class="gi gi-clock"></i> ' + item.Fecha + '</dt>';
                        _panel_item += '        					<dd><span class="label label-success"># ' + (i+1) + '</span> <span class="label label-danger">ID : ' + item.idEditorial + '</span> <span class="label label-info">Autor : ' + (item.Autor==''?'N/D':item.Autor) + '</span> <span class="label label-info">Seccion : ' + (item.seccion==''?'N/D':item.seccion) + '</span> <span class="label label-info">Cateoria : ' + (item.Categoria==''?'N/D':item.Categoria) + '</span> <span class="label label-info">Pagina : ' + (item.PaginaPeriodico==''?'N/D':item.PaginaPeriodico) + '</span> ' + (item.audited?'<span class="label label-success">Auditada</span>':'') + '</dd>';
                        _panel_item += '    					</dl>';
                        _panel_item += '					</div>';
                        _panel_item += '				</div>';
                        _panel_item += '    		</div>';
                        _panel_item += '		</div>';
        				_panel_item += '	</div>';
        				_panel_item += '	<div id="tab_res_' + tab + '_' + (i+1) + '" class="panel-collapse collapse">';
        				_panel_item += '		<div class="panel-body">';
                        _panel_item += '            <div class="widget-extra-full mb7">';
                        _panel_item += '                <div class="btn-group">';
                        _panel_item += '                    <button class="btn btn-default btn-tool" data-toggle="tooltip" title="Analizar nota" data-type="analytic" data-id="' + item.idEditorial + '"><i class="fa fa-stethoscope"></i></button>';
                        _panel_item += '                </div>';
                        _panel_item += '                <div class="btn-group pull-right">';
                        
                        if(parseInt(item.Cutted)==1) _panel_item += '<button class="btn btn-default btn-tool" data-toggle="tooltip" title="Recorte de PDF" data-type="rpdf" data-url="' + item.idEditorial + ':gdl2015"><i class="fa fa-scissors"></i> <i class="fa fa-file-pdf-o"></i></button>';
                        
                        if(item.pdf.indexOf('.pdf')>=0) {
                            _panel_item += '<button class="btn btn-default btn-tool" data-toggle="tooltip" title="Imagen" data-type="img" data-url="' + item.pdf + '.jpg"><i class="fa fa-file-image-o"></i></button>';
                            _panel_item += '<button class="btn btn-default btn-tool" data-toggle="tooltip" title="PDF" data-type="pdf" data-url="' + item.pdf + '"><i class="fa fa-file-pdf-o"></i></button>';
                        } else {
                            _panel_item += '<a href="' + item.Encabezado + '" class="btn btn-default btn-tool" data-toggle="tooltip" title="Link" target="_blank"><i class="fa fa-globe"></i></a>';
                        }

                        //_panel_item += '                    <button class="btn btn-default btn-tool" data-toggle="tooltip" title="Email" data-type="email" data-id="xx"><i class="fa fa-envelope-o"></i></button>';
                        _panel_item += '                </div>';
                        _panel_item += '            </div>';
                        
                        if(item.Encabezado.trim()!="" && item.Encabezado.trim().substring(0, 4)!='http') _panel_item += '<h3>' + item.Encabezado.trim() + '</h3>';
        				
                        _panel_item += '			<p class="text-justify">' + item.Texto + '</p>';
        				
                        if(item.PieFoto.trim()!='' && item.PieFoto.trim()!='|') _panel_item += '<p class="well well-sm"><strong>Pie de foto :</strong> ' + item.PieFoto.trim() + '</p>';

                        _panel_item += '		</div>';
        				_panel_item += '	</div>';
        				_panel_item += '</div>';

        				$("#tab-res-" + tab).append(_panel_item);

        		});

        	}
 		},

        actors: function() {
            $.d3GET(base_path+'/ajax/actors',{},function(data){
                if(data.status == true && data.actors.length > 0) {
                    
                    var _selects = '<option></option>';
                    var _selectb = '';
                    
                    $("#note-actor").empty();
                    $("#c-note-actor").empty();
                    
                    $.each(data.actors, function(i, item){
                        _selects += '<option value="' + item.id + '">' + item.name + '</option>';
                        _selectb += '<option value="' + item.id + '">' + item.name + '</option>';
                    });
                    
                    $("#note-actor").append(_selects).trigger("chosen:updated");
                    $("#c-note-actor").append(_selectb)
                }
            });
        },

        themes: function() {
            $.d3GET(base_path+'/ajax/themes',{},function(data){
                if(data.status == true && data.actors.length > 0) {
                    
                    var _selects = '<option></option>';
                    var _selectb = '';
                    
                    $("#note-topic").empty();
                    $("#c-note-topic").empty();
                    
                    $.each(data.actors, function(i, item){
                        _selects += '<option value="' + item.id + '">' + item.text + '</option>';
                        _selectb += '<option value="' + item.id + '">' + item.text + '</option>';
                    });
                    
                    $("#note-topic").append(_selects).trigger("chosen:updated");
                    $("#c-note-topic").append(_selectb);
                }
            });
        },

        types: function() {
            $.d3GET(base_path+'/ajax/types',{},function(data){
                if(data.status == true && data.types.length > 0) {
                    
                    var _selects = '<option></option>';
                    var _selectb = '';
                    
                    $("#note-type").empty();
                    $("#c-note-type").empty();
                    
                    $.each(data.types, function(i, item){
                        _selects += '<option value="' + item.id + '">' + item.name + '</option>';
                        _selectb += '<option value="' + item.id + '">' + item.name + '</option>';
                    });
                    
                    $("#note-type").append(_selects).trigger("chosen:updated");
                    $("#c-note-type").append(_selectb);
                }
            });
        },

        check: function(id) {
            var audited = {status: false, data:[] };
            $.d3GET(base_path+'/ajax/note_check',{id:id},function(data){
                if(data.status == true) {
                    audited.status = true;
                    audited.data = data.note;
                }
            },false);
            return audited;
        },

        fillTempAudit: function(data) {
            
            var _newActorId = data.actor_id;
            var _actorCount = 0;

            $.each(_analityc.data, function(i, d) {
                if(_newActorId==d.actor_id) _actorCount++;
            });

            if(_actorCount < 10 && _analityc.data.length < 30) _analityc.data.push(data);
            else {
                $.bootstrapGrowl("Solo puede agregar 10 temas por actor y solo puede agregar 3 actores como maximo", {
                    type: "danger",
                    delay: 4500,
                    allow_dismiss: true
                });

                return false;
            }

            return true;

        }
 	}

 }();
