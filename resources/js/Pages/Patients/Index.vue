<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {onMounted, ref, reactive, watchEffect, nextTick, onUnmounted, onBeforeUnmount} from 'vue';
import {searchAddress} from "@/Helper/Api.js";
import DialogModal from "@/Components/DialogModal.vue";
import FormSection from "@/Components/FormSection.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import Banner from "@/Components/Banner.vue";
import InputDate from "@/Components/InputDate.vue";
import SectionTitle from "@/Components/SectionTitle.vue";
import {back, formatCpf, formatDate} from "@/Helper/functions.js";
import {useForm, usePage} from "@inertiajs/vue3";
import ActionMessage from "@/Components/ActionMessage.vue";

defineEmits(['update:form.street', 'update:form.neighborhood', 'update:form.city', 'update:form.state']);

const props = defineProps({
    patients: {
        type: Array,
        default: () => [],
    },
});
const fileInput = ref(null);
const hasCsv = ref(false);
const fileName = ref(null);
const message = ref(null);
const statusActionMessage = ref(false);
const loading = ref(false);
const showModal = ref(false);
const filter = ref('');
const rows = ref([]);
const fileUrl = ref(null);
const placeholderImage = 'https://via.placeholder.com/150';
const requestType = ref('create');
const columns = [
    {
        id: 'id',
        label: 'ID',
        field: 'id',
        align: 'left',
        sortable: true,
    },
    {
        id: 'full_name',
        label: 'Nome Completo',
        field: row => `${row.first_name} ${row.last_name}`,
        align: 'left',
        sortable: true,
    },
    {
        id: 'birthday',
        label: 'Data de Nascimento',
        field: 'birthday',
        align: 'left',
        sortable: true,
        format: (val) => formatDate(val),
    },
    {
        id: 'mother_name',
        label: 'Nome da Mãe',
        field: 'mother_name',
        align: 'left',
        sortable: true,
    },
    {
        id: 'cpf',
        label: 'CPF',
        field: 'cpf',
        align: 'left',
        sortable: true,
        format: (val) => formatCpf(val),
    },
    {
        id: 'cns',
        label: 'CNS',
        field: 'cns',
        align: 'left',
        sortable: true,
    },
    {
        name: 'actions',
        label: 'Ações',
        field: 'actions',
        align: 'left',
        sortable: false,
        actions: true,
        type: 'button',
        component: 'ButtonAction',
    }
];

const form = reactive(useForm({
    first_name: '',
    last_name: '',
    birthday: '',
    mother_name: '',
    cpf: '',
    cns: '',
    photo: '',
    postal_code: '',
    street: '',
    number: '',
    complement: '',
    neighborhood: '',
    city: '',
    state: '',
}));

const openModal = () => {
    showModal.value = true;
}

const closeModal = () => {
    showModal.value = false;
    message.value = 'Mensagem de Teste';
    statusActionMessage.value = true;
    form.reset();
    fileUrl.value = null;
}

const handleFileChange = async (event) => {
    if (event.target.files.length > 0) {
        fileName.value = event.target.files[0].name;
        fileUrl.value = URL.createObjectURL(event.target.files[0]);
        const file = event.target.files[0];
        const formData = new FormData();
        formData.append('file', file);
        try {
            const response = await axios.post(route('patients.uploadPatientImage'), formData);
            const data = response.data;
            form.photo = data.url;
            fileUrl.value = data.url;
        } catch (error) {
            console.error('Error uploading file:', error);
        }
    } else {
        fileUrl.value = null;
    }
};

const editPatient = (row) => {
    form.id = row.id;
    form.cpf = formatCpf(row.cpf);
    form.cns = row.cns;
    form.first_name = row.first_name;
    form.last_name = row.last_name;
    form.birthday = row.birthday;
    form.mother_name = row.mother_name;
    form.photo = row.photo;
    fileUrl.value = row.photo;
    form.postal_code = row.address.postal_code;
    form.street = row.address.street;
    form.number = row.address.number.toString();
    form.complement = row.address.complement;
    form.neighborhood = row.address.neighborhood;
    form.city = row.address.city;
    form.state = row.address.state;
    requestType.value = 'update';
    console.log(form)
    openModal();
};

const deletePatient = (row) => {
    form.delete(route('patients.destroy', { patients: row.id, patient: row.id }), {
        preserveScroll: true,
        onSuccess: () => {
            rows.value = rows.value.filter((item) => item.id !== row.id);
            message.value = 'Paciente excluído com sucesso!';
            statusActionMessage.value = true;
        },
        onError: () => console.log('error'),
        onFinish: () => {
            loading.value = false;
        },
    });
};

const submitPatient = () => {
    if (requestType.value === 'create') {
        createPatient();
    } else {
        updatePatient();
    }
};

const createPatient = async () => {
    loading.value = true;
    try {
        form
            .transform((data) => ({
                ...data,
                cpf: data.cpf.replace(/\D/g, '').toString(),
                postal_code: data.postal_code.replace(/\D/g, '').toString(),
            }))
            .post(route('patients.store'), {
                preserveScroll: true,
                onSuccess: (data) => {
                    rows.value.push(data);
                    message.value = 'Paciente cadastrado com sucesso!';
                    statusActionMessage.value = true;
                    closeModal();
                    form.reset()
                },
                onError: (e) => console.log('error'),
                onFinish: () => {
                    loading.value = false;
                },
            });
    } catch (error) {
        console.error('Error creating patient:', error);
    }
    loading.value = false;
};

const updatePatient = async () => {
    console.log('update');
    loading.value = true;
    try {
        form
            .transform((data) => ({
                ...data,
                cpf: data.cpf.replace(/\D/g, '').toString(),
                postal_code: data.postal_code.replace(/\D/g, '').toString(),
            }))
            .put(route('patients.update', { patients: form.id, patient: form.id }), {
                preserveScroll: true,
                onSuccess: (data) => {
                    rows.value.push(data);
                    message.value = 'Paciente atualizado com sucesso!';
                    statusActionMessage.value = true;
                    closeModal();
                    form.reset();
                },
                onError: (e) => console.log('error'),
                onFinish: () => {
                    loading.value = false;
                },
            });
    } catch (error) {
        console.error('Error updating patient:', error);
    }
    loading.value = false;
};

const exportTable = async (fileType = 'csv') => {
    try {
        const response = await axios.get(route('patients.export', { type: 'csv' }), { responseType: 'blob' });
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'patients.' + fileType);
        document.body.appendChild(link);
        link.click();
    } catch (error) {
        console.error('Error exporting table:', error);
    }
};

const searchPostalCode = async () => {
    let postalCodeData = await searchAddress(form.postal_code.replace(/\D/g, '').toString());
    if (postalCodeData) {
        form.street = postalCodeData.logradouro || '';
        form.neighborhood = postalCodeData.bairro || '';
        form.city = postalCodeData.localidade || '';
        form.state = postalCodeData.uf || '';
    }
};

const handleFileChangeImport = (event) => {
    fileInput.value = event.target.files[0];
    hasCsv.value = true;
};

const openFileSelector = () => {
    fileInput.value.click();
};

const importPatients = async () => {
    if (!fileInput.value.files.length) {
        console.error('No file selected');
        return;
    }

    const formData = new FormData();
    formData.append('file', fileInput.value.files[0]);

    try {
        const response = await axios.post(route('patients.import'), formData);
        console.log('Import started:', response.data);
        fileInput.value = null;
        await nextTick(() => {
            hasCsv.value = false;
        });
    } catch (error) {
        console.error('Error starting import:', error);
    }
};

const socketTest = () => {
    window.Echo.channel('pacientes_database_jobcompleted')
        .listen('JobCompleted', (e) => {
            console.log(e);
            message.value = e.message
            statusActionMessage.value = true
        });
};

onMounted(() => {
    console.log('mounted')

    window.Echo.connector.socket.on('connect', () => {
        console.log('Connected to the WebSocket server.');
        socketTest();
    });
    console.log(window.Echo.socketId());

})

onUnmounted(() => {
    console.log('unmounted')
    window.Echo.leaveChannel('pacientes_database_jobcompleted')
})

onBeforeUnmount(() => {
    console.log('before unmount')
    window.Echo.channel('pacientes_database_jobcompleted')
        .stopListening('JobCompleted')
})

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex flex-row items-center justify-between">
                <div class="flex flex-row justify-between items-center">
                    <q-btn
                        flat
                        round
                        dense
                        icon="arrow_back"
                        @click="back"
                        class="q-ma-md"
                    />
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Pacientes {{message + ' + ' + statusActionMessage}}
                    </h2>
                </div>
                <div class="flex flex-row items-center">
                </div>
            </div>
        </template>
        <ActionMessage
            :on="statusActionMessage"
            @close="statusActionMessage = false"
            :duration="1000"
            class="fixed top-0 right-0 m-4 p-4 bg-blue-500 border border-blue-700 shadow-lg"
        >
            {{ message }}
        </ActionMessage>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <q-table
                        :rows="props.patients"
                        :columns="columns"
                        row-key="name"
                        no-data-label="Nenhum paciente encontrado"
                    >
                        <template v-slot:body-cell-actions="{ row }">
                            <div class="flex flex-row justify-evenly items-center mt-3">
                                <q-btn color="primary" icon="edit" size="sm" @click="editPatient(row)" />
                                <q-btn color="negative" icon="close" size="sm" @click="deletePatient(row)" />
                            </div>
                        </template>
                        <template v-slot:top>
                            <q-btn color="primary" icon="add" size="sm" class="q-ml-sm"  :disable="loading" label="Paciente" @click="openModal" />
                            <q-btn v-if="props.patients.length !== 0" size="sm" class="q-ml-sm" color="primary" :disable="loading" label="CSV" @click="() => exportTable('csv')" />
                            <q-btn v-if="!hasCsv" size="sm" icon="upload_file" class="q-ml-sm"  color="primary" @click="openFileSelector">Importar Pacientes</q-btn>
                            <q-btn v-else color="primary" size="sm" icon="upload_file" class="q-ml-sm"  @click="importPatients">Enviar CSV</q-btn>
                            <input type="file" ref="fileInput" @change="handleFileChangeImport" hidden />
                            <q-space />
                            <q-input borderless dense debounce="300" color="primary" v-model="filter">
                                <template v-slot:append>
                                    <q-icon name="search" />
                                </template>
                            </q-input>
                        </template>
                    </q-table>
                </div>
            </div>
            <DialogModal
                :show="showModal"
                @close="closeModal"
            >
                <template #title >
                    Novo Paciante
                </template>
                <template #content>
                    <FormSection>
                        <template #title>
                            <SectionTitle>
                                <template #title>
                                    Dados Pessoais
                                </template>
                            </SectionTitle>
                        </template>
                        <template #form>
                            <div class="flex flex-col justify-start items-center">
                                <div class="flex flex-col items-center">
                                    <label for="photo">Foto</label>
                                    <div class="relative w-32 h-32 rounded-full overflow-hidden">
                                        <input type="file" id="photo" name="photo" accept="image/*" class="absolute opacity-0 w-full h-full" @change="handleFileChange" />
                                        <img :src="fileUrl || placeholderImage" alt="Selected file" class="w-full h-full object-cover" />
                                    </div>
                                </div>
                                <div class="flex flex-row justify-between items-center">
                                    <div class="flex flex-col mx-5">
                                        <InputLabel for="firstName">Nome</InputLabel>
                                        <TextInput
                                            id="firstName"
                                            name="firstName"
                                            :model-value="form.first_name"
                                            @update:model-value="value => form.first_name = value"
                                        />
                                        <InputError :message="form.errors.first_name" />
                                    </div>
                                    <div class="flex flex-col mx-5">
                                        <InputLabel for="lastName">Sobrenome</InputLabel>
                                        <TextInput
                                            id="lastName"
                                            name="lastName"
                                            :model-value="form.last_name"
                                            @update:model-value="value => form.last_name = value"
                                        />
                                        <InputError :message="form.errors.last_name" />
                                    </div>
                                    <div class="flex flex-col mx-5">
                                        <InputLabel for="birthday">Data de Nascimento</InputLabel>
                                        <InputDate
                                            id="birthday"
                                            name="birthday"
                                            :model-value="form.birthday"
                                            @update:model-value="value => form.birthday = value"
                                        />
                                        <InputError :message="form.errors.birthday" />
                                    </div>
                                    <div class="flex flex-col mx-5">
                                        <InputLabel for="motherName">Nome da Mãe</InputLabel>
                                        <TextInput
                                            id="motherName"
                                            name="motherName"
                                            :model-value="form.mother_name"
                                            @update:model-value="value => form.mother_name = value"
                                        />
                                        <InputError :message="form.errors.mother_name" />
                                    </div>
                                    <div class="flex flex-col mx-5">
                                        <InputLabel for="cpf">CPF</InputLabel>
                                        <TextInput
                                            id="cpf"
                                            name="cpf"
                                            mask-input="###.###.###-##"
                                            :model-value="form.cpf"
                                            @update:model-value="value => form.cpf = value"
                                        />
                                        <InputError :message="form.errors.cpf" />
                                    </div>
                                    <div class="flex flex-col mx-5">
                                        <InputLabel for="cns">CNS</InputLabel>
                                        <TextInput
                                            id="cns"
                                            name="cns"
                                            :model-value="form.cns"
                                            @update:model-value="value => form.cns = value"
                                        />
                                        <InputError :message="form.errors.cns" />
                                    </div>
                                </div>
                            </div>
                        </template>
                    </FormSection>
                    <FormSection >
                        <template #title>
                            <SectionTitle>
                                <template #title>
                                    Endereço
                                </template>
                            </SectionTitle>
                        </template>
                        <template #form>
                            <div class="flex flex-col justify-start items-center">
                                <div class="flex flex-row justify-between items-center">
                                    <div class="flex flex-col mx-5">
                                        <InputLabel for="postal_code">CEP</InputLabel>
                                        <TextInput
                                            id="postal_code"
                                            name="postal_code"
                                            mask-input="#####-###"
                                            :model-value="form.postal_code"
                                            @update:model-value="value => form.postal_code = value"
                                        />
                                        <InputError :message="form.errors.postal_code" />
                                    </div>
                                    <div class="flex flex-col justify-between items-start">
                                        <PrimaryButton @click="searchPostalCode">
                                            Buscar
                                        </PrimaryButton>
                                    </div>
                                    <div class="flex flex-col mx-5">
                                        <InputLabel for="street">Rua / Av.</InputLabel>
                                        <TextInput
                                            id="street"
                                            name="street"
                                            :model-value="form.street"
                                            @update:model-value="value => form.street = value"
                                            :disabled="form.street !== ''"
                                        />
                                        <InputError :message="form.errors.street" />
                                    </div>
                                    <div class="flex flex-col mx-5">
                                        <InputLabel for="number">Número</InputLabel>
                                        <TextInput
                                            id="number"
                                            name="number"
                                            :model-value="form.number"
                                            @update:model-value="value => form.number = value"
                                        />
                                        <InputError :message="form.errors.number" />
                                    </div>
                                    <div class="flex flex-col mx-5">
                                        <InputLabel for="complement">Complemento</InputLabel>
                                        <TextInput
                                            id="complement"
                                            name="complement"
                                            :model-value="form.complement"
                                            @update:model-value="value => form.complement = value"
                                        />
                                        <InputError :message="form.errors.complement" />
                                    </div>
                                    <div class="flex flex-col mx-5">
                                        <InputLabel for="neighborhood">Bairro</InputLabel>
                                        <TextInput
                                            id="neighborhood"
                                            name="neighborhood"
                                            :model-value="form.neighborhood"
                                            @update:model-value="value => form.neighborhood = value"
                                            :disabled="form.neighborhood !== ''"
                                        />
                                        <InputError :message="form.errors.neighborhood" />
                                    </div>
                                    <div class="flex flex-col mx-5">
                                        <InputLabel for="city">Cidade</InputLabel>
                                        <TextInput
                                            id="city"
                                            name="city"
                                            :model-value="form.city"
                                            @update:model-value="value => form.city = value"
                                            :disabled="form.city !== ''"
                                        />
                                        <InputError :message="form.errors.city" />
                                    </div>
                                    <div class="flex flex-col mx-5">
                                        <InputLabel for="state">Estado</InputLabel>
                                        <TextInput
                                            id="state"
                                            name="state"
                                            :model-value="form.state"
                                            @update:model-value="value => form.state = value"
                                            :disabled="form.state !== ''"
                                        />
                                        <InputError :message="form.errors.state" />
                                    </div>
                                </div>
                            </div>
                        </template>
                    </FormSection>
                </template>
                <template #footer>
                    <div class="flex flex-row justify-between items-center">
                        <div class="flex flex-col mx-1">
                            <PrimaryButton @click="submitPatient">
                                Salvar
                            </PrimaryButton>
                        </div>
                        <div class="flex flex-col mx-1">
                            <DangerButton label="Cancelar" @click="closeModal" >
                                Cancelar
                            </DangerButton>
                        </div>
                    </div>
                </template>
            </DialogModal>
            <Banner
                v-if="message"
                :message="message"
                :status="statusActionMessage"
                @close="statusActionMessage = false"
            />
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
