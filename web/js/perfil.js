// Clase para manejar la funcionalidad del perfil
class PerfilManager {
    constructor() {
        this.gustosSeleccionados = new Set();
        this.motivosSeleccionados = new Set();
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        // Obtener la URL de actualización directamente del meta tag
        this.updateProfileInfoUrl = document.querySelector('meta[name="update-profile-info-url"]').content;
        
        this.init();
    }

    init() {
        console.log('Inicializando PerfilManager');
        console.log('URL de actualización:', this.updateProfileInfoUrl);
        this.setupAjax();
        this.setupEventListeners();
        this.setupModals();
    }

    setupAjax() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': this.csrfToken
            }
        });
    }

    setupEventListeners() {
        // Guardar descripción
        $('#guardarDescripcion').on('click', (e) => {
            e.preventDefault();
            this.guardarDescripcion();
        });

        // Guardar gustos
        $('#guardarGustos').on('click', (e) => {
            e.preventDefault();
            this.guardarGustos();
        });

        // Guardar motivos
        $('#guardarMotivos').on('click', (e) => {
            e.preventDefault();
            this.guardarMotivos();
        });

        // Manejar gustos
        $('.gustos-buttons button').on('click', (e) => {
            const gusto = $(e.currentTarget).data('gusto');
            this.toggleGusto(gusto);
        });

        // Manejar motivos
        $('.motivos-buttons button').on('click', (e) => {
            const motivo = $(e.currentTarget).data('motivo');
            this.toggleMotivo(motivo);
        });

        // Agregar gusto personalizado
        $('#addCustomGusto').on('click', () => {
            this.agregarGustoPersonalizado();
        });
    }

    setupModals() {
        // Modal de descripción
        $('#editDescriptionModal').on('show.bs.modal', () => {
            const currentValue = $('#descripcion').data('current-value');
            // Convertir los saltos de línea HTML a saltos de línea reales
            const formattedValue = currentValue.replace(/<br\s*\/?>/g, '\n');
            $('#descripcion').val(formattedValue);
        });

        // Modal de gustos
        $('#editGustosModal').on('show.bs.modal', () => {
            this.inicializarGustos();
        });

        // Modal de motivos
        $('#editMotivosModal').on('show.bs.modal', () => {
            this.inicializarMotivos();
        });
    }

    async guardarDescripcion() {
        const descripcion = $('#descripcion').val().trim();
        
        if (!descripcion) {
            alert('Por favor, ingrese una descripción');
            return;
        }

        try {
            const response = await $.ajax({
                url: this.updateProfileInfoUrl,
                type: 'POST',
                data: {
                    descripcion: descripcion,
                    _csrf: this.csrfToken
                }
            });

            if (response.success) {
                this.mostrarMensajeExito('editDescriptionModal');
                this.cerrarModal('editDescriptionModal');
            } else {
                this.mostrarError(response.message || 'Error al guardar la descripción');
            }
        } catch (error) {
            this.mostrarError('Error al guardar la descripción');
            console.error('Error:', error);
        }
    }

    async guardarGustos() {
        const gustosArray = Array.from(this.gustosSeleccionados);
        
        if (gustosArray.length === 0) {
            alert('Por favor, seleccione al menos un gusto');
            return;
        }

        try {
            const response = await $.ajax({
                url: this.updateProfileInfoUrl,
                type: 'POST',
                data: {
                    gustos: JSON.stringify(gustosArray),
                    _csrf: this.csrfToken
                }
            });

            if (response.success) {
                this.mostrarMensajeExito('editGustosModal');
                this.cerrarModal('editGustosModal');
            } else {
                this.mostrarError(response.message || 'Error al guardar los gustos');
            }
        } catch (error) {
            this.mostrarError('Error al guardar los gustos');
            console.error('Error:', error);
        }
    }

    async guardarMotivos() {
        const motivosArray = Array.from(this.motivosSeleccionados);
        
        if (motivosArray.length === 0) {
            alert('Por favor, seleccione al menos un motivo');
            return;
        }

        try {
            const response = await $.ajax({
                url: this.updateProfileInfoUrl,
                type: 'POST',
                data: {
                    motivo: JSON.stringify(motivosArray),
                    _csrf: this.csrfToken
                }
            });

            if (response.success) {
                this.mostrarMensajeExito('editMotivosModal');
                this.cerrarModal('editMotivosModal');
            } else {
                this.mostrarError(response.message || 'Error al guardar los motivos');
            }
        } catch (error) {
            this.mostrarError('Error al guardar los motivos');
            console.error('Error:', error);
        }
    }

    toggleGusto(gusto) {
        if (gusto === 'Otros') {
            $('#otrosGustoInput').toggle();
            return;
        }

        const $button = $(`.gustos-buttons button[data-gusto="${gusto}"]`);
        if ($button.hasClass('active')) {
            $button.removeClass('active');
            this.gustosSeleccionados.delete(gusto);
        } else {
            $button.addClass('active');
            this.gustosSeleccionados.add(gusto);
        }

        this.actualizarGustosInput();
    }

    toggleMotivo(motivo) {
        const $button = $(`.motivos-buttons button[data-motivo="${motivo}"]`);
        if ($button.hasClass('active')) {
            $button.removeClass('active');
            this.motivosSeleccionados.delete(motivo);
        } else {
            $button.addClass('active');
            this.motivosSeleccionados.add(motivo);
        }

        this.actualizarMotivosInput();
    }

    agregarGustoPersonalizado() {
        const customGusto = $('#customGusto').val().trim();
        if (customGusto) {
            this.gustosSeleccionados.add(customGusto);
            this.actualizarGustosInput();
            $('#customGusto').val('');
            $('#otrosGustoInput').hide();
        }
    }

    inicializarGustos() {
        this.gustosSeleccionados = new Set();
        $('#gustosInput').val('');
        $('.gustos-buttons button').removeClass('active');
        
        try {
            const gustosActuales = JSON.parse($('#gustosInput').data('current-value') || '[]');
            if (Array.isArray(gustosActuales)) {
                gustosActuales.forEach(gusto => {
                    this.gustosSeleccionados.add(gusto);
                    $(`.gustos-buttons button[data-gusto="${gusto}"]`).addClass('active');
                });
                this.actualizarGustosInput();
            }
        } catch (e) {
            console.error('Error al cargar gustos:', e);
        }
    }

    inicializarMotivos() {
        this.motivosSeleccionados = new Set();
        $('#motivosInput').val('');
        $('.motivos-buttons button').removeClass('active');
        
        try {
            const motivosActuales = JSON.parse($('#motivosInput').data('current-value') || '[]');
            if (Array.isArray(motivosActuales)) {
                motivosActuales.forEach(motivo => {
                    this.motivosSeleccionados.add(motivo);
                    $(`.motivos-buttons button[data-motivo="${motivo}"]`).addClass('active');
                });
                this.actualizarMotivosInput();
            }
        } catch (e) {
            console.error('Error al cargar motivos:', e);
        }
    }

    actualizarGustosInput() {
        $('#gustosInput').val(Array.from(this.gustosSeleccionados).join(', '));
    }

    actualizarMotivosInput() {
        $('#motivosInput').val(Array.from(this.motivosSeleccionados).join(', '));
    }

    mostrarMensajeExito(modalId) {
        $(`#${modalId} .btn-primary`).addClass('d-none');
        $(`#${modalId} .btn-success`).removeClass('d-none').text('¡Guardado! Cerrar');
    }

    mostrarError(mensaje) {
        alert(mensaje);
    }

    cerrarModal(modalId) {
        setTimeout(() => {
            $(`#${modalId}`).modal('hide');
            window.location.reload();
        }, 1500);
    }
}

// Inicializar cuando el documento esté listo
$(document).ready(() => {
    window.perfilManager = new PerfilManager();
}); 