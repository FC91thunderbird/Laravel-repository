import ToastService from 'primevue/toastservice';
import Toast from 'primevue/toast';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import Ripple from 'primevue/ripple';
import Paginator from 'primevue/paginator';
import AutoComplete from 'primevue/autocomplete';
import InputSwitch from 'primevue/inputswitch';
import Calendar from 'primevue/calendar';
import ProgressSpinner from 'primevue/progressspinner';
import RadioButton from 'primevue/radiobutton';
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';
import MultiSelect from 'primevue/multiselect';
import Tooltip from 'primevue/tooltip';
import Panel from 'primevue/panel';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import Breadcrumb from 'primevue/breadcrumb';
import FilterField from '@/components/common/FilterField.vue';
import ValidationMessage from '@/components/common/ValidationMessage.vue';
import ConfirmDialog from 'primevue/confirmdialog';
import ConfirmationService from 'primevue/confirmationservice';
import Textarea from 'primevue/textarea';


import PrimeVue from 'primevue/config';

import Sidebar from 'primevue/sidebar';

// import '../../css/primevue.css';
import "primeicons/primeicons.css";

const primeVue = {
    init : async function(app){
        // const currentLocale = localStorage.getItem('lang') || 'it';
        // let localeMessages = currentLocale === 'en' ? enMessages : itMessages;
        // let localeMessages =  enMessages;
        let options  = {
            ripple: true,
            zIndex: {
                modal: 1100,
                overlay: 9999,
                menu: 1000,
                tooltip: 1100
            },
            // locale: localeMessages
        }
        app.use(PrimeVue, options);

        app.component('Toast', Toast);
        app.component('Button', Button);
        app.component('Dialog', Dialog);
        app.component('Dropdown', Dropdown);
        app.component('Paginator', Paginator);
        app.component('AutoComplete', AutoComplete);
        app.component('InputSwitch', InputSwitch);
        app.component('Calendar', Calendar);
        app.component('ProgressSpinner', ProgressSpinner);
        app.component('Accordion', Accordion);
        app.component('AccordionTab', AccordionTab);
        app.component('MultiSelect', MultiSelect);
        app.component('RadioButton', RadioButton);
        app.component('Panel', Panel);
        app.component('DataTable', DataTable);
        app.component('Column', Column);
        app.component('InputText', InputText);
        app.component('FilterField', FilterField);
        app.component('Breadcrumb', Breadcrumb);
        app.component('ValidationMessage', ValidationMessage);
        app.component('ConfirmDialog', ConfirmDialog);
        app.component('Textarea', Textarea);


        app.component('Sidebar', Sidebar);

        app.use(ToastService);
        app.use(ConfirmationService);

        app.directive('ripple', Ripple);
        app.directive('tooltip', Tooltip);
    }
}

export default primeVue;
