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
        name: "logo",
        required: false,
        label: t('logo'),
        align: "center",
        field: (row) => row.logo,
        format: (val) => `${val.url}`,
        sortable: false,
    },

    {
        name: "name",
        required: false,
        label: t('name'),
        align: "center",
        field: (row) => row.name,
        format: (val) => `${val}`,
        sortable: false,
    },

    {
        name: "parent",
        required: false,
        label: t('table.parent_category'),
        align: "center",
        field: (row) => row.category,
        format: (val) => `${val?.name ?? '----'}`,
        sortable: false,
    },

    {
        name: "is_active",
        required: false,
        label: t('is_active'),
        align: "center",
        field: (row) => row.is_active,
        format: (val) => `${statusTypes.find(st => st.value === val).label}`,
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
