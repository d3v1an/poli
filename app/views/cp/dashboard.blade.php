@extends ('layout.admin')

<!-- Contenido -->
@section ('content')
    <div id="page-content">
        <!-- Blank Header -->
        <div class="content-header">
            <div class="header-section">
                <h1>
                    <i class="gi gi-signal"></i><span class="char-name">Dashboard</span><br><small>{{ $actor->name }}</small>
                </h1>
            </div>
        </div>
        <ul class="breadcrumb breadcrumb-top">
            <li>Panel de control</li>
            <li>Personajes</li>
        </ul>
        <!-- END Blank Header -->

    	<div class="row">
    		
    		<!-- Menu -->
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
	                            <a href="{{ URL::to('cp/report/printed/' . $actor->id) }}">
	                                <i class="fa fa-angle-right fa-fw"></i> <strong>{{ $actor->name }}</strong>
	                            </a>
	                        </li>
	                    @endforeach
	                </ul>
	                <!-- END Menu Content -->
	            </div>
	            <!-- END Menu Block -->

	        </div>
	        <!-- /Menu -->

	        <!-- Content -->
	        <div class="col-sm-8 col-lg-9">

	        	<!-- Statics Block -->
            	<div class="block">
            		
            		<!-- Statics Bar Title -->
	                <div class="block-title">
	                    <h2>Estadísticas de comportamiento</h2>
	                    <div class="block-options pull-right">
	                        <div class="btn-group btn-group-sm">
	                            <a href="javascript:void(0)" class="btn btn-alt btn-sm btn-default dropdown-toggle enable-tooltip" data-toggle="dropdown" title="Opciónes"><span class="caret"></span></a>
	                            <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
	                                <li><a href="javascript:void(0)" id="btn-data-range">Rango de fechas</a></li>
	                                <!--
	                                <li class="dropdown-header">Exportar</li>
	                                <li><a href="javascript:void(0)" id="btn-export" data-type="excel" data-actor="">Excel</a></li>
	                                -->
	                            </ul>
	                        </div>
	                    </div>
	                </div>
	                <!-- /Statics Bar Title -->

	                <div class="block-content-mini-padding">
	                	
	                	<div class="row">
	                		
	                		<div class="col-md-6">
	                			<div class="widget">
		                            <div class="widget-simple">
		                                <h4 class="widget-content">
		                                    Impresos
		                                </h4>
		                            </div>
		                            <div class="widget-extra">
		                                <div class="row text-center themed-background-dark-modern">
		                                    <div class="col-xs-4">
		                                        <h3 class="widget-content-light">
		                                            <i class="gi gi-thumbs_up"></i><br>
		                                            <small>3.200</small>
		                                        </h3>
		                                    </div>
		                                    <div class="col-xs-4">
		                                        <h3 class="widget-content-light">
		                                            <i class="gi gi-thumbs_down"></i><br>
		                                            <small>2.500</small>
		                                        </h3>
		                                    </div>
		                                    <div class="col-xs-4">
		                                        <h3 class="widget-content-light">
		                                            <i class="gi gi-hand_right"></i><br>
		                                            <small>580</small>
		                                        </h3>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
	                		</div>
	                		<div class="col-md-6">
	                			<div class="widget">
		                            <div class="widget-simple">
		                                <h4 class="widget-content">
		                                    Electronicos
		                                </h4>
		                            </div>
		                            <div class="widget-extra">
		                                <div class="row text-center themed-background-dark">
		                                    <div class="col-xs-4">
		                                        <h3 class="widget-content-light">
		                                            <i class="gi gi-thumbs_up"></i><br>
		                                            <small>3.200</small>
		                                        </h3>
		                                    </div>
		                                    <div class="col-xs-4">
		                                        <h3 class="widget-content-light">
		                                            <i class="gi gi-thumbs_down"></i><br>
		                                            <small>2.500</small>
		                                        </h3>
		                                    </div>
		                                    <div class="col-xs-4">
		                                        <h3 class="widget-content-light">
		                                            <i class="gi gi-hand_right"></i><br>
		                                            <small>580</small>
		                                        </h3>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
	                		</div>

	                	</div>


	                </div>

            	</div>
            	<!-- /Statics Block -->

	        </div>
	        <!-- /Content -->
    	</div>

    </div>
@stop
<!-- /Contenido -->