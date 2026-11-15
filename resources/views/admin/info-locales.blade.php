@extends('layouts.app')
@section('titulo')
    Datos de locales
@endsection

@section('contenido')
    <div class="row">
        <div class="col-md-12">
            <div id="panel-info-locales" class="panel">
                <div class="panel-hdr">
                    <h2><span class="fw-300"><i>Administración</i></span>&nbsp;&nbsp;DATOS DE LOCALES</h2>
                    <div class="panel-toolbar">
                        <button class="btn btn-panel" data-action="panel-collapse" data-toggle="tooltip" data-original-title="Collapse"></button>
                        <button class="btn btn-panel" data-action="panel-fullscreen" data-toggle="tooltip" data-original-title="Fullscreen"></button>
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <div class="row ml-1 mb-3">
                            <button type="button" class="btn btn-primary" onclick="nuevo()" data-toggle="modal" data-target=".default-example-modal-right">
                                Agregar datos de local
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="tabla_info_locales" class="table table-bordered table-hover table-striped w-100">
                                <thead class="bg-primary-600">
                                    <tr>
                                        <th>ID</th>
                                        <th>Local</th>
                                        <th>Razón social</th>
                                        <th>Dirección</th>
                                        <th>RUC</th>
                                        <th>Teléfono</th>
                                        <th>Celular</th>
                                        <th>Celular adicional</th>
                                        <th>Email</th>
                                        <th>BCP cuenta corriente soles</th>
                                        <th>BCP CCI soles</th>
                                        <th>BCP cuenta corriente dólares</th>
                                        <th>BCP CCI dólares</th>
                                        <th>Interbank cuenta corriente soles</th>
                                        <th>Interbank CCI soles</th>
                                        <th>Interbank cuenta corriente dólares</th>
                                        <th>Interbank CCI dólares</th>
                                        <th>QR pago</th>
                                        <th>Link web</th>
                                        <th>Dominio de sistema</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade default-example-modal-right" id="modal_info_locales" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog modal-dialog-right">
            <div class="modal-content">
                <form id="form_info_locales" novalidate>
                    <div class="modal-header">
                        <h5 id="titulo_modal_info_locales" class="modal-title h4">DATOS DEL LOCAL</h5>
                        <button type="button" class="close" aria-label="Close" onclick="cerrarModal()">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id">
                        <div class="card mb-g">
                            <div class="card-header py-2">
                                <h6 class="mb-0"><i class="fal fa-building text-primary mr-2"></i>Identificación del local</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="form-row">
                                    <div class="form-group col-md-6 mb-3">
                                        <label for="local">Local <span class="text-danger">*</span></label>
                                        <input type="text" id="local" class="form-control text-uppercase">
                                        <div id="error_local" class="text-danger d-none"></div>
                                    </div>
                                    <div class="form-group col-md-6 mb-3">
                                        <label for="razon_social">Razón social <span class="text-danger">*</span></label>
                                        <input type="text" id="razon_social" class="form-control text-uppercase" maxlength="50" required>
                                        <div id="error_razon_social" class="text-danger d-none"></div>
                                    </div>
                                    <div class="form-group col-md-6 mb-3">
                                        <label for="ruc">RUC <span class="text-danger">*</span></label>
                                        <input type="number" id="ruc" class="form-control text-uppercase" maxlength="11" minlength="11">
                                        <div id="error_ruc" class="text-danger d-none"></div>
                                    </div>
                                    <div class="form-group col-12 mb-0">
                                        <label for="direccion">Dirección <span class="text-danger">*</span></label>
                                        <input type="text" id="direccion" class="form-control text-uppercase" maxlength="150">
                                        <div id="error_direccion" class="text-danger  d-none"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-g">
                            <div class="card-header py-2">
                                <h6 class="mb-0"><i class="fal fa-address-card text-primary mr-2"></i>Contacto</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="form-row">
                                    <div class="form-group col-md-6 mb-3">
                                        <label for="telefono">Teléfono <span class="text-danger">*</span></label>
                                        <input type="text" id="telefono" class="form-control" maxlength="30">
                                        <div id="error_telefono" class="text-danger  d-none"></div>
                                    </div>
                                    <div class="form-group col-md-6 mb-3">
                                        <label for="celular">Celular <span class="text-danger">*</span></label>
                                        <input type="text" id="celular" class="form-control" maxlength="30">
                                        <div id="error_celular" class="text-danger  d-none"></div>
                                    </div>
                                    <div class="form-group col-md-6 mb-3">
                                        <label for="celular2">Celular adicional</label>
                                        <input type="text" id="celular2" class="form-control" maxlength="30">
                                        <div id="error_celular2" class="text-danger d-none"></div>
                                    </div>
                                    <div class="form-group col-12 mb-0">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input type="email" id="email" class="form-control" maxlength="50">
                                        <div id="error_email" class="text-danger  d-none"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-g">
                            <div class="card-header py-2">
                                <h6 class="mb-0"><i class="fal fa-university text-primary mr-2"></i>Cuentas bancarias</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="form-row">
                                    <div class="form-group col-12 mb-2">
                                        <span class="text-muted small text-uppercase font-weight-bold">BCP</span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="cta_cte_bcp_soles">Cuenta corriente · Soles</label>
                                        <input type="text" id="cta_cte_bcp_soles" class="form-control" maxlength="50">
                                        <div id="error_cta_cte_bcp_soles" class="text-danger d-none"></div>
                                        <div class="custom-control custom-switch mt-2">
                                            <input type="checkbox" class="custom-control-input" id="cta_cte_bcp_soles_check" value="1">
                                            <label class="custom-control-label" for="cta_cte_bcp_soles_check">Mostrar en PDF</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="cta_cci_bcp_soles">Cuenta CCI · Soles</label>
                                        <input type="text" id="cta_cci_bcp_soles" class="form-control" maxlength="50">
                                        <div id="error_cta_cci_bcp_soles" class="text-danger d-none"></div>
                                        <div class="custom-control custom-switch mt-2">
                                            <input type="checkbox" class="custom-control-input" id="cta_cci_bcp_soles_check" value="1">
                                            <label class="custom-control-label" for="cta_cci_bcp_soles_check">Mostrar en PDF</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="cta_cte_bcp_dolares">Cuenta corriente · Dólares</label>
                                        <input type="text" id="cta_cte_bcp_dolares" class="form-control" maxlength="50">
                                        <div id="error_cta_cte_bcp_dolares" class="text-danger d-none"></div>
                                        <div class="custom-control custom-switch mt-2">
                                            <input type="checkbox" class="custom-control-input" id="cta_cte_bcp_dolares_check" value="1">
                                            <label class="custom-control-label" for="cta_cte_bcp_dolares_check">Mostrar en PDF</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="cta_cci_bcp_dolares">Cuenta CCI · Dólares</label>
                                        <input type="text" id="cta_cci_bcp_dolares" class="form-control" maxlength="50">
                                        <div id="error_cta_cci_bcp_dolares" class="text-danger d-none"></div>
                                        <div class="custom-control custom-switch mt-2">
                                            <input type="checkbox" class="custom-control-input" id="cta_cci_bcp_dolares_check" value="1">
                                            <label class="custom-control-label" for="cta_cci_bcp_dolares_check">Mostrar en PDF</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 mb-2 mt-2">
                                        <span class="text-muted small text-uppercase font-weight-bold">Interbank</span>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="cta_cte_interbank_soles">Cuenta corriente · Soles</label>
                                        <input type="text" id="cta_cte_interbank_soles" class="form-control" maxlength="30">
                                        <div id="error_cta_cte_interbank_soles" class="text-danger d-none"></div>
                                        <div class="custom-control custom-switch mt-2">
                                            <input type="checkbox" class="custom-control-input" id="cta_cte_interbank_soles_check" value="1">
                                            <label class="custom-control-label" for="cta_cte_interbank_soles_check">Mostrar en PDF</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="cta_cci_interbank_soles">Cuenta CCI · Soles</label>
                                        <input type="text" id="cta_cci_interbank_soles" class="form-control" maxlength="30">
                                        <div id="error_cta_cci_interbank_soles" class="text-danger d-none"></div>
                                        <div class="custom-control custom-switch mt-2">
                                            <input type="checkbox" class="custom-control-input" id="cta_cci_interbank_soles_check" value="1">
                                            <label class="custom-control-label" for="cta_cci_interbank_soles_check">Mostrar en PDF</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-0">
                                        <label for="cta_cte_interbank_dolares">Cuenta corriente · Dólares</label>
                                        <input type="text" id="cta_cte_interbank_dolares" class="form-control" maxlength="30">
                                        <div id="error_cta_cte_interbank_dolares" class="text-danger d-none"></div>
                                        <div class="custom-control custom-switch mt-2">
                                            <input type="checkbox" class="custom-control-input" id="cta_cte_interbank_dolares_check" value="1">
                                            <label class="custom-control-label" for="cta_cte_interbank_dolares_check">Mostrar en PDF</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-0">
                                        <label for="cta_cci_interbank_dolares">Cuenta CCI · Dólares</label>
                                        <input type="text" id="cta_cci_interbank_dolares" class="form-control" maxlength="30">
                                        <div id="error_cta_cci_interbank_dolares" class="text-danger d-none"></div>
                                        <div class="custom-control custom-switch mt-2">
                                            <input type="checkbox" class="custom-control-input" id="cta_cci_interbank_dolares_check" value="1">
                                            <label class="custom-control-label" for="cta_cci_interbank_dolares_check">Mostrar en PDF</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-g">
                            <div class="card-header py-2">
                                <h6 class="mb-0"><i class="fal fa-qrcode text-primary mr-2"></i>QR de pago</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="form-row align-items-center">
                                    <div class="form-group col-md-7 mb-md-0">
                                        <label for="qr_pago">Imagen QR <span class="text-danger">*</span></label>
                                        <input type="file" id="qr_pago" class="form-control" accept="image/jpeg,image/png,image/webp" required>
                                        <div id="error_qr_pago" class="text-danger d-none"></div>
                                    </div>
                                    <div class="col-md-5 text-center">
                                        <img id="qr_preview" class="img-thumbnail mt-2 d-none" alt="Vista previa del QR de pago"
                                            style="max-width: 150px; max-height: 150px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-g">
                            <div class="card-header py-2">
                                <h6 class="mb-0"><i class="fal fa-globe text-primary mr-2"></i>Presencia digital</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="form-row">
                                    <div class="form-group col-md-6 mb-md-0">
                                        <label for="link_web">Link web <span class="text-danger">*</span></label>
                                        <input type="text" id="link_web" class="form-control" maxlength="100">
                                        <div id="error_link_web" class="text-danger d-none"></div>
                                    </div>
                                    <div class="form-group col-md-6 mb-0">
                                        <label for="dominio_sistema">Dominio de sistema </label>
                                        <input type="text" id="dominio_sistema" class="form-control" maxlength="100">
                                        <div id="error_dominio_sistema" class="text-danger d-none"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="guardar()">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $("#menu_administracion").addClass("active open");
            $("#submenu_info_locales").addClass("active");

            $("#tabla_info_locales").DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                scrollX: true,
                ajax: "{{ route('info-locales.index') }}",
                columns: [{
                        data: 'id',
                        className: 'text-center',
                        visible: false
                    },
                    {
                        data: 'local'
                    },
                    {
                        data: 'razon_social'
                    },
                    {
                        data: 'direccion'
                    },
                    {
                        data: 'ruc'
                    },
                    {
                        data: 'telefono'
                    },
                    {
                        data: 'celular'
                    },
                    {
                        data: 'celular2',
                        visible: false
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'cta_cte_bcp_soles',
                        visible: false
                    },
                    {
                        data: 'cta_cci_bcp_soles',
                        visible: false
                    },
                    {
                        data: 'cta_cte_bcp_dolares',
                        visible: false
                    },
                    {
                        data: 'cta_cci_bcp_dolares',
                        visible: false
                    },
                    {
                        data: 'cta_cte_interbank_soles',
                        visible: false
                    },
                    {
                        data: 'cta_cci_interbank_soles',
                        visible: false
                    },
                    {
                        data: 'cta_cte_interbank_dolares',
                        visible: false
                    },
                    {
                        data: 'cta_cci_interbank_dolares',
                        visible: false
                    },
                    {
                        data: 'qr_url',
                        orderable: false,
                        searchable: false,
                        render: function(url, type) {
                            if (type !== 'display' || !url) {
                                return '';
                            }
                            return '<img src="' + $('<div>').text(url).html() +
                                '" alt="QR de pago" style="width:64px;height:64px;object-fit:contain;">';
                        }
                    },
                    {
                        data: 'link_web',
                        visible: false
                    },
                    {
                        data: 'dominio_sistema',
                        visible: false
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                dom: dom_buttons_table,
                buttons: [{
                    extend: 'pdfHtml5',
                    text: 'PDF',
                    titleAttr: 'Generate PDF',
                    className: 'btn-outline-danger btn-sm mr-1',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 18, 19]
                    }
                }, {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    titleAttr: 'Generate Excel',
                    className: 'btn-outline-success btn-sm mr-1',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 18, 19]
                    }
                }],
                lengthMenu: [
                    [50, 100, -1],
                    [50, 100, "All"]
                ],
                language: espanol,
                ordering: false,
            });

            $('#qr_pago').on('change', function() {
                const file = this.files[0];
                if (!file) {
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(event) {
                    $('#qr_preview').attr('src', event.target.result).removeClass('d-none');
                };
                reader.readAsDataURL(file);
            });
        });

        function nuevo() {
            limpiarFormulario();
            $('#titulo_modal_info_locales').text('AGREGAR DATOS DEL LOCAL');
            $('#qr_pago').prop('required', true);
        }

        function getdata(id) {
            limpiarFormulario();
            $.ajax({
                url: "{{ route('info-locales.index') }}/" + id + "/edit",
                type: 'GET',
                success: function(data) {
                    $('#id').val(data.id);
                    ['local', 'razon_social', 'direccion', 'ruc', 'telefono', 'celular', 'celular2', 'email', 'cta_cte_bcp_soles', 'cta_cci_bcp_soles',
                        'cta_cte_bcp_dolares', 'cta_cci_bcp_dolares', 'cta_cte_interbank_soles', 'cta_cci_interbank_soles',
                        'cta_cte_interbank_dolares', 'cta_cci_interbank_dolares', 'link_web', 'dominio_sistema'
                    ].forEach(function(field) {
                        $('#' + field).val(data[field]);
                    });
                    [
                        'cta_cte_bcp_soles',
                        'cta_cci_bcp_soles',
                        'cta_cte_bcp_dolares',
                        'cta_cci_bcp_dolares',
                        'cta_cte_interbank_soles',
                        'cta_cci_interbank_soles',
                        'cta_cte_interbank_dolares',
                        'cta_cci_interbank_dolares'
                    ].forEach(function(field) {
                        const value = data[field + '_check'];
                        const visible = value === true || value === 1 ||
                            ['1', 't', 'true', 'yes', 'on'].includes(String(value).toLowerCase());
                        $('#' + field + '_check').prop('checked', visible);
                    });
                    $('#qr_pago').prop('required', false);
                    if (data.qr_url) {
                        $('#qr_preview').attr('src', data.qr_url).removeClass('d-none');
                    }
                    $('#titulo_modal_info_locales').text('EDITAR DATOS DEL LOCAL');
                    $('#modal_info_locales').modal('show');
                },
                error: function(xhr) {
                    const message = xhr.responseJSON && xhr.responseJSON.msg ? xhr.responseJSON.msg : 'No se pudieron cargar los datos del local.';
                    maquinge.notificaciones(message, 'Sistema', 'error');
                }
            });
        }

        function guardar() {
            const form = document.getElementById('form_info_locales');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            $('.error_msg_local').addClass('d-none').text('');
            $('[id^="error_"]').addClass('d-none').text('');

            const formData = new FormData();
            const fields = ['id', 'local', 'razon_social', 'direccion', 'ruc', 'telefono', 'celular', 'celular2', 'email', 'cta_cte_bcp_soles', 'cta_cci_bcp_soles',
                'cta_cte_bcp_dolares', 'cta_cci_bcp_dolares', 'cta_cte_interbank_soles', 'cta_cci_interbank_soles',
                'cta_cte_interbank_dolares', 'cta_cci_interbank_dolares', 'link_web', 'dominio_sistema'
            ];
            fields.forEach(function(field) {
                let value = $('#' + field).val() || '';
                if (field === 'local' || field === 'razon_social' || field === 'direccion') {
                    value = value.toLocaleUpperCase('es');
                }
                formData.append(field, value);
            });
            [
                'cta_cte_bcp_soles',
                'cta_cci_bcp_soles',
                'cta_cte_bcp_dolares',
                'cta_cci_bcp_dolares',
                'cta_cte_interbank_soles',
                'cta_cci_interbank_soles',
                'cta_cte_interbank_dolares',
                'cta_cci_interbank_dolares'
            ].forEach(function(field) {
                formData.append(field + '_check', $('#' + field + '_check').prop('checked') ? '1' : '0');
            });
            if ($('#qr_pago')[0].files[0]) {
                formData.append('qr_pago', $('#qr_pago')[0].files[0]);
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                url: "{{ route('info-locales.store') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#tabla_info_locales').DataTable().ajax.reload(null, false);
                    maquinge.notificaciones(data.msg, 'Sistema', 'success');
                    cerrarModal();
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(field, messages) {
                            $('#error_' + field).removeClass('d-none').text(messages[0]);
                        });
                        return;
                    }
                    const message = xhr.responseJSON && xhr.responseJSON.msg ? xhr.responseJSON.msg : 'No se pudo guardar la información del local.';
                    maquinge.notificaciones(message, 'Sistema', 'error');
                }
            });
        }

        function del(id) {
            Swal.fire({
                title: '¿Está seguro de eliminar este local?',
                text: 'El registro se ocultará del listado y conservará su fecha de eliminación.',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then(function(result) {
                if (!result.value) {
                    return;
                }
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    url: "{{ route('info-locales.index') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        $('#tabla_info_locales').DataTable().ajax.reload(null, false);
                        maquinge.notificaciones(data.msg, 'Sistema', 'success');
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON && xhr.responseJSON.msg ? xhr.responseJSON.msg : 'No se pudo eliminar el registro.';
                        maquinge.notificaciones(message, 'Sistema', 'error');
                    }
                });
            });
        }

        function limpiarFormulario() {
            $('#form_info_locales')[0].reset();
            $('#id').val('');
            $('#qr_pago').prop('required', true);
            $('#qr_preview').attr('src', '').addClass('d-none');
            $('[id^="error_"]').addClass('d-none').text('');
        }

        function cerrarModal() {
            $('#modal_info_locales').modal('hide');
            limpiarFormulario();
        }
    </script>
@endpush
