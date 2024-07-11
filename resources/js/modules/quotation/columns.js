import { reactive } from "vue";
import i18n from "../../i18n";
import { statusTypes } from "../../utils/constraints";
const { t } = i18n.global

export const columns = reactive([
    {
        name: "id",
        required: true,
        label: t('id'),
        align: "center",
        field: (row) => row.id,
        format: (val) => `${val}`,
        sortable: true,
    },

    {
        name: "ref",
        required: false,
        label: t('table.ref'),
        align: "center",
        field: (row) => row.ref,
        format: (val) => `${val}`,
        sortable: false,
    },

    {
        name: "date",
        required: false,
        label: t('date'),
        align: "center",
        field: (row) => row.date,
        format: (val) => `${val}`,
        sortable: false,
    },

    {
        name: "warehouse",
        required: false,
        label: t('warehouse_id'),
        align: "center",
        field: (row) => row.warehouse,
        format: (val) => `${val ?? t('table.no_default')}`,
        sortable: false,
    },

    {
        name: "customer",
        required: false,
        label: t('customer_id'),
        align: "center",
        field: (row) => row.customer,
        format: (val) => `${val ?? t('table.no_default')}`,
        sortable: false,
    },

    {
        name: "grand_total",
        required: false,
        label: t('table.grand_total'),
        align: "center",
        field: (row) => row.grand_total,
        format: (val) => `${val}`,
        sortable: false,
    },

    {
        name: "created_at",
        required: false,
        label: t('created_at'),
        align: "center",
        field: (row) => row.created_at,
        format: (val) => `${val}`,
        sortable: true,
    },

    {
        name: "updated_at",
        required: false,
        label: t('updated_at'),
        align: "center",
        field: (row) => row.updated_at,
        format: (val) => `${val}`,
        sortable: true,
    },

    {
        name: "actions",
        required: false,
        label: t('actions'),
        align: "center",
        field: (row) => row.id,
        format: (val) => `${val}`,
        sortable: false,
    },
]);