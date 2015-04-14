$(function(){

    $('#btn-add-nota').click(function(e){
        $('#modal-note').modal('show');
        e.preventDefault();
    });

    $('#note-program').on('change', function() {

        var _dInfor     = $(this).val().split(':');
        var _source     = _dInfor[1];
        var _condutcor  = _dInfor[2];

        $('#_note_source').val(_source);
        $('#_note_conductor').val(_condutcor);

        console.log( $(this).val() );
    });

    $.loadPrograms = function() {
        // Carga de programas
        $.d3GET(base_path+'/ajax/programs',{},function(data){
            if(data.status == true && data.actors.length > 0) {
                var _selects = '<option value="0">Seleccione un programa</option>';
                $("#note-program").empty();
                $.each(data.actors, function(i, item){
                    _selects += '<option value="' + item.id + ':' + item.source.name + ':' + item.comunicator.name + '">' + item.name + '</option>';
                });
                $("#note-program").append(_selects);
            }
        });
    }
    $.loadPrograms();

    $.loadComunicators = function() {
        // Carga de comunicadores
        $.d3GET(base_path+'/ajax/comunicators',{},function(data){
            if(data.status == true && data.actors.length > 0) {
                var _selects = '<option value="0">Autor</option>';
                $("#note-autor").empty();
                $.each(data.actors, function(i, item){
                    _selects += '<option value="' + item.id + '">' + item.name + '</option>';
                });
                $("#note-autor").append(_selects);
            }
        });
    }
    $.loadComunicators();

    $.loadActors = function() {
        // Carga de actores
        $.d3GET(base_path+'/ajax/actors',{},function(data){
            if(data.status == true && data.actors.length > 0) {
                
                var _selects = '<option></option>';
                var _selectb = '';

                $("#note-actor").empty();
                $("#c-note-actor").empty();
                $("#to_actor").empty();

                $.each(data.actors, function(i, item){
                    _selects += '<option value="' + item.id + '">' + item.name + '</option>';
                    if(item.status==1) _selectb += '<option value="' + item.id + '">' + item.name + '</option>';
                });
                
                $("#note-actor").append(_selects).trigger("chosen:updated");
                $("#c-note-actor").append(_selectb);
                $("#to_actor").append(_selectb);
            }
        });
    }
    $.loadActors();

    $.loadTopics = function() {
        // Temas
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
    }
    $.loadTopics();

    $.loadTypes = function() {
        // Tipos
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
    }
    $.loadTypes();

    // Boton de auditoria
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

        var _filled = $.fillTempAudit({ actor_id: _actor_id, topic_id: _topic_id, type_id: _type_id, status: _status })

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

    // Array temporal
    var _analityc       = { data:[] };

    $.fillTempAudit = function(data) {
    
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

    };

    $("#btn-add-audit").click(function(e){

        var _form       = $("#note-detaill");
        var _program    = $("#note-program", _form).val();
        var _autor      = $("#note-autor", _form).val();
        var _title      = $("#note-title", _form).val();
        var _header     = $("#note-header", _form).val();
        var _note       = CKEDITOR.instances.noteText.getData();
        $("#noteText", _form).val(_note);

        if(_program==0) {
            alert('Seleccione un programa valido');
            return false;
        }
        if(_autor==0) {
            alert('Seleccion un autor valido');
            return false;
        }
        if(_title=='') {
            alert('Ingresa un titulo a la nota');
            return false;
        }
        // if(_header=='') {
        //     alert('Ingresa un encabezado a la nota');
        //     return false;
        // }.
        if(_note=='') {
            alert('Ingrese un texto para la nota');
            return false;
        }
        
        var _meta = [];

        $.each(_analityc.data,function(i, m) {
            var _md = m.actor_id+':'+m.topic_id+':'+m.type_id+':'+m.status;
            _meta.push(_md);
        });

        var _metaStr        = _meta.join("|");

        $('#meta', _form).val(_metaStr);

        _form.submit();

    });

    $(".download-media").click(function(e){
        var _media = $(this).data('media');
        window.location.href = '/cp/electronic/download/' + _media;
        e.preventDefault();
    });

    $('.btn-delete').click(function(e){

        var _enews_id = $(this).data('id');
        var _piece_id = $(this).data('pid');

        var _quest = confirm('Esta seguro de eliminar el elemento seleccionado?');

        if(_quest==true) {
            $.d3POST('/cp/electronic/rpiece',{eaudit:_enews_id,piece:_piece_id},function(data){
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

        e.preventDefault();
    });

    $('.btn-edit').click(function(e){

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
        e.preventDefault();
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

    $('.btn-add-actor').click(function(e){

        var _actor = $('#new-actor').val();
        
        if(_actor=='') {
            $.bootstrapGrowl('Ingrese un nombre de actor valido', {
                type: "danger",
                delay: 4500,
                allow_dismiss: true
            });
            return false;
        }

        $.d3POST(base_path+'/ajax/add_actor',{name:_actor},function(data){
            if(data.status == true) {
                $.bootstrapGrowl(data.message, {
                    type: "success",
                    delay: 4500,
                    allow_dismiss: true
                });
                $.loadActors();
                $('#new-actor').val('');
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

    $('.btn-add-tema').click(function(e){
        
        var _tema = $('#new-tema').val();
        
        if(_tema=='') {
            $.bootstrapGrowl('Ingrese un nombre de tema valido', {
                type: "danger",
                delay: 4500,
                allow_dismiss: true
            });
            return false;
        }

        $.d3POST(base_path+'/ajax/add_tema',{text:_tema},function(data){
            if(data.status == true) {
                $.bootstrapGrowl(data.message, {
                    type: "success",
                    delay: 4500,
                    allow_dismiss: true
                });
                $.loadTopics();
                $('#new-tema').val('');
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

    $('.btn-add-tipo').click(function(e){
        
        var _tipo = $('#new-tipo').val();
        
        if(_tipo=='') {
            $.bootstrapGrowl('Ingrese un nombre de tipo valido', {
                type: "danger",
                delay: 4500,
                allow_dismiss: true
            });
            return false;
        }

        $.d3POST(base_path+'/ajax/add_type',{text:_tipo},function(data){
            if(data.status == true) {
                $.bootstrapGrowl(data.message, {
                    type: "success",
                    delay: 4500,
                    allow_dismiss: true
                });
                $.loadTypes();
                $('#new-tipo').val('');
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
});