<script setup>
    import { ref, reactive } from 'vue';
    import { useForm } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    const props = defineProps({
        categoriesProp: {
            type: Object,
        }
    });
    const categories = ref(props.categoriesProp);
    const createForm = useForm({
        name: null,
    });
    const openCreateModal = () => {};
    const isOpenCreate = ref(false);
    const hasMessage = ref('');
    const handleOpenCreate = () => {
        isOpenCreate.value = true;
    };
    const handleCreate = () => {
        createForm.post(route('categories.store'), {
            onSuccess: () => {
                isOpenCreate.value = false
            }
        });
        
    };
</script>
<template>
    <AuthenticatedLayout>
        <div class="pl-5">
            <input 
                type="text"
                class="w-64 h-12 rounded-full border-slate-300"
                placeholder="Buscar...">
        </div>
        <div class="flex justify-between ml-5">
            <h1>Lista de categorias</h1>
            <button 
                class="bg-indigo-700 text-white text-center px-4 py-2 rounded-s-full"
                @click="handleOpenCreate">
                Add new
            </button>
        </div>
        <hr>
        <a-alert
            type="success"
            v-if="hasMessage"
            :message="hasMessage"
            closable/>
        <table class="border w-full ml-5">
            <thead>
                <tr class="bg-indigo-700 text-white py-3">
                    <th class="text-left w-10">Sr#</th>
                    <th class="text-left">Category</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="category in categories">
                    <td class="py-3">{{ category.id }}</td>
                    <td class="py-3">{{ category.name }}</td>
                    <td class="py-3"></td>
                    <td class="py-3"></td>
                </tr>
            </tbody>
        </table>
        <a-modal
            title="Crear una categoria"
            v-model:open="isOpenCreate"
            :ok-button-props="{ class: ['hidden'] }">
            <a-form
                :model="createForm"
                layout="vertical"
                @finish="handleCreate">
                <div class="mb-4">
                    <a-form-item
                        label="Categoria"
                        name="name"
                        :rules="[{ required: false, message: 'Ingrese la categoria' }]">
                        <a-input
                            v-model:value="createForm.name"/>
                    </a-form-item>
                    <p v-if="createForm.errors.name" class="text-red-700"> {{ createForm.errors.name }}</p>
                </div>
                <button class="rounded py-2 px-4 bg-green-500 text-white mt-2">Crear</button>
            </a-form>
        </a-modal>
    </AuthenticatedLayout>
</template>