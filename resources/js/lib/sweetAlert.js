import Swal from 'sweetalert2';

const baseAlert = Swal.mixin({
    buttonsStyling: false,
    reverseButtons: true,
    customClass: {
        popup: 'rounded-[22px] border border-slate-200 shadow-2xl',
        title: 'font-display text-2xl font-extrabold tracking-tight text-slate-900',
        htmlContainer: 'text-sm leading-6 text-slate-500',
        confirmButton:
            'inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700',
        cancelButton:
            'mr-3 inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50',
    },
});

const toastAlert = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3200,
    timerProgressBar: true,
    customClass: {
        popup: 'rounded-2xl border border-slate-200 shadow-xl',
        title: 'text-sm font-semibold text-slate-800',
    },
});

export const showSuccessAlert = (title) =>
    toastAlert.fire({
        icon: 'success',
        title,
    });

export const showErrorAlert = (title) =>
    toastAlert.fire({
        icon: 'error',
        title,
    });

export const showFlashAlert = (flash = {}) => {
    if (flash.success) {
        return showSuccessAlert(flash.success);
    }

    if (flash.error) {
        return showErrorAlert(flash.error);
    }

    return null;
};

export const confirmDeleteAlert = async ({
    title = 'Confirmar exclusão',
    text = 'Essa ação não poderá ser desfeita.',
    confirmButtonText = 'Sim, excluir',
    cancelButtonText = 'Cancelar',
} = {}) => {
    const result = await baseAlert.fire({
        icon: 'warning',
        title,
        text,
        showCancelButton: true,
        confirmButtonText,
        cancelButtonText,
        iconColor: '#dc2626',
    });

    return result.isConfirmed;
};

export default Swal;
