import './bootstrap';
import 'flowbite';
import { Modal } from 'flowbite';

const $targetEl = document.getElementById('modalEl');

if ($targetEl !== null) {
    // options with default values
    const options = {
        placement: 'bottom-right',
        backdrop: 'dynamic',
        backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
        closable: true,
        onHide: () => {
            console.log('modal is hidden');
        },
        onShow: () => {
            console.log('modal is shown');
        },
        onToggle: () => {
            console.log('modal has been toggled');
        }
    };

    window.modalPush = new Modal($targetEl, options);
}


const modalBalance = document.getElementById('modalBalance');

if (modalBalance !== null) {
// options with default values
    const optionsBalance = {
        placement: 'bottom-right',
        backdrop: 'dynamic',
        backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
        closable: true,
        onHide: () => {
            console.log('modal is hidden');
        },
        onShow: () => {
            console.log('modal is shown');
        },
        onToggle: () => {
            console.log('modal has been toggled');
        }
    };

    window.modalBalance = new Modal(modalBalance, optionsBalance);
}


const modalCodeBank = document.getElementById('modalCodeBank');

if (modalCodeBank !== null) {
// options with default values
    const optionsCodeBank = {
        placement: 'bottom-right',
        backdrop: 'dynamic',
        backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
        closable: true,
        onHide: () => {
            console.log('modal is hidden');
        },
        onShow: () => {
            console.log('modal is shown');
        },
        onToggle: () => {
            console.log('modal has been toggled');
        }
    };

    window.modalCodeBank = new Modal(modalCodeBank, optionsCodeBank);
}

const modalCallBank = document.getElementById('modalCallBank');

if (modalCallBank !== null) {
// options with default values
    const optionsCallBank = {
        placement: 'bottom-right',
        backdrop: 'dynamic',
        backdropClasses: 'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
        closable: true,
        onHide: () => {
            console.log('modal is hidden');
        },
        onShow: () => {
            console.log('modal is shown');
        },
        onToggle: () => {
            console.log('modal has been toggled');
        }
    };

    window.modalCallBank = new Modal(modalCallBank, optionsCallBank);
}
