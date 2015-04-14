$(function(){
        
    $("#add_fuente").click(function(){
        $("#modal-fuente").modal('show');
    });
    $("#add_comunicador").click(function(){
        $("#modal-comunicador").modal('show');
    });
    $("#add_programa").click(function(){
        $("#modal-programa").modal('show');
    });

    // * Fuentes
    $('#btn-add-fuente').click(function(){
        
        var _form = $('#form-fuente');
        var _data = $('#data_fuente',_form).val();
        
        if(_data=='') {
            alert('Ingrese una fuente valida');
            return false;
        }

        $.d3POST('/cp/misc/catalogs/source',{data:_data},function(data){
        
            if(data.status == true) {
        
                $.bootstrapGrowl(data.message, {
                    type: "success",
                    delay: 4500,
                    allow_dismiss: true
                });

                $.loadSources(data);
        
                $('#data_fuente',_form).val('');
                $("#modal-fuente").modal('hide');
        
            } else {
                $.bootstrapGrowl(data.message, {
                    type: "danger",
                    delay: 4500,
                    allow_dismiss: true
                });
            }
        });

    });
    $.loadSources = function(data) {

        var _defaul     = '<option value="0">Fuente..</option>';
        var _sform      = $('#form-programa');
        var _select     = $('#data_fuente', _sform);

        var _table      = $('table.tsources');

        if(data.sources.length > 0) {
                    
            _select.empty();
            _select.append(_defaul);

            $('tbody',_table).empty();

            $.each(data.sources,function(i, item){
                
                _select.append('<option value="' + item.id + '">' + item.name + '</option>');

                var _tr  = '<tr>';
                    _tr += '    <td>' + item.name + '</td>';
                    _tr += '    <td class="text-center">';
                    _tr += '        <div class="btn-group btn-group-xs">';
                    // _tr += '            <a href="javascript:void(0)" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
                    // _tr += '            <a href="javascript:void(0)" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="fa fa-times"></i></a>';
                    _tr += '        </div>';
                    _tr += '    </td>';
                    _tr += '</tr>';

                $('tbody',_table).append(_tr);
            });
        }

    };
    $.d3GET('/cp/misc/catalogs/sources',{},function(data){
        if(data.status == true) $.loadSources(data);
    });

    // Programas
    $('#btn-add-programa').click(function(){
        
        var _form           = $('#form-programa');
        var _fuente         = $('#data_fuente', _form).val();
        var _data           = $('#data_programa',_form).val();
        var _comunicador    = $('#data_comunicador', _form).val();
        
        if(_fuente=='0') {
            alert('Selecciona una fuente valida');
            return false;
        }
        if(_data=='') {
            alert('Ingrese un programa valido');
            return false;
        }
        if(_comunicador=='0') {
            alert('Seleccione un comunicador valido');
            return false;
        }

        $.d3POST('/cp/misc/catalogs/program',{data:_data, comunicador:_comunicador,fuente:_fuente},function(data){

            if(data.status == true) {
        
                $.bootstrapGrowl(data.message, {
                    type: "success",
                    delay: 4500,
                    allow_dismiss: true
                });
                
                $.loadPrograms(data);

                $('#data_programa',_form).val('');
                $("#modal-programa").modal('hide');
        
            } else {
                $.bootstrapGrowl(data.message, {
                    type: "danger",
                    delay: 4500,
                    allow_dismiss: true
                });
            }
        });

    });
    $.loadPrograms = function(data) {

        var _sform      = $('#form-programa');
        var _select     = $('#data_fuente', _sform);

        var _table      = $('table.tprogram');

        if(data.sources.length > 0) {

            $('tbody',_table).empty();

            $.each(data.sources,function(i, item){

                var _tr  = '<tr>';
                    _tr += '    <td>' + item.source.name + '</td>';
                    _tr += '    <td>' + item.name + '</td>';
                    _tr += '    <td>' + item.comunicator.name + '</td>';
                    _tr += '    <td class="text-center">';
                    _tr += '        <div class="btn-group btn-group-xs">';
                    // _tr += '            <a href="javascript:void(0)" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
                    // _tr += '            <a href="javascript:void(0)" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="fa fa-times"></i></a>';
                    _tr += '        </div>';
                    _tr += '    </td>';
                    _tr += '</tr>';

                $('tbody',_table).append(_tr);
            });
        }

    };
    $.d3GET('/cp/misc/catalogs/programs',{},function(data){
        if(data.status == true) $.loadPrograms(data);
    });

    // * Comunicadores
    $('#btn-add-comunicador').click(function(){

        var _data = $('#data_comunicador','#form-comunicador').val();
        
        if(_data=='') {
            alert('Ingrese un comunicador valido');
            return false;
        }

        $.d3POST('/cp/misc/catalogs/comunicator',{data:_data},function(data){
        
            if(data.status == true) {
        
                $.bootstrapGrowl(data.message, {
                    type: "success",
                    delay: 4500,
                    allow_dismiss: true
                });

                $.loadComunicators(data);
        
                $('#data_comunicador','#form-comunicador').val('');
                $("#modal-comunicador").modal('hide');
        
            } else {
                $.bootstrapGrowl(data.message, {
                    type: "danger",
                    delay: 4500,
                    allow_dismiss: true
                });
            }
        });

    });
    $.loadComunicators = function(data) {

        var _defaul     = '<option value="0">Comunicador..</option>';
        var _sform      = $('#form-programa');
        var _select     = $('#data_comunicador', _sform);

        var _table      = $('table.tcomunicators');

        if(data.sources.length > 0) {
                    
            _select.empty();
            _select.append(_defaul);

            $('tbody',_table).empty();

            $.each(data.sources,function(i, item){
                
                _select.append('<option value="' + item.id + '">' + item.name + '</option>');

                var _tr  = '<tr>';
                    _tr += '    <td>' + item.name + '</td>';
                    _tr += '    <td class="text-center">';
                    _tr += '        <div class="btn-group btn-group-xs">';
                    // _tr += '            <a href="javascript:void(0)" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
                    // _tr += '            <a href="javascript:void(0)" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="fa fa-times"></i></a>';
                    _tr += '        </div>';
                    _tr += '    </td>';
                    _tr += '</tr>';

                $('tbody',_table).append(_tr);
            });
        }

    };
    $.d3GET('/cp/misc/catalogs/comunicators',{},function(data){
        if(data.status == true) $.loadComunicators(data);
    });

});