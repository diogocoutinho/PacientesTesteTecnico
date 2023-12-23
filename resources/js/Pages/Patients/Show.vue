<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {back, formatCpf, formatDate, formatPostalCode} from "../../Helper/functions.js";

const props = defineProps({
    patient: Object,
});

const editPatient = () => {
    console.log(props.patient[0].id)
    window.location.href = route('patients.edit', props.patient[0].id);
}
</script>

<template>
    <AppLayout :title="patient[0].first_name + ' ' + patient[0].last_name">
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
                        Paciente {{ patient[0].first_name + ' ' + patient[0].last_name }}
                    </h2>
                </div>
                <div class="flex flex-row items-center">
                    <q-btn
                        flat
                        round
                        dense
                        icon="edit"
                        @click="editPatient"
                        class="q-ma-md"
                    />
                    <q-btn
                        flat
                        round
                        dense
                        icon="delete"
                        @click="back"
                        class="q-ma-md"
                    />
                </div>
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="patient" class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex flex-col justify-between items-center w-full">
                            <div class="flex flex-col justify-between items-start">
                                <div class="relative w-32 h-32 rounded-full overflow-hidden">
                                    <img :src="patient[0].photo" alt="Foto do Paciente" class="w-full h-full object-cover" />
                                </div>
                            </div>
                            <QSeparator class="q-ma-md" />
                            <div class="flex flex-col justify-start items-start">
                                <div class="flex flex-row justify-around items-start w-full">
                                    <div class="flex flex-col justify-start items-start">
                                        <div class="flex flex-col justify-between items-center">
                                            <h1 class="text-2xl font-bold text-gray-800">Dados Pessoais</h1>
                                        </div>
                                        <QSeparator vertical class="q-ma-md" />
                                        <p>Nome: {{ patient[0].first_name + ' ' + patient[0].last_name }}</p>
                                        <p>Data de Nascimento: {{ formatDate(patient[0].birthday) }}</p>
                                        <p>Nome da Mãe: {{ patient[0].mother_name }}</p>
                                        <p>CPF: {{ formatCpf(patient[0].cpf) }}</p>
                                        <p>CNS: {{ patient[0].cns }}</p>
                                    </div>
                                    <QSeparator vertical class="q-ma-md" />
                                    <div class="flex flex-col justify-start items-start">
                                        <div class="flex flex-col justify-between items-center">
                                            <h1 class="text-2xl font-bold text-gray-800">Endereço</h1>
                                        </div>
                                        <QSeparator vertical class="q-ma-md" />
                                        <p>CEP: {{ formatPostalCode(patient[0].address.postal_code) }}</p>
                                        <p>Logradouro: {{ patient[0].address.street }}</p>
                                        <p>Número: {{ patient[0].address.number }}</p>
                                        <p>Complemento: {{ patient[0].address.complement }}</p>
                                        <p>Bairro: {{ patient[0].address.neighborhood }}</p>
                                        <p>Cidade: {{ patient[0].address.city }}</p>
                                        <p>Estado: {{ patient[0].address.state }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
