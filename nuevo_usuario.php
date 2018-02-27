<div class="box box-solid">
    <div class="box-header with-border">
        <i class="fa fa-laptop"></i>
        <h3 class="box-title">Administracion</h3>
        <a href="javascript:void(0);" onclick="cargarformularios(1)">
            <i class="fa fa-map-marker text-light-blue"></i>
            <h3 class="box-title text-light-blue">Usuarios</h3>
        </a>
    </div><!-- /.box-header -->
</div>

<div class="row">

    <div class="col-md-6">

        <div class="box box-primary">

            <div class="box-header">
                <h3 class="box-title">Crear nueva Cuenta de Usuario</h3>
            </div><!-- /.box-header -->

            <div id="notificacion_resul_fanu"></div>

            <form  id="f_nuevo_usuario"  method="post"  action="agregar_nuevo_usuario" class="form-horizontal form_entrada" >
                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

                <div class="box-body">
                    <div class="form-group col-xs-12">
                        <label for="nombre">Nombres*</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="" placeholder="Ingresar Nombres">
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="apellido">Apellidos*</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" value="" placeholder="Ingresar Apellidos">
                    </div>
                    <div class="form-group col-xs-6">
                        <label for="tipo">Tipo de Usuario</label>

                        <select id="tipo" name="tipo" class="form-control">

                            <option value="1">Administrador</option>
                            <option value="2">Tecnico</option>
<!--                            <option value="2">Usuario</option>-->
                        </select>
                    </div>


                    <div class="form-group col-xs-12">
                        <label for="email">Email*</label>
                        <input type="text" class="form-control" id="email" name="email" value="" placeholder="Ingresar email">
                    </div>

                    <div class="form-group col-xs-12">
                        <label for="password">password*</label>
                        <input type="password" class="form-control" id="password" name="password" value=""p required placeholder="Ingresar password">
                    </div>

                </div>

                <div class="box-footer col-xs-12 ">
                    <button type="submit" class="btn btn-primary">Guardar Datos</button>
                </div>


            </form>

        </div>
    </div>    <!-- end col mod 6 -->

</div> <!-- end row -->
